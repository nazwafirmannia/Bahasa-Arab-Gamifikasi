<?php // Character.php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    protected $table = 'character';
    protected $primaryKey = 'id_character';
    
    protected $fillable = [
            'name',
            'image',
            'description',
            'unlock_level',
            'is_active'
    ];
    
    protected $casts = [

        'is_active' => 'boolean',
    ];
    
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_character', 'id_character', 'id_user')
                    ->withPivot('obtained_at');
    }

}