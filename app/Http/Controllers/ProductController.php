<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $categories = Category::withCount(['products' => function ($q) {
            $q->where('is_active', true);
        }])->get();

        $query = Product::where('is_active', true)->with('category');

        if ($request->filled('kategori')) {
            $categorySlug = $request->kategori;
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        if ($request->filled('varian')) {
            $query->where('variant', $request->varian);
        }

        $products = $query->orderBy('is_featured', 'desc')->orderBy('id', 'asc')->get();

        return Inertia::render('Public/Products/Index', [
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $request->kategori,
            'currentVariant' => $request->varian,
        ]);
    }

    public function show(string $slug): Response
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with('category')
            ->firstOrFail();

        $relatedProducts = Product::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->with('category')
            ->take(3)
            ->get();

        return Inertia::render('Public/Products/Show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}

