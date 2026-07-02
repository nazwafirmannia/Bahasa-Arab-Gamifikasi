<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id_notification';
    
    protected $fillable = [
        'id_user',
        'type',
        'title',
        'message',
        'icon',
        'action_url',
        'data',
        'read_at',
    ];
    
    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
    
    // Scope: Hanya yang belum dibaca
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }
    
    // Helper: Mark as read
    public function markAsRead(): void
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }
    
    // Helper: Get notification color by type
    public function getColorClass(): string
    {
        return match($this->type) {
            'badge_earned' => 'bg-amber-100 border-amber-300 text-amber-800',
            'streak_bonus' => 'bg-orange-100 border-orange-300 text-orange-800',
            'quiz_passed' => 'bg-emerald-100 border-emerald-300 text-emerald-800',
            'level_unlocked' => 'bg-blue-100 border-blue-300 text-blue-800',
            'coin_low' => 'bg-red-100 border-red-300 text-red-800',
            default => 'bg-gray-100 border-gray-300 text-gray-800',
        };
    }
}