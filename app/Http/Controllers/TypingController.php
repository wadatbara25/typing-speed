<?php

namespace App\Http\Controllers;

use App\Models\{Lesson, Attempt};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TypingController extends Controller
{
    public function show(Lesson $lesson)
    {
        return view('typing.show', compact('lesson'));
    }

    public function storeAttempt(Request $request)
    {
        $validated = $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'wpm' => 'required|numeric|min:0',
            'accuracy' => 'required|numeric|min:0|max:100',
            'duration_seconds' => 'nullable|numeric|min:0',
            'typed_text' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['started_at'] = now()->subSeconds($validated['duration_seconds'] ?? 0);
        $validated['finished_at'] = now();

        Attempt::create($validated);

        return response()->json(['status' => 'success']);
    }

    public function stats()
    {
        $user = Auth::user();
        $attempts = $user->attempts()->with('lesson')->latest()->get();
        return response()->json($attempts);
    }
}
