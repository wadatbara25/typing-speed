<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use App\Models\Article;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    /** Show home page with top users and latest articles */
    public function home()
    {
        // Top 5 fastest users
        $topUsers = Attempt::select('user_id',
                DB::raw('MAX(wpm) as best_speed'),
                DB::raw('MAX(accuracy) as best_accuracy'))
            ->groupBy('user_id')
            ->orderByDesc('best_speed')
            ->with('user:id,name')
            ->take(5)
            ->get()
            ->map(fn($a) => (object)[
                'name' => $a->user->name ?? 'User',
                'best_speed' => $a->best_speed,
                'best_accuracy' => $a->best_accuracy
            ]);

        // Latest 3 articles
        $articles = Article::latest()->take(3)->get();

        return view('welcome', compact('topUsers', 'articles'));
    }

    /** Show list of all articles */
    public function articles()
    {
        $articles = Article::latest()->paginate(6);
        return view('articles.index', compact('articles'));
    }

    /** Show single article */
    public function showArticle($id)
    {
        $article = Article::findOrFail($id);
        return view('articles.show', compact('article'));
    }
}
