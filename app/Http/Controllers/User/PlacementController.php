<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PlacementQuestion;
use App\Models\PlacementResult;
use App\Models\UserStat;
use App\Models\Level;
use App\Models\Stage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PlacementController extends Controller
{
    /**
     * Tampilkan 15 soal placement: 5 Easy + 5 Medium + 5 Hard (diacak urutannya)
     */
    public function show()
    {
        if (Auth::user()->has_taken_placement) {
            return redirect()->route('user.dashboard');
        }

        // ✅ Ambil 5 soal acak per difficulty
        $easyQs   = PlacementQuestion::where('is_active', true)->where('difficulty', 'easy')->inRandomOrder()->take(5)->get();
        $mediumQs = PlacementQuestion::where('is_active', true)->where('difficulty', 'medium')->inRandomOrder()->take(5)->get();
        $hardQs   = PlacementQuestion::where('is_active', true)->where('difficulty', 'hard')->inRandomOrder()->take(5)->get();

        // Gabungkan & acak urutan tampilannya
        $questions = $easyQs->concat($mediumQs)->concat($hardQs)->shuffle();

        if ($questions->count() < 15) {
            return redirect()->route('user.dashboard')
                ->with('error', 'Jumlah soal placement belum lengkap (min. 5 per level). Hubungi admin.');
        }

        return view('user.placement.test', compact('questions'));
    }

    /**
     * Hitung skor & tentukan stage berdasarkan penguasaan per level
     */
    public function submit(Request $request)
    {
        $user = Auth::user();
        
        if ($user->has_taken_placement) {
            return redirect()->route('user.dashboard');
        }

        $answers = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|string'
        ]);

        $easy_correct   = 0;
        $medium_correct = 0;
        $hard_correct   = 0;

        // Hitung kebenaran per difficulty
        foreach ($answers['answers'] as $qId => $userAnswer) {
            $question = PlacementQuestion::find($qId);
            if ($question && $question->isCorrect($userAnswer)) {
                if ($question->difficulty === 'easy')   $easy_correct++;
                elseif ($question->difficulty === 'medium') $medium_correct++;
                else $hard_correct++;
            }
        }

        // 🎯 LOGIKA PENENTUAN STAGE (Threshold 60% per level)
        if ($hard_correct >= 3) {
            $assignedStageId = 3; // Advanced (Kelas 9)
        } elseif ($medium_correct >= 3) {
            $assignedStageId = 2; // Intermediate (Kelas 8)
        } else {
            $assignedStageId = 1; // Beginner (Kelas 7)
        }

        $total_score = round((($easy_correct + $medium_correct + $hard_correct) / 15) * 100);

        // ✅ DB TRANSACTION: Hanya untuk operasi database
        DB::transaction(function() use ($user, $total_score, $assignedStageId) {
            // 1. Simpan hasil placement
            PlacementResult::updateOrCreate(
                ['id_user' => $user->id_user],
                [
                    'score' => $total_score, 
                    'assigned_stage_id' => $assignedStageId,
                    'taken_at' => now()
                ]
            );
            
            // 2. Buat UserStat
            UserStat::updateOrCreate(
                ['id_user' => $user->id_user],
                [
                    'current_stage_id' => $assignedStageId,  // ✅ SUDAH DIPERBAIKI (hapus "cnet stop")
                    'last_activity' => now()
                ]
            );
            
            // 3. Auto-unlock Level sesuai Stage
            Level::where('id_stage', '<=', $assignedStageId)
                ->update(['status_kunci' => false]);
            
            // 4. Update Flag User
            $user->update(['has_taken_placement' => true]);
        });

        // ✅ PERSIAPAN DATA UNTUK HALAMAN RESULT (Di luar transaction)
        $stageName = Stage::find($assignedStageId)?->stage_name ?? 'Beginner';

        $viewData = [
            'score' => $total_score,
            'stageName' => $stageName,
            'stageColor' => match($assignedStageId) {
                3 => 'border-purple-500', 2 => 'border-blue-500', default => 'border-emerald-500'
            },
            'stageBg' => match($assignedStageId) {
                3 => 'from-purple-400 to-pink-500', 2 => 'from-blue-400 to-cyan-500', default => 'from-emerald-400 to-teal-500'
            },
            'stageTextColor' => match($assignedStageId) {
                3 => 'text-purple-600', 2 => 'text-blue-600', default => 'text-emerald-600'
            },
            'stageBar' => match($assignedStageId) {
                3 => 'bg-purple-500', 2 => 'bg-blue-500', default => 'bg-emerald-500'
            },
            'stageBadge' => match($assignedStageId) {
                3 => 'bg-purple-100 text-purple-800', 2 => 'bg-blue-100 text-blue-800', default => 'bg-emerald-100 text-emerald-800'
            },
            'stageIcon' => match($assignedStageId) { 3 => '🚀', 2 => '🌟', default => '🌱' },
            'statusTitle' => match($assignedStageId) { 
                3 => 'Masya Allah, Luar Biasa!', 
                2 => 'Bagus Sekali!', 
                default => 'Semangat Belajar!' 
            },
            'statusDesc' => match($assignedStageId) { 
                3 => 'Kamu siap untuk materi Advanced (Nahwu & Sharaf).', 
                2 => 'Kamu siap melanjutkan ke materi Intermediate.', 
                default => 'Mari mulai dari dasar agar pondasimu kuat.' 
            },
            'kelasEquivalent' => match($assignedStageId) { 3 => '9', 2 => '8', default => '7' },
            'breakdown' => [
                'easy' => $easy_correct, 
                'medium' => $medium_correct, 
                'hard' => $hard_correct
            ]
        ];

        // ✅ REDIRECT KE HALAMAN RESULT DENGAN FLASH DATA
        return redirect()->route('placement.result')->with($viewData);
    }
}