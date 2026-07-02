<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = 'quiz';
    protected $primaryKey = 'id_quiz';
    
    protected $fillable = [
        'id_level',
        'title',
        'quiz_format',
        'passing_score',
        'time_limit_sec',
        'max_attempts',
        'status'
    ];

    protected $casts = [
        'passing_score' => 'integer',
        'time_limit_sec' => 'integer',
        'max_attempts' => 'integer',
    ];

    public $timestamps = false;

    public function level()
    {
        return $this->belongsTo(Level::class, 'id_level', 'id_level');
    }

    public function items()
    {
        return $this->hasMany(QuizItem::class, 'id_quiz', 'id_quiz')
                    ->orderBy('order_index');
    }

    // ✅ FIX: Pakai RiwayatKuis (bukan QuizAttempt)
    public function attempts()
    {
        return $this->hasMany(RiwayatKuis::class, 'id_quiz', 'id_quiz')
                    ->orderBy('attempt_number', 'desc');
    }

// Di dalam class Quiz
public function isGameFormat()
{
    // Return true jika quiz mengandung flashcard atau tipe game lain
    return $this->items->contains(function($item) {
        return in_array($item->item_type, ['flashcard', 'memory', 'drag_drop']);
    });
}
}