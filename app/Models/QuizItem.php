<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizItem extends Model
{
    protected $table = 'quiz_item';
    protected $primaryKey = 'id_item';
    
    protected $fillable = [
        'id_quiz',
        'item_type',
        'question_text',
        'option_a',
        'option_b',
        'option_c', 
        'option_d',
        'correct_answer',
        'game_data',
        'order_index'
    ];

    protected $casts = [
        'game_data' => 'array', // Auto convert JSON ↔ Array
        'order_index' => 'integer',
    ];

    public $timestamps = false;

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'id_quiz', 'id_quiz');
    }

    public function isCorrect(string $userAnswer): bool
    {
        return strtolower(trim($userAnswer))
            === strtolower(trim($this->correct_answer));
    }
    
    // Helper: Get game data safely
    public function getGameData(): array
    {
        return $this->game_data ?? [];
    }
}