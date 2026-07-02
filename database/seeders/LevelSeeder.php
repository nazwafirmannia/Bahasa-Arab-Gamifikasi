<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        Level::updateOrCreate(
            ['level_order' => 1],
            [
                'title' => 'Level 1 - Dasar',
            ]
        );
    }
}
