<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    protected $table = 'stage';
    protected $primaryKey = 'id_stage';
    
    protected $fillable = [
        'stage_name',
        'urutan',
        'xp_multiplier',
        'coin_multiplier',
        'base_xp_material',
        'base_coin_material'
    ];

    protected $casts = [
        'xp_multiplier' => 'decimal:2',
        'coin_multiplier' => 'decimal:2',
        'urutan' => 'integer',
    ];

    public $timestamps = false;

    public function levels()
    {
        return $this->hasMany(Level::class, 'id_stage', 'id_stage')
                    ->orderBy('level_order');
    }

    public function users()
    {
        return $this->hasMany(UserStat::class, 'current_stage_id', 'id_stage');
    }
}