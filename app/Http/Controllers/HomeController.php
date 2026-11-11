<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('pages.home', [
            'articles' => Article::published()
                ->latest('published_at')
                ->limit(6)
                ->get(),
        ]);
    }
}
