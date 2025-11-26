<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminArticleController extends Controller
{
    /** Allow access to admin users only */
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /** Show all articles */
    public function index()
    {
        $articles = Article::latest()->paginate(10);
        return view('admin.articles.index', compact('articles'));
    }

    /** Show the create article form */
    public function create()
    {
        return view('admin.articles.create');
    }

    /** Store a new article */
    public function store(Request $request)
    {
        // Validate input
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'summary'      => 'nullable|string|max:191',
            'content'      => 'required|string',
            'author_name'  => 'nullable|string|max:191',
            'status'       => 'required|in:draft,published',
            'image'        => 'nullable|image|max:2048',
        ], [
            'title.required'   => 'يرجى إدخال عنوان المقال.',
            'content.required' => 'يرجى كتابة محتوى المقال.',
        ]);

        // Upload image if exists
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        // Set publish date if published
        if ($data['status'] === 'published') {
            $data['published_at'] = now();
        }

        // Create article
        Article::create($data);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article created successfully.');
    }

    /** Show the edit article form */
    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    /** Update an existing article */
    public function update(Request $request, Article $article)
    {
        // Validate input
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'summary'      => 'nullable|string|max:191',
            'content'      => 'required|string',
            'author_name'  => 'nullable|string|max:191',
            'status'       => 'required|in:draft,published',
            'image'        => 'nullable|image|max:2048',
        ]);

        // Replace old image if a new one is uploaded
        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        // Update publish date if needed
        if ($data['status'] === 'published' && !$article->published_at) {
            $data['published_at'] = now();
        }

        // Update article
        $article->update($data);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article updated successfully.');
    }

    /** Delete article */
    public function destroy(Article $article)
    {
        if ($article->image) {
            Storage::disk('public')->delete($article->image);
        }

        $article->delete();

        return back()->with('success', 'Article deleted successfully.');
    }
}
