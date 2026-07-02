<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatKuis extends Model
{
    protected $table = 'riwayat_kuis';
    protected $primaryKey = 'id_quiz_result';
    
    protected $fillable = [
        'id_user',
        'id_quiz',
        'score',
        'status', // 'lulus' atau 'gagal'
        'attempt_number', // angka percobaan ke berapa
        'taken_at',
    ];
    
    protected $casts = [
        'score' => 'integer',
        'attempt_number' => 'integer',
        'taken_at' => 'datetime',
    ];
    
    public $timestamps = false;
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
    
    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'id_quiz', 'id_quiz');
    }
}