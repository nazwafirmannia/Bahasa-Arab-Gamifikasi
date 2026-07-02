<?php
namespace App\Http\Controllers\User;

use App\Models\UserMaterialProgress;
use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Material;
use App\Models\Question;
use App\Services\RewardCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LearningController extends Controller
{
// ✅ DASHBOARD USER (DENGAN CACHE)
public function dashboard()
{
    $user = Auth::user();

    $cacheKey = "user_dashboard_" . $user->id_user;

    Cache::forget($cacheKey);

    $result = Cache::remember($cacheKey, 600, function () use ($user) {

        $streakResult = [
            'updated' => false,
            'bonus' => null
        ];

        $stages = \App\Models\Stage::with([
            'levels' => function ($q) {
                $q->orderBy('level_order', 'asc');
            }
        ])->orderBy('urutan')->get();

        $currentStage = $user->stat->currentStage;

        foreach ($stages as $stage) {

            $totalMat = 0;
            $doneMat = 0;

            foreach ($stage->levels as $level) {

                $mats = $level->materials
                    ->pluck('id_material')
                    ->toArray();

                $totalMat += count($mats);

                $done = !empty($mats)
                    ? UserMaterialProgress::where('id_user', $user->id_user)
                        ->whereIn('id_material', $mats)
                        ->where('is_selesai', true)
                        ->count()
                    : 0;

                $doneMat += $done;

                $level->progress_percent =
                    count($mats)
                    ? round(($done / count($mats)) * 100)
                    : 0;
            }

            $stage->progress_percent =
                $totalMat
                ? round(($doneMat / $totalMat) * 100)
                : 0;
        }

        $currentCharacter = \App\Models\Character::where(
                'unlock_level',
                '<=',
                $user->stat->current_level ?? 1
            )
            ->where('is_active', true)
            ->orderByDesc('unlock_level')
            ->first();

        $todayMaterial = UserMaterialProgress::where(
                'id_user',
                $user->id_user
            )
            ->where('is_selesai', true)
            ->whereDate('completed_at', today())
            ->count();

        $todayPractice = UserMaterialProgress::where(
                'id_user',
                $user->id_user
            )
            ->where('practice_completed', true)
            ->whereDate('practice_completed_at', today())
            ->count();

        $todayXp = DB::table('xp_log')
            ->where('id_user', $user->id_user)
            ->whereDate('created_at', today())
            ->sum('amount');

        $ownedBadgeIds = $user->badges
            ->pluck('id_badge')
            ->toArray();

        $lockedBadges = \App\Models\Badge::whereNotIn(
            'id_badge',
            $ownedBadgeIds
        )->get();

        return compact(
            'stages',
            'currentStage',
            'currentCharacter',
            'streakResult',
            'todayMaterial',
            'todayPractice',
            'todayXp',
            'lockedBadges'
        );

    }); // <-- PENTING! callback ditutup di sini

    $viewData = $result;

    $viewData['streakBonus'] =
        $viewData['streakResult']['bonus'] ?? null;

    return view('user.dashboard', $viewData);
}

    // ✅ SHOW LEVEL
    public function showLevel($levelId)
    {
        $user = Auth::user();
        $level = Level::with(['materials.questions'])->findOrFail($levelId);
        
        if (
            $level->status_kunci
            && $user->role !== 'admin'
        ){
            return redirect()->route('user.dashboard')
                ->with('error', 'Level ini masih terkunci. Selesaikan level sebelumnya!');
        }
        
        // Hitung progress level ini
        $materialIds = $level->materials->pluck('id_material');
        $completed = UserMaterialProgress::where('id_user', $user->id_user)
            ->whereIn('id_material', $materialIds)
            ->where('is_selesai', true)
            ->count();
            
        $level->progress_percent = $level->materials->count() > 0 
            ? round(($completed / $level->materials->count()) * 100) 
            : 0;
            
        // Cek apakah quiz evaluasi sudah bisa diakses
        $level->can_take_quiz = $level->progress_percent == 100 && $level->quiz()->exists();
        
        return view('user.learning.level', compact('level'));
    }

    // ✅ SHOW MATERIAL
    public function showMaterial($materialId)
    {
        $material = Material::with('questions')->findOrFail($materialId);
        $level = $material->level;
        
        if (
            $level->status_kunci
            && Auth::user()->role !== 'admin'
        ) {
            abort(403, 'Akses ditolak');
        }
        
        // Cek progress
        $progress = UserMaterialProgress::where('id_user', Auth::id())
            ->where('id_material', $materialId)
            ->first();
            
        $isCompleted = $progress?->is_selesai ?? false;
        
        return view('user.learning.material', compact('material', 'level', 'isCompleted'));
    }

// ✅ COMPLETE MATERIAL (FIXED - NO BADGE CHECKER)
public function completeMaterial($materialId)
{
    $user = Auth::user();
    $material = Material::findOrFail($materialId);
    
    // Catat progress
    DB::table('user_material_progress')->updateOrInsert(
        ['id_user' => $user->id_user, 'id_material' => $materialId],
        ['is_selesai' => true, 'completed_at' => now()]
    );
    
    // ✅ HAPUS CACHE DASHBOARD
    Cache::forget("user_dashboard_" . $user->id_user);
    
    // Hitung reward
    $reward = RewardCalculator::calculate('complete_material', true, $user);
    
    try {
        DB::transaction(function() use ($user, $reward, $material) {
            $user->stat->addXp($reward['xp']);
            
            \App\Models\XpLog::create([
                'id_user' => $user->id_user,
                'amount' => $reward['xp'],
                'source' => 'material_complete',
                'reference_id' => $material->id_material
            ]);
            
            $user->stat->updateStreak();
        });
        
        // ✅ HAPUS BadgeChecker & NotificationService SEMENTARA
        $badges = \App\Services\BadgeChecker::checkAndAward($user);
        foreach($badges as $badge){
            \App\Services\NotificationService::badgeEarned(
                $user,
                $badge->name,
                $badge->icon
            );
        }

        $badgePopup = null;
        if(count($badges) > 0){
        
            $badgePopup = [
                'name' => $badges[0]->name,
                'icon' => $badges[0]->icon
            ];
        }
        
        $message = "Materi selesai! +{$reward['xp']} XP 🎉";
        
        return back()
            ->with('success', $message)
            ->with('badge_popup', $badgePopup);
        
    } catch (\Exception $e) {
        \Log::error('Error in completeMaterial: ' . $e->getMessage());
        return back()->with('error', 'Terjadi kesalahan. Coba lagi.');
    }
}

    // ✅ PRACTICE: Tampilkan halaman latihan soal (ONE-TIME ONLY)
    public function practice($materialId)
    {
        $user = Auth::user();
        $material = Material::with('questions')->findOrFail($materialId);
        
        // Cek progress materi
        $progress = UserMaterialProgress::where('id_user', $user->id_user)
            ->where('id_material', $materialId)
            ->first();
        
        // Jika materi belum selesai & bukan admin, redirect back
        if ((!$progress || !$progress->is_selesai) && $user->role !== 'admin') {
            return redirect()->route('user.material', $materialId)
                ->with('info', '📚 Selesaikan materi terlebih dahulu sebelum mengerjakan latihan!');
        }
        
        // Acak urutan soal (ambil 5 jika >5)
        $questions = $material->questions()->inRandomOrder()->take(5)->get();
        
        return view('user.learning.practice', compact('material', 'questions'));
    }

    public function submitPractice(Request $request, $materialId)
{
    $user = Auth::user();
    $material = Material::findOrFail($materialId);
    
    $validated = $request->validate([
        'answers' => 'required|array',
        'answers.*' => 'required|string'
    ]);
    
    // ✅ PAKAI firstOrCreate
    $progress = UserMaterialProgress::firstOrCreate(
        ['id_user' => $user->id_user, 'id_material' => $materialId],
        ['is_selesai' => false, 'practice_completed' => false]
    );
    
    // ✅ CEK apakah ini percobaan pertama (SEBELUM update)
    $isFirstAttempt = !$progress->practice_completed;
    
    $totalXp = 0;
    $correctCount = 0;
    $results = [];
    
    foreach ($validated['answers'] as $qId => $userAnswer) {
        $question = Question::find($qId);
        if (!$question) continue;
        
        $isCorrect = $question->isCorrect($userAnswer);
        
        if ($isCorrect) {
            $correctCount++;
            // ✅ HANYA hitung reward jika first attempt
            if ($isFirstAttempt) {
                $reward = RewardCalculator::calculate('practice_correct', true, $user);
                $totalXp += $reward['xp'];
            }
        }
        
        $results[] = [
            'question_id' => $qId,
            'question_text' => $question->question_text,
            'user_answer' => $userAnswer,
            'correct_answer' => $question->correct_answer,
            'is_correct' => $isCorrect,
            'explanation' => $question->explanation,
            'options' => [
                'a' => $question->option_a,
                'b' => $question->option_b,
                'c' => $question->option_c,
                'd' => $question->option_d,
            ]
        ];
    }
    
    $totalQuestions = count($validated['answers']);
    $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;
    
    // ✅ Update practice_completed menggunakan Query Builder
    DB::table('user_material_progress')
        ->where('id_user', $user->id_user)
        ->where('id_material', $materialId)
        ->update([
            'practice_completed' => true,
            'practice_completed_at' => now()
        ]);
    
    // ✅ Berikan reward HANYA jika first attempt
    if ($isFirstAttempt && ($totalXp > 0)) {
        if ($totalXp > 0) {
            $user->stat->addXp($totalXp);
            \App\Models\XpLog::create([
                'id_user' => $user->id_user,
                'amount' => $totalXp,
                'source' => 'practice_first_attempt',
                'reference_id' => $material->id_material
            ]);
        }
        $user->stat->updateStreak();
    }

    $badges = \App\Services\BadgeChecker::checkAndAward($user);

foreach($badges as $badge){
    \App\Services\NotificationService::badgeEarned(
        $user,
        $badge->name,
        $badge->icon
    );
}

$badgePopup = null;

if(count($badges) > 0){
    $badgePopup = [
        'name' => $badges[0]->name,
        'icon' => $badges[0]->icon
    ];
}

    session([
        'practice_result' => [
            'material_id' => $materialId,
            'material_title' => $material->title,
            'total_questions' => $totalQuestions,
            'correct_count' => $correctCount,
            'score' => $score,
            'xp_earned' => $isFirstAttempt ? $totalXp : 0,
            'is_review_mode' => !$isFirstAttempt,
            'results' => $results,
            'badge_earned' => null,
        ]
    ]);

    return redirect()->route('user.practice.result', $materialId);
}

    // ✅ SHOW PRACTICE RESULT
    public function showPracticeResult($materialId)
    {
        $material = Material::findOrFail($materialId);
        $result = session('practice_result');
        
        // Validasi: hasil harus ada dan sesuai material
        if (!$result || $result['material_id'] != $materialId) {
            return redirect()->route('user.material', $materialId);
        }
        
        // Ambil soal untuk review jawaban
        $questions = $material->questions->keyBy('id_question');
        
        return view('user.learning.practice-result', compact('material', 'result', 'questions'));
    }

    // ✅ BUY HINT
    public function buyHint($questionId)
    {
        $user = Auth::user();
        $question = Question::findOrFail($questionId);
        $hintCost = 10;
        
        if ($user->stat->coin_balance < $hintCost) {
            return response()->json(['success' => false, 'message' => 'Coin tidak cukup!'], 400);
        }
        
        DB::transaction(function() use ($user, $hintCost, $question) {
            
            \App\Models\CoinUsage::create([
                'id_user' => $user->id_user,
                'amount' => $hintCost,
                'type' => 'buy_hint',
                'reference_id' => $question->id_question
            ]);
        });
        
        return response()->json([
            'success' => true,
            'message' => 'Hint berhasil dibeli!',
            'explanation' => $question->explanation,
            'remaining_coin' => $user->stat->coin_balance
        ]);
    }

// ✅ NEXT MATERIAL: Redirect ke materi berikutnya
public function nextMaterial($materialId)
{
    $material = Material::findOrFail($materialId);
    $level = $material->level;
    
    // ✅ VALIDASI: Pastikan urutan tidak null
    if ($material->urutan === null) {
        return redirect()->route('user.level', $level->id_level)
            ->with('warning', '⚠️ Materi tidak memiliki urutan. Kembali ke level.');
    }
    
    // Cari materi berikutnya dalam level yang sama
    $nextMaterial = Material::where('id_level', $level->id_level)
        ->where('urutan', '>', $material->urutan)
        ->orderBy('urutan')
        ->first();
    
    if ($nextMaterial) {
        return redirect()->route('user.material', $nextMaterial->id_material);
    } else {
        return redirect()->route('user.level', $level->id_level)
            ->with('success', '🎉 Level selesai! Lanjutkan ke quiz evaluasi atau stage berikutnya.');
    }
}

    // =====================================================
    // ✅ QUIZ LEVEL METHODS
    // =====================================================

    public function showLevelQuiz($levelId)
    {
        $user = Auth::user();
        $level = Level::with('materials')->findOrFail($levelId);
        
        $materialIds = $level->materials->pluck('id_material');
        $completedCount = UserMaterialProgress::where('id_user', $user->id_user)
            ->whereIn('id_material', $materialIds)
            ->where('is_selesai', true)
            ->count();
            
        if ($completedCount < $level->materials->count()) {
            return redirect()->route('user.level', $level->id_level)
                ->with('error', 'Selesaikan semua materi dulu sebelum mengerjakan quiz evaluasi.');
        }
        
        $quiz = $level->quiz()->where('status', 'active')->first();
        
        if (!$quiz) {
            return redirect()->route('user.level', $level->id_level)
                ->with('error', 'Quiz evaluasi belum tersedia untuk level ini.');
        }
        
        $lastAttempt = \App\Models\RiwayatKuis::where('id_user', $user->id_user)
            ->where('id_quiz', $quiz->id_quiz)
            ->orderBy('attempt_number', 'desc')
            ->first();
            
        if ($lastAttempt && $lastAttempt->status === 'lulus') {
            return redirect()->route('user.level.result', [
                'levelId' => $level->id_level, 
                'quizId' => $quiz->id_quiz
            ]);
        }
        
        $questions = $quiz->items()->inRandomOrder()->take(10)->get();

        return view('user.learning.quiz', compact('level', 'quiz', 'questions', 'lastAttempt'));
    }

    public function submitLevelQuiz(Request $request, $levelId, $quizId)
    {
        // ✅ TIMEOUT PREVENTION
        set_time_limit(30); // Max 30 detik
        
        $user = Auth::user();
        $quiz = \App\Models\Quiz::with('level')->findOrFail($quizId);
        
        // ✅ VALIDASI: Cek apakah sudah lulus sebelumnya
        $lastAttempt = \App\Models\RiwayatKuis::where('id_user', $user->id_user)
            ->where('id_quiz', $quizId)
            ->orderBy('attempt_number', 'desc')
            ->first();
            
        if ($lastAttempt && $lastAttempt->status === 'lulus') {
            return redirect()->route('user.level.result', [
                'levelId' => $levelId, 
                'quizId' => $quizId
            ])->with('info', 'Kamu sudah lulus quiz ini.');
        }
        
        // ✅ VALIDASI INPUT
        $answers = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required'
        ]);
        
        // ✅ HITUNG SKOR (SIMPEL, TIDAK BERAT)
        $correct = 0;
        $total = count($answers['answers']);
        
        foreach ($answers['answers'] as $qId => $userAnswer) {
            $item = \App\Models\QuizItem::find($qId);
            if ($item && $item->isCorrect($userAnswer)) {
                $correct++;
            }
        }
        
        $score = $total > 0 ? round(($correct / $total) * 100) : 0;
        $status = $score >= $quiz->passing_score ? 'lulus' : 'gagal';
        $attemptNumber = ($lastAttempt?->attempt_number ?? 0) + 1;
        
        // ✅ SIMPAN HASIL (TRANSACTION KECIL)
        try {
            \App\Models\RiwayatKuis::create([
                'id_user' => $user->id_user,
                'id_quiz' => $quizId,
                'score' => $score,
                'status' => $status,
                'attempt_number' => $attemptNumber,
                'taken_at' => now()
            ]);
        } catch (\Exception $e) {
            \Log::error('Error saving quiz result: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan hasil quiz. Coba lagi.');
        }
        
        // ✅ JIKA LULUS: UNLOCK NEXT LEVEL + BONUS
        if ($status === 'lulus') {
            try {

                $currentLevel = $user->stat->current_level ?? 1;

                $user->stat->update([
                'current_level' => min($currentLevel + 1, 9)
                ]);

            // Buka level berikutnya dalam stage yang sama
            $nextLevel = \App\Models\Level::where('id_stage', $quiz->level->id_stage)
            ->where('level_order', '>', $quiz->level->level_order)
            ->orderBy('level_order')
            ->first();

            if ($nextLevel) {
                $nextLevel->update([
                    'status_kunci' => false
                ]);
            }
                // 2. Hitung bonus (DENGAN NULL CHECK!)
                $currentStage = $user->stat->currentStage;
                $xpMultiplier = $currentStage?->xp_multiplier ?? 1;
                $coinMultiplier = $currentStage?->coin_multiplier ?? 1;
                
                $bonusXp = 100 * $xpMultiplier;
                $bonusCoin = 50 * $coinMultiplier;
                
                // 3. Berikan reward
                DB::transaction(function() use ($user, $bonusXp, $bonusCoin, $quiz) {

                    $user->stat->addXp($bonusXp);
                    $user->stat->updateStreak();
                
                    \App\Models\XpLog::create([
                        'id_user' => $user->id_user,
                        'amount' => $bonusXp,
                        'source' => 'level_quiz_pass',
                        'reference_id' => $quiz->id_quiz
                    ]);
                });
                
                // 4. ✅ BadgeChecker & NotificationService SEMENTARA
                $badges = \App\Services\BadgeChecker::checkAndAward($user);
                foreach($badges as $badge){
                    \App\Services\NotificationService::badgeEarned(
                        $user,
                        $badge->name,
                        $badge->icon
                    );
                }

                $badgePopup = null;

                if(count($badges) > 0){
                    $badgePopup = [
                        'name' => $badges[0]->name,
                        'icon' => $badges[0]->icon
                    ];
                }

                $message = "🎉 LULUS! +{$bonusXp} XP";
                
                return redirect()->route('user.level.result', [
                    'levelId' => $levelId,
                    'quizId' => $quizId
                ])
                ->with('success', $message)
                ->with('badge_popup', $badgePopup);
                
            } catch (\Exception $e) {
                \Log::error('Error in quiz pass logic: ' . $e->getMessage());
                return redirect()->route('user.level.result', [
                    'levelId' => $levelId, 
                    'quizId' => $quizId
                ])->with('success', "🎉 LULUS! Skor: {$score}%");
            }
        }
        
        return redirect()->route('user.level.result', [
            'levelId' => $levelId,
            'quizId' => $quizId
        ])->with(
            'error',
            "Skor: {$score}%. Belum lulus. Pelajari kembali materi dan coba lagi."
        );
    }

    public function showLevelQuizResult($levelId, $quizId)
    {
        $user = Auth::user();
        $quiz = \App\Models\Quiz::with('level')->findOrFail($quizId);
        
        $result = \App\Models\RiwayatKuis::where('id_user', $user->id_user)
            ->where('id_quiz', $quizId)
            ->orderBy('attempt_number', 'desc')
            ->first();
            
        if (!$result) {
            return redirect()->route('user.level', $levelId);
        }
        
        return view('user.learning.quiz-result', compact('quiz', 'result', 'levelId'));
    }

