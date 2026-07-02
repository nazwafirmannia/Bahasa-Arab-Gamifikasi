<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Character;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @property int $id_user
 * @property string $name_user
 * @property string $email_user
 * @property string $role
 * @property bool $has_taken_placement
 * @property-read \App\Models\UserStat|null $stat
 * @method bool save(array $options = [])
 */

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'id_user';
    
    protected $fillable = [
        'name_user',
        'email_user', 
        'password_user',
        'role',
        'has_taken_placement'
    ];

    protected $hidden = ['password_user'];
    
    protected $casts = [
        'password_user' => 'hashed',
        'has_taken_placement' => 'boolean',
    ];

    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // ✅ Method untuk auth login
    public function username()
    {
        return 'email_user';
    }

    public function getAuthPassword()
    {
        return $this->password_user;
    }

    public function getRoleAttribute($value)
    {
        return trim(strtolower($value));
    }

    // ⚠️ Admin tidak punya stat, jadi relasi optional
    public function stat()
    {
        return $this->hasOne(UserStat::class, 'id_user', 'id_user');
    }

    public function characters()
    {
        return $this->belongsToMany(Character::class, 'user_character', 'id_user', 'id_character')
                    ->withPivot('obtained_at');
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badge', 'id_user', 'id_badge')
                    ->withPivot('obtained_at');
    }

    public function materialProgress()
    {
        return $this->hasMany(UserMaterialProgress::class, 'id_user', 'id_user');
    }

    public function quizHistory()
    {
        return $this->hasMany(RiwayatKuis::class, 'id_user', 'id_user');
    }

    public function quizAttempts()
    {
        return $this->hasMany(RiwayatKuis::class, 'id_user', 'id_user');
    }
    
    public function xpLogs()
    {
        return $this->hasMany(XpLog::class, 'id_user', 'id_user');
    }

    //public function coinUsages()
    //{//
        //return $this->hasMany(CoinUsage::class, 'id_user', 'id_user');//
   // }//

    public function placementResult()
    {
        return $this->hasOne(PlacementResult::class, 'id_user', 'id_user');
    }

    // Helper: Cek apakah user adalah admin
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class, 'id_user', 'id_user')
                    ->orderBy('created_at', 'desc');
    }
    
    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }
    
    public function getUnreadCountAttribute()
    {
        return $this->unreadNotifications()->count();
    }

    // app/Models/User.php

//public function profileCharacter()
//{
    //return $this->belongsTo(Character::class, 'profile_character_id');
//}

//public function equippedCharacter()
//{//
    //return $this->belongsToMany(Character::class, 'user_character', 'id_user', 'id_character')//
                //->wherePivot('is_equipped', true)//
                //->first();//
//}//

// Helper: Get avatar untuk profil (prioritas: profile_character > equipped_character > default)
public function getProfileAvatarAttribute()
{
    $avatar = $this->currentAvatar;

    if ($avatar) {
        return asset('storage/'.$avatar->image);
    }

    return 'https://ui-avatars.com/api/?name=' .
           urlencode($this->name_user);
}

public function getCurrentAvatarAttribute()
{
    $level = $this->stat?->current_level ?? 1;

    return Character::where('is_active', true)
        ->where('unlock_level', '<=', $level)
        ->orderByDesc('unlock_level')
        ->first();
}
}