<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    protected $table = 'level';
    protected $primaryKey = 'id_level';
    
    protected $fillable = [
        'id_stage',
        'title_level',
        'level_order',
        'status_kunci'
    ];

    protected $casts = [
        'status_kunci' => 'boolean',
        'level_order' => 'integer',
    ];

    public $timestamps = false;

    public function stage()
    {
        return $this->belongsTo(Stage::class, 'id_stage', 'id_stage');
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'id_level', 'id_level')
                    ->orderBy('order');
    }

    public function quiz()
    {
        return $this->hasOne(Quiz::class, 'id_level', 'id_level')
                    ->where('status', 'active');
    }

    // Helper: Cek apakah level ini bisa diakses user
    public function isUnlocked(): bool
    {
        return !$this->status_kunci;
    }
}