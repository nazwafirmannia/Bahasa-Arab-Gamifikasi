<?php
namespace App\Services;

use App\Models\User;

class RewardCalculator
{
    public static function calculate(string $activity, bool $isCorrect, User $user): array
    {
        // Admin tidak dapat reward
        if ($user->role === 'admin') {
            return ['xp' => 0];
        }
        
        $stage = $user->stat->currentStage;
        
        $baseRewards = [
            'complete_material' => ['xp' => 30],
            'practice_correct' => ['xp' => 10],
            'practice_wrong' => ['xp' => 5],
            'quiz_pass' => ['xp' => 100],
            'quiz_fail' => ['xp' => 20],
        ];
        
        $base = $baseRewards[$activity] ?? ['xp' => 0];
        
        // Jika salah di practice, pakai reward wrong
        if ($activity === 'practice_correct' && !$isCorrect) {
            $base = $baseRewards['practice_wrong'];
        }
        
        // Apply multiplier
        return [
            'xp' => round($base['xp'] * $stage->xp_multiplier),
        ];
    }
}