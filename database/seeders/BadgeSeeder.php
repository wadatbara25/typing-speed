<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgeSeeder extends Seeder
{
    public function run(): void
    {
        Badge::truncate();

        Badge::insert([
            ['name' => 'المبتدئ الذهبي', 'description' => 'أنجز أول درس في الطباعة', 'icon' => '🥇', 'criteria' => 'first_lesson'],
            ['name' => 'سريع كالصقر', 'description' => 'حقق أكثر من 60 WPM', 'icon' => '⚡', 'criteria' => 'speed_60'],
            ['name' => 'دقيق كالقلم', 'description' => 'دقة تفوق 95%', 'icon' => '🖋️', 'criteria' => 'accuracy_95'],
            ['name' => 'محترف الطباعة', 'description' => 'أكمل جميع الدروس بنجاح', 'icon' => '🏆', 'criteria' => 'all_lessons'],
        ]);
    }
}
