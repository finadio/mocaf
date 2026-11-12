<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class JournalController extends Controller
{
    public function index(Request $request): Response
    {
        $categories = [
            'Mengenal MOCAF',
            'Inovasi Pangan Lokal',
            'Pengembangan Produk',
            'Aktivitas dan Dokumentasi',
            'Tips Penyajian',
        ];

        $query = Article::published();

        if ($request->filled('kategori')) {
            $query->where('category', $request->kategori);
        }

        $articles = $query->latest('published_at')->paginate(9)->withQueryString();

        return Inertia::render('Public/Journal/Index', [
            'articles' => $articles,
            'categories' => $categories,
            'currentCategory' => $request->kategori,
        ]);
    }

    public function show(string $slug): Response
    {
        $article = Article::published()->where('slug', $slug)->firstOrFail();

        $relatedArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->where('category', $article->category)
            ->latest('published_at')
            ->take(3)
            ->get();

        if ($relatedArticles->isEmpty()) {
            $relatedArticles = Article::published()
                ->where('id', '!=', $article->id)
                ->latest('published_at')
                ->take(3)
                ->get();
        }

        return Inertia::render('Public/Journal/Show', [
            'article' => $article,
            'relatedArticles' => $relatedArticles,
        ]);
    }
}