/*
 * Helper: Update streak login harian (FIXED - NULL CHECK)
 
private function updateStreak($user)
{
    try {
        $last = $user->stat->last_activity;
        $today = now()->startOfDay();
        
        if (!$last || $last->startOfDay()->lt($today->subDay())) {
            $user->stat->update(['streak' => 0]);
        } elseif ($last->startOfDay()->lt($today)) {
            $user->stat->increment('streak');
            
            // ✅ NULL CHECK untuk currentStage
            if ($user->stat->streak % 7 === 0) {
                $currentStage = $user->stat->currentStage;
                $xpMultiplier = $currentStage?->xp_multiplier ?? 1;
                $coinMultiplier = $currentStage?->coin_multiplier ?? 1;
                
                $bonusXp = 50 * $xpMultiplier;
                $bonusCoin = 25 * $coinMultiplier;
                
                $user->stat->addXp($bonusXp);
                
                \App\Models\XpLog::create([
                    'id_user' => $user->id_user,
                    'amount' => $bonusXp,
                    'source' => 'streak_bonus',
                    'reference_id' => null
                ]);
            }
        }
        
        $user->stat->update(['last_activity' => now()]);
        
    } catch (\Exception $e) {
        \Log::error('Error in updateStreak: ' . $e->getMessage());
        // Silent fail - tidak perlu return error
    }
}
*/
}