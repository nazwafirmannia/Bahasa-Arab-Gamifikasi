<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCharacter extends Model
{
    protected $table = 'user_character';
    
    protected $primaryKey = null; // atau 'id' jika ada kolom id
    public $incrementing = false;
    
    public $timestamps = false;
    
    protected $fillable = [
        'id_user',
        'id_character',
        'obtained_at'
    ];
    
    protected $casts = [
        'obtained_at' => 'datetime'
    ];
    
    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
    
    // Relasi ke Character
    public function character()
    {
        return $this->belongsTo(Character::class, 'id_character', 'id_character');
    }
}