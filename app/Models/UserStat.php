<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserStat extends Model
{
    protected $table = 'user_stat';
    protected $primaryKey = 'id_user_stat';
    
    protected $fillable = [
        'id_user',
        'xp_total',
        'streak',
        'current_stage_id',
        'last_activity',
        'reminder_sent_at',
        'current_level'
    ];

    protected $casts = [
        'xp_total' => 'integer',
        'streak' => 'integer',
        'last_activity' => 'datetime',
        'reminder_sent_at' => 'datetime',
        'current_level' => 'integer',
    ];

    public $timestamps = false;

    // ===== RELASI =====
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class, 'current_stage_id', 'id_stage');
    }
    
    public function currentStage()
    {
        return $this->belongsTo(Stage::class, 'current_stage_id', 'id_stage');
    }
    
    // ===== HELPER METHODS =====
    public function addXp(int $amount): void
    {
        $this->increment('xp_total', $amount);
    
        // Sinkronkan object dengan database
        $this->refresh();
    }

// ===== STREAK LOGIC (OPTIMIZED) =====
public function updateStreak(): array
{
    $result = [
        'updated' => false,
        'bonus' => null,
    ];

    $this->refresh();

    $last = $this->last_activity;

    // ==========================
    // Aktivitas pertama
    // ==========================
    if (is_null($last)) {

        $this->streak = 1;
        $this->last_activity = now();
        $this->reminder_sent_at = null;
        $this->save();

        $result['updated'] = true;

    } else {

        $days = $last->copy()->startOfDay()->diffInDays(now()->copy()->startOfDay());

        // ==========================
        // Hari yang sama
        // ==========================
        if ($days == 0) {

            // Jika hari ini belum pernah mendapat streak
            if ($this->streak == 0) {
                $this->streak = 1;
                $result['updated'] = true;
            }

            $this->last_activity = now();
            $this->reminder_sent_at = null;
            $this->save();

            return $result;
        }

        // ==========================
        // Besoknya
        // ==========================
        if ($days == 1) {

            if ($this->streak <= 0) {
                $this->streak = 1;
            } else {
                $this->streak++;
            }

            $result['updated'] = true;
        }

        // ==========================
        // Putus streak
        // ==========================
        if ($days >= 2) {

            $this->streak = 1;
            $result['updated'] = true;
        }

        $this->last_activity = now();
        $this->reminder_sent_at = null;
        $this->save();
    }

    // ==========================
    // Bonus Milestone
    // ==========================
    $milestones = [
        3  => ['xp' => 30, 'msg' => '🔥 Streak 3 Hari!'],
        7  => ['xp' => 100, 'msg' => '🔥 Streak 1 Minggu!'],
        14 => ['xp' => 200, 'msg' => '🔥 Streak 2 Minggu!'],
        30 => ['xp' => 500, 'msg' => '🔥 Streak 1 Bulan!'],
    ];

    if (isset($milestones[$this->streak])) {

        $bonus = $milestones[$this->streak];

        $this->addXp($bonus['xp']);

        if ($this->user) {

            \App\Models\XpLog::create([
                'id_user' => $this->user->id_user,
                'amount' => $bonus['xp'],
                'source' => 'streak_milestone',
                'reference_id' => null,
            ]);
        }

        $result['bonus'] = [
            'msg' => $bonus['msg'],
            'xp'  => $bonus['xp'],
        ];
    }

    return $result;
}
}