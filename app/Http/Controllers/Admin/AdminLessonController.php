<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminLessonController extends Controller
{
    /** Show all lessons */
    public function index()
    {
        try {
            $lessons = Lesson::orderByDesc('id')->paginate(10);
            return view('admin.lessons.index', compact('lessons'));
        } catch (\Throwable $e) {
            Log::error('Error loading lessons: ' . $e->getMessage());
            return back()->with('error', 'Error loading lessons list.');
        }
    }

    /** Show create lesson form */
    public function create()
    {
        return view('admin.lessons.create');
    }

    /** Store a new lesson */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'content'        => 'required|string',
            'level'          => 'nullable|string|max:50',
            'duration_limit' => 'nullable|numeric|min:10',
        ]);

        try {
            Lesson::create($data);
            return redirect()
                ->route('admin.lessons.index')
                ->with('success', 'Lesson created successfully.');
        } catch (\Throwable $e) {
            Log::error('Error creating lesson: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Error saving lesson.');
        }
    }

    /** Show edit form */
    public function edit(Lesson $lesson)
    {
        return view('admin.lessons.edit', compact('lesson'));
    }

    /** Update existing lesson */
    public function update(Request $request, Lesson $lesson)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'content'        => 'required|string',
            'level'          => 'nullable|string|max:50',
            'duration_limit' => 'nullable|numeric|min:10',
        ]);

        try {
            $lesson->update($data);
            return redirect()
                ->route('admin.lessons.index')
                ->with('success', 'Lesson updated successfully.');
        } catch (\Throwable $e) {
            Log::error('Error updating lesson: ' . $e->getMessage());
            return back()->with('error', 'Error updating lesson.');
        }
    }

    /** Delete lesson */
    public function destroy(Lesson $lesson)
    {
        try {
            $lesson->delete();
            return back()->with('success', 'Lesson deleted successfully.');
        } catch (\Throwable $e) {
            Log::error('Error deleting lesson: ' . $e->getMessage());
            return back()->with('error', 'Error deleting lesson.');
        }
    }
}
