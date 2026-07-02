<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class PlacementResult extends Model
{
    protected $table = 'placement_result';
    protected $primaryKey = 'id_user';
    public $incrementing = false;
    
    protected $fillable = ['id_user', 'score', 'assigned_stage_id', 'taken_at'];
    
    protected $casts = [
        'score' => 'integer',
        'taken_at' => 'datetime',
    ];
    
    public $timestamps = false;
    const CREATED_AT = 'taken_at';

    public function user() { return $this->belongsTo(User::class, 'id_user', 'id_user'); }
    public function stage() { return $this->belongsTo(Stage::class, 'assigned_stage_id', 'id_stage'); }
}