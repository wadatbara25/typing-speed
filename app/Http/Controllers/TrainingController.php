<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Attempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingController extends Controller
{
    /** Display the training page with available lessons */
    public function index()
    {
        $lessons = Lesson::orderBy('level')->get();
        return view('training.index', compact('lessons'));
    }

    /** Store a user's training attempt */
    public function storeAttempt(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'wpm' => 'required|numeric|min:0',
            'accuracy' => 'required|numeric|min:0|max:100',
        ]);

        Attempt::create([
            'user_id' => Auth::id(),
            'lesson_id' => $request->lesson_id,
            'wpm' => $request->wpm,
            'accuracy' => $request->accuracy,
        ]);

        return response()->json(['status' => 'success']);
    }
}
