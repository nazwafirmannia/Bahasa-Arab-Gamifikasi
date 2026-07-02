<?php
namespace App\Services;

use App\Models\User;
use App\Models\UserBadge;
use App\Models\Badge;
use Illuminate\Support\Facades\DB;

class BadgeChecker
{
    // app/Services/BadgeChecker.php

public static function checkAndAward(User $user): array
{
    $newlyEarned = []; // ✅ Tampung badge baru
    $badges = Badge::where('is_active', true)->get();
    
    foreach ($badges as $badge) {
        // Skip jika sudah dimiliki
        if (
            UserBadge::where('id_user', $user->id_user)
                ->where('id_badge', $badge->id_badge)
                ->exists()
        ) {
            continue;
        }
        
        $criteria = $badge->criteria;
        
        if (self::evaluate($user, $criteria)) {
            DB::transaction(function() use ($user, $badge, &$newlyEarned) {
                // Award badge
                \App\Models\UserBadge::create([
                    'id_user' => $user->id_user,
                    'id_badge' => $badge->id_badge,
                    'obtained_at' => now()
                ]);
                
                // Berikan bonus
                if ($badge->xp_bonus > 0) $user->stat->addXp($badge->xp_bonus);
                //if ($badge->coin_bonus > 0) $user->stat->addCoin($badge->coin_bonus);
            });
            
            $newlyEarned[] = $badge; // ✅ Tambahkan ke array return
        }
    }
    
    return $newlyEarned; // ✅ Return badge baru
}
    
private static function evaluate(User $user, array $criteria): bool
{
    return match($criteria['type'] ?? null) {

        // ==========================
        // BADGE MATERI
        // ==========================
        'materials_completed' =>
            $user->materialProgress()
                ->where('is_selesai', true)
                ->count() >= ($criteria['count'] ?? 999),

        // ==========================
        // BADGE LATIHAN
        // ==========================
        'practice_completed' =>
            $user->materialProgress()
                ->where('practice_completed', true)
                ->count() >= ($criteria['count'] ?? 999),

        // ==========================
        // BADGE QUIZ
        // ==========================
        'quiz_completed' =>
            \App\Models\RiwayatKuis::where('id_user', $user->id_user)
                ->where('status', 'lulus')
                ->count() >= ($criteria['count'] ?? 999),

        // ==========================
        // BADGE XP
        // ==========================
        'xp_reached' =>
            $user->stat->xp_total >= ($criteria['min_xp'] ?? 999999),

        // ==========================
        // BADGE STREAK
        // ==========================
        'streak_days' =>
            $user->stat->streak >= ($criteria['days'] ?? 999),

        default => false,
    };
}
}