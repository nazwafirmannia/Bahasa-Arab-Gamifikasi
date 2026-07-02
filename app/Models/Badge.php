<?php // Badge.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    protected $table = 'badge';
    protected $primaryKey = 'id_badge';
    
    protected $fillable = [
        'name', 'description', 'criteria', 'icon', 
        'xp_bonus', 'is_active'
    ];
    
    protected $casts = [
        'criteria' => 'array',
        'xp_bonus' => 'integer',
        'is_active' => 'boolean',
    ];
    
    public $timestamps = false;

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_badge', 'id_badge', 'id_user')
                    ->withPivot('obtained_at');
    }
}