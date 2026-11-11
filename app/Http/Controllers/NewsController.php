<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Contracts\View\View;

class NewsController extends Controller
{
    public function index(?string $category = null): View
    {
        $query = Article::published()->latest('published_at');

        if ($category !== null) {
            $query->where('category', $category);
        }

        return view('pages.latest', [
            'articles' => $query->paginate(12),
            'category' => $category,
        ]);
    }

    public function show(string $slug): View
    {
        $article = Article::published()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('pages.article', ['article' => $article]);
    }
}
