<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use Carbon\Carbon;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        Article::truncate();

        Article::insert([
            [
                'title' => 'لماذا تعلم الطباعة مهم في القرن 21؟',
                'summary' => 'في عصر التكنولوجيا، السرعة في الكتابة تعني إنتاجية أعلى وفرص عمل أفضل.',
                'content' => 'الطباعة باللمس أصبحت مهارة أساسية لكل من يستخدم الحاسب... تعلمها يمنحك كفاءة وراحة أكبر في أداء المهام اليومية.',
                'author_name' => 'فريق الخوارزمي',
                'published_at' => Carbon::now()->subDays(3),
            ],
            [
                'title' => '10 نصائح لتحسين سرعة الكتابة',
                'summary' => 'تعرف على أفضل الممارسات لتطوير مهارة الطباعة.',
                'content' => '1. اجلس بشكل مريح. 2. استخدم جميع أصابعك. 3. تدرب بانتظام. 4. لا تنظر إلى لوحة المفاتيح...',
                'author_name' => 'مدرب ماهر',
                'published_at' => Carbon::now()->subDays(2),
            ],
            [
                'title' => 'الطباعة باللمس للأطفال',
                'summary' => 'ابدأ مبكرًا في تعليم الأطفال الطباعة بطريقة ممتعة وتفاعلية.',
                'content' => 'يساعد تدريب الأطفال على الطباعة منذ الصغر في تطوير مهارات التركيز والتنسيق بين العين واليد...',
                'author_name' => 'م. تغريد العبدالله',
                'published_at' => Carbon::now()->subDay(),
            ],
        ]);
    }
}
