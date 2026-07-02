<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizItem;
use App\Models\RiwayatKuis;
use App\Models\XpLog;
use App\Services\RewardCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class QuizController extends Controller
{
    public function show($quizId)
    {
        $user = Auth::user();
        
        // ✅ BUAT CACHE KEY UNTUK QUIZ DAN ATTEMPTS
        $quizCacheKey = "quiz_" . $quizId;
        $attemptsCacheKey = "quiz_attempts_" . $quizId . "_" . $user->id_user . "_" . today();
        
        // ✅ AMBIL QUIZ DARI CACHE
        $quiz = Cache::remember($quizCacheKey, 3600, function() use ($quizId) {
            return Quiz::with('items')->findOrFail($quizId);
        });
        
        // ✅ CEK AKSES LEVEL
        if ($quiz->level->status_kunci && $user->role !== 'admin') {
            abort(403, 'Level ini masih terkunci');
        }
        
        // ✅ AMBIL JUMLAH ATTEMPTS HARI INI DARI CACHE
        $todayAttempts = Cache::remember($attemptsCacheKey, 300, function() use ($user, $quizId) {
            return RiwayatKuis::where('id_user', $user->id_user)
                ->where('id_quiz', $quizId)
                ->whereDate('taken_at', today())
                ->count();
        });
        
        // ✅ CEK KUOTA ATTEMPTS
        if ($todayAttempts >= $quiz->max_attempts && $user->role !== 'admin') {
            return back()->with('error', 
                'Maksimal percobaan hari ini tercapai (' . $quiz->max_attempts . 'x). Coba lagi besok!');
        }
        
        // ✅ ACAK SOAL JIKA BUKAN GAME FORMAT
        if (!$quiz->isGameFormat()) {
            $quiz->items = $quiz->items->shuffle();
        }
        
        return view('user.quiz.play', compact('quiz'));
    }

    public function submit(Request $request, $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $user = Auth::user();
        $answers = $request->validate(['answers' => 'required|array']);
        
        $correct = 0;
        $total = $quiz->items->count();
        
        foreach ($quiz->items as $item) {
            $userAnswer = $answers['answers'][$item->id_item] ?? null;
            $gameType = $item->game_data['game_type'] ?? 'mcq';
            $isCorrect = false;
            
            if ($gameType === 'flashcard') {
                // Flashcard: benar jika user klik "Saya Tahu"
                $isCorrect = ($userAnswer === 'known');
            } 
            elseif ($gameType === 'fill') {
                // Fill: bandingkan jawaban (normalisasi harakat)
                $cleanUser = preg_replace('/[^\p{Arabic}\p{Latin}\p{Number}]/u', '', $userAnswer ?? '');
                $cleanCorrect = preg_replace('/[^\p{Arabic}\p{Latin}\p{Number}]/u', '', $item->correct_answer ?? '');
                $isCorrect = mb_strtolower($cleanUser) === mb_strtolower($cleanCorrect);
            } 
            else {
                // MCQ: bandingkan pilihan
                $isCorrect = strtolower($userAnswer ?? '') === strtolower($item->correct_answer ?? '');
            }
            
            if ($isCorrect) {
                $correct++;
            }
        }
        
        $score = $total > 0 ? round(($correct / $total) * 100) : 0;
        $status = $score >= $quiz->passing_score ? 'lulus' : 'gagal';
        $attemptNumber = RiwayatKuis::where('id_user', $user->id_user)
            ->where('id_quiz', $quizId)
            ->count() + 1;
        
        DB::transaction(function() use ($user, $quiz, $score, $status, $attemptNumber) {
            RiwayatKuis::create([
                'id_user' => $user->id_user,
                'id_quiz' => $quiz->id_quiz,
                'score' => $score,
                'status' => $status,
                'attempt_number' => $attemptNumber
            ]);
            
            $reward = RewardCalculator::calculate(
                $status === 'lulus' ? 'quiz_pass' : 'quiz_fail',
                true,
                $user
            );
            
            if ($reward['xp'] > 0 && $user->stat) $user->stat->addXp($reward['xp']);
            if ($reward['coin'] > 0 && $user->stat) $user->stat->addCoin($reward['coin']);
            
            XpLog::create([
                'id_user' => $user->id_user,
                'amount' => $reward['xp'],
                'source' => 'quiz_' . $status,
                'reference_id' => $quiz->id_quiz
            ]);
            
            if ($status === 'lulus') {
                $nextLevel = \App\Models\Level::where('id_stage', $quiz->level->id_stage)
                    ->where('level_order', '>', $quiz->level->level_order)
                    ->orderBy('level_order')
                    ->first();
                    
                if ($nextLevel) {
                    $nextLevel->update(['status_kunci' => false]);
                }
                
                if ($user->stat) {
                    $bonusXp = 50 * $user->stat->currentStage->xp_multiplier;
                    $bonusCoin = 25 * $user->stat->currentStage->coin_multiplier;
                    $user->stat->addXp($bonusXp);
                    $user->stat->addCoin($bonusCoin);
                    
                    XpLog::create([
                        'id_user' => $user->id_user,
                        'amount' => $bonusXp,
                        'source' => 'level_complete_bonus',
                        'reference_id' => $quiz->id_quiz
                    ]);
                }
            }
            
            // ✅ FIX: Panggil method dari UserStat model
            if ($user->stat) {
                $user->stat->updateStreak();
            }
        });
        
        \App\Services\BadgeChecker::checkAndAward($user);
        
        // ✅ HAPUS CACHE QUIZ DAN ATTEMPTS
        Cache::forget("quiz_" . $quizId);
        Cache::forget("quiz_attempts_" . $quizId . "_" . $user->id_user . "_" . today());
        
        return view('user.quiz.result', compact('quiz', 'score', 'status'));
    }
}