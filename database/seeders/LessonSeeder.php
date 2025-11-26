<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Lesson;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        Lesson::truncate();

        $lessons = [
            [
                'title' => 'صف الارتكاز - البداية',
                'content' => 'ك م ن ت ش س ي ب ... تدرب على صف الارتكاز ببطء ودقة.',
                'level' => 'مبتدئ',
                'duration_limit' => 60,
            ],
            [
                'title' => 'الحروف العربية - التدريب 1',
                'content' => 'أ ب ت ث ج ح خ د ذ ر ز س ش ص ض ط ظ ... حاول الطباعة بسرعة متزايدة.',
                'level' => 'متوسط',
                'duration_limit' => 90,
            ],
            [
                'title' => 'الجمل العربية',
                'content' => 'العلم نور، والعمل عبادة، والمثابرة طريق النجاح.',
                'level' => 'متقدم',
                'duration_limit' => 120,
            ],
            [
                'title' => 'اختبار السرعة النهائي',
                'content' => 'ابدأ الآن في اختبار مهاراتك الحقيقية في الطباعة السريعة والدقة العالية.',
                'level' => 'محترف',
                'duration_limit' => 180,
            ],
        ];

        Lesson::insert($lessons);
    }
}
