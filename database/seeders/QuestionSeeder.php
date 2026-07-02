<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Question;
use App\Models\Quizze;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $quiz = Quizze::firstOrFail();

        if (!$quiz) {
            throw new \Exception('Quiz tidak ditemukan. Jalankan QuizSeeder dulu.');
        }

        Question::updateOrCreate(
            ['question' => 'Apa tujuan utama gamifikasi?'],
            [
                'quizzes_id' => $quiz->id,
                'option_a' => 'Menghibur saja',
                'option_b' => 'Meningkatkan motivasi belajar',
                'option_c' => 'Memperumit sistem',
                'option_d' => 'Mengurangi interaksi',
                'correct_answer' => 'B',
            ]
        );

        Question::updateOrCreate(
            ['question' => 'RP dalam sistem ini berfungsi untuk?'],
            [
                'quizzes_id' => $quiz->id,
                'option_a' => 'Menghapus data',
                'option_b' => 'Menambah level langsung',
                'option_c' => 'Menuju Skill Point',
                'option_d' => 'Login user',
                'correct_answer' => 'C',
            ]
        );
    }
}
