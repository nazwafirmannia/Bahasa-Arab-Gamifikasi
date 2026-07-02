<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Level;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        $level = Level::firstOrFail();

        if (!$level) {
            throw new \Exception('Level tidak ditemukan. Jalankan LevelSeeder dulu.');
        }

        Material::updateOrCreate(
            ['title' => 'Pengenalan Gamifikasi'],
            [
                'level_id' => $level->id,
                'content_type' => 'text',
                'content_source' => null,
                'content_text' => 'Gamifikasi adalah penerapan elemen game dalam pembelajaran.',
            ]
        );

        Material::updateOrCreate(
            ['title' => 'Contoh Gamifikasi'],
            [
                'level_id' => $level->id,
                'content_type' => 'video',
                'content_source' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'content_text' => null,
            ]
        );
    }
}
