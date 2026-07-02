<?php
namespace App\Services;

use App\Models\User;
use App\Models\Notification;

class NotificationService
{
    /**
     * Create notification with auto-cleanup
     */
    public static function send(User $user, string $type, string $title, string $message, string $icon = '🔔', ?string $actionUrl = null, array $data = []): void
    {
        Notification::create([
            'id_user' => $user->id_user,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'action_url' => $actionUrl,
            'data' => $data,
        ]);
        
        // ✅ Auto-cleanup: Hapus notif lama (>14 hari) untuk user ini
        Notification::where('id_user', $user->id_user)
            ->where('created_at', '<', now()->subDays(14))
            ->delete();
    }
    
    /**
     * Trigger: Badge Earned
     */
    public static function badgeEarned(User $user, string $badgeName, string $badgeIcon): void
    {
        self::send(
            $user,
            'badge_earned',
            '🏅 Badge Baru!',
            "Kamu mendapat badge: **{$badgeName}** {$badgeIcon}",
            $badgeIcon,
            route('user.profile'),
            ['badge_name' => $badgeName, 'badge_icon' => $badgeIcon]
        );
    }
    
    /**
     * Trigger: Streak Milestone
     */
    public static function streakMilestone(User $user, int $streak, int $bonusXp, int $bonusCoin): void
    {
        self::send(
            $user,
            'streak_bonus',
            '🔥 Streak Bonus!',
            "Streak {$streak} hari! +{$bonusXp} XP, +{$bonusCoin} Coin 🎉",
            '🔥',
            route('user.dashboard'),
            ['streak' => $streak, 'bonus_xp' => $bonusXp, 'bonus_coin' => $bonusCoin]
        );
    }
    
    /**
     * Trigger: Quiz Passed
     */
    public static function quizPassed(User $user, string $quizTitle, int $score, int $bonusXp): void
    {
        self::send(
            $user,
            'quiz_passed',
            '🎉 Lulus Quiz!',
            "Kamu lulus **{$quizTitle}** dengan skor {$score}%! +{$bonusXp} XP",
            '🎯',
            route('user.level.result', ['levelId' => $user->stat->currentStage->id_stage, 'quizId' => 1]), // Sesuaikan
            ['quiz_title' => $quizTitle, 'score' => $score, 'bonus_xp' => $bonusXp]
        );
    }
    
    /**
     * Trigger: Level Unlocked
     */
    public static function levelUnlocked(User $user, string $levelName, int $stageOrder): void
    {
        self::send(
            $user,
            'level_unlocked',
            '🔓 Level Baru Terbuka!',
            "Level **{$levelName}** sudah bisa diakses! 🚀",
            '🗝️',
            route('user.level', 1), // Sesuaikan dengan level ID
            ['level_name' => $levelName, 'stage_order' => $stageOrder]
        );
    }
    
    /**
     * Trigger: Coin Low (opsional, untuk engagement)
     */
    public static function coinLow(User $user, int $currentCoin, int $threshold = 20): void
    {
        if ($currentCoin < $threshold) {
            self::send(
                $user,
                'coin_low',
                '💰 Coin Menipis!',
                "Coin kamu tinggal {$currentCoin}. Selesaikan materi untuk dapat lebih!",
                '🪙',
                route('user.dashboard'),
                ['current_coin' => $currentCoin, 'threshold' => $threshold]
            );
        }
    }
}