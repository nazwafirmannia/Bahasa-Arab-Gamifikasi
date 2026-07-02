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
    }

// ===== STREAK LOGIC (OPTIMIZED) =====
public function updateStreak(): array
{
    $result = ['updated' => false, 'bonus' => null];
    $last = $this->last_activity;
    
// Aktivitas belajar pertama kali
if (!$last) {

    $this->update([
        'streak' => 1,
        'last_activity' => now(),
        'reminder_sent_at' => null
    ]);

    $result['updated'] = true;

    return $result;
}

// Streak putus jika tidak ada aktivitas belajar >48 jam
if ($last->diffInDays(now()) >= 2) {

    $this->update([
        'streak' => 1,
        'last_activity' => now(),
        'reminder_sent_at' => null
    ]);

    $result['updated'] = true;

    return $result;
}
    
// Tambah streak jika ada aktivitas belajar pada hari berbeda
if (!$last->isSameDay(now())) {

    $this->increment('streak');
    $this->refresh();

    $this->update([
        'last_activity' => now(),
        'reminder_sent_at' => null
    ]);

    $result['updated'] = true;

    // 🎁 Bonus Milestone
        $milestones = [
            3  => ['xp' => 30,  'msg' => '🔥 Streak 3 Hari!'],
            7  => ['xp' => 100, 'msg' => '🔥 Streak 1 Minggu!'],
            14 => ['xp' => 200, 'msg' => '🔥 Streak 2 Minggu!'],
            30 => ['xp' => 500, 'msg' => '🔥 Streak 1 Bulan!'],
        ];

    if (isset($milestones[$this->streak])) {

        $bonus = $milestones[$this->streak];

        $this->addXp($bonus['xp']);


        $user = $this->user;

        if ($user) {

            \App\Models\XpLog::create([
                'id_user' => $user->id_user,
                'amount' => $bonus['xp'],
                'source' => 'streak_milestone',
                'reference_id' => null
            ]);
        }

        $result['bonus'] = [
            'msg' => $bonus['msg'],
            'xp' => $bonus['xp'],
        ];
    }

}
else {

    $this->update([
        'last_activity' => now(),
        'reminder_sent_at' => null
    ]);

}

return $result;
}
}