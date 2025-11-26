<?php

namespace App\Http\Controllers;

use App\Models\Lesson;

class LessonController extends Controller
{
    /** Display list of lessons ordered by level */
    public function index()
    {
        $lessons = Lesson::query()
            ->select(['id', 'title', 'content', 'level', 'duration_limit'])
            ->orderByRaw('CAST(level AS UNSIGNED) ASC')
            ->paginate(9); // Show 9 lessons per page with pagination

        return view('lessons.index', compact('lessons'));
    }

    /** Show single lesson details */
    public function show(Lesson $lesson)
    {
        return view('lessons.show', compact('lesson'));
    }
}
