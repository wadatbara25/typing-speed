<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Level;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        Level::truncate();

        Level::insert([
            ['name' => 'مبتدئ',  'min_wpm' => 0,  'max_wpm' => 30,  'badge_color' => '#10B981'],
            ['name' => 'متوسط',  'min_wpm' => 31, 'max_wpm' => 60, 'badge_color' => '#F59E0B'],
            ['name' => 'متقدم',  'min_wpm' => 61, 'max_wpm' => 90, 'badge_color' => '#3B82F6'],
            ['name' => 'محترف', 'min_wpm' => 91, 'max_wpm' => 999, 'badge_color' => '#EF4444'],
        ]);
    }
}
