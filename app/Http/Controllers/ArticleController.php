<?php

namespace App\Http\Controllers;

use App\Models\{Article, User};

class ArticleController extends Controller
{
    /** Show homepage with latest articles and top users */
    public function welcome()
    {
        $articles = Article::latest()->take(3)->get();

        // Get top 5 users by average WPM
        $topUsers = User::whereHas('attempts')
            ->withAvg('attempts', 'wpm')
            ->orderByDesc('attempts_avg_wpm')
            ->take(5)
            ->get()
            ->map(function ($user) {
                $user->average_wpm = round($user->attempts_avg_wpm ?? 0, 1);
                return $user;
            });

        return view('welcome', compact('articles', 'topUsers'));
    }

    /** Show a single article */
    public function show($id)
    {
        $article = Article::findOrFail($id);
        return view('articles.show', compact('article'));
    }
}
