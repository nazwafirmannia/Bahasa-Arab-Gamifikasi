<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'question';
    protected $primaryKey = 'id_question';
    
    protected $fillable = [
        'id_material',
        'question_text',
        'question_type',
        'option_a',
        'option_b', 
        'option_c',
        'option_d',
        'correct_answer',
        'explanation',
        'difficulty'
    ];

    public $timestamps = false;

    public function material()
    {
        return $this->belongsTo(Material::class, 'id_material', 'id_material');
    }

    // Helper: Cek apakah jawaban user benar
    public function isCorrect(string $userAnswer): bool
    {
        return strtolower(trim($userAnswer)) === strtolower(trim($this->correct_answer));
    }

    // Helper: Get options as array (untuk frontend)
    public function getOptionsArray(): array
    {
        return array_filter([
            'a' => $this->option_a,
            'b' => $this->option_b,
            'c' => $this->option_c,
            'd' => $this->option_d,
        ]);
    }
}