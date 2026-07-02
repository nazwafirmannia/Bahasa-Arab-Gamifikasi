<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlacementQuestion extends Model
{
    protected $table = 'placement_question';
    protected $primaryKey = 'id_question';
    
    // Karena kita tidak pakai created_at/updated_at di tabel ini
    public $timestamps = false; 

    protected $fillable = [
        'question_text',
        'option_a', 'option_b', 'option_c', 'option_d',
        'correct_answer',
        'explanation',
        'difficulty',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Helper: Cek jawaban benar
    public function isCorrect(string $userAnswer): bool
    {
        return strtolower(trim($userAnswer)) === strtolower(trim($this->correct_answer));
    }
    
    // Helper: Ambil opsi sebagai array
    public function getOptionsArray(): array
    {
        return array_filter([
            'a' => $this->option_a,
            'b' => $this->option_b,
            'c' => $this->option_c,
            'd' => $this->option_d,
        ]);
    }

    public function getRouteKeyName()
    {
        return 'id_question';
    }
}