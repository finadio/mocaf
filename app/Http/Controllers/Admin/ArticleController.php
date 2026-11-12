<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreArticleRequest;
use App\Http\Requests\Admin\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ArticleController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Article::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $articles = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/Articles/Index', [
            'articles' => $articles,
            'filters' => [
                'search' => $request->search,
                'status' => $request->status,
            ],
        ]);
    }

    public function create(): Response
    {
        $categories = [
            'Mengenal MOCAF',
            'Inovasi Pangan Lokal',
            'Pengembangan Produk',
            'Aktivitas dan Dokumentasi',
            'Tips Penyajian',
        ];

        return Inertia::render('Admin/Articles/Create', [
            'categories' => $categories,
        ]);
    }

    public function store(StoreArticleRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = 'art_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/uploads/articles'), $filename);
            $validated['thumbnail'] = '/assets/uploads/articles/' . $filename;
        }

        if ($validated['status'] === 'published' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        Article::create($validated);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diterbitkan.');
    }

    public function edit(Article $article): Response
    {
        $categories = [
            'Mengenal MOCAF',
            'Inovasi Pangan Lokal',
            'Pengembangan Produk',
            'Aktivitas dan Dokumentasi',
            'Tips Penyajian',
        ];

        return Inertia::render('Admin/Articles/Edit', [
            'article' => $article,
            'categories' => $categories,
        ]);
    }

    public function update(UpdateArticleRequest $request, Article $article): RedirectResponse
    {
        $validated = $request->validated();

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = 'art_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/uploads/articles'), $filename);
            $validated['thumbnail'] = '/assets/uploads/articles/' . $filename;
        } else {
            $validated['thumbnail'] = $article->thumbnail;
        }

        if ($validated['status'] === 'published' && empty($article->published_at) && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $article->update($validated);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui.');
    }

    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dihapus.');
    }
}

