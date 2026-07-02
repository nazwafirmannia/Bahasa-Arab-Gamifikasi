<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quizze;
use App\Models\Material;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $material = Material::firstOrFail();

        if (!$material) {
            throw new \Exception('Material tidak ditemukan. Jalankan MaterialSeeder dulu.');
        }

        Quizze::updateOrCreate(
            ['title' => 'Quiz Gamifikasi Dasar'],
            [
                'material_id' => $material->id,
                'quiz_type' => 'multiple_choice',
            ]
        );
    }
}
