<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('variant', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
            'categories' => $categories,
            'filters' => [
                'search' => $request->search,
                'category_id' => $request->category_id,
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Products/Create', [
            'categories' => Category::all(),
        ]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name'] . '-' . $validated['variant']);
        }

        // Upload foto utama
        if ($request->hasFile('main_image')) {
            $file = $request->file('main_image');
            $filename = 'prod_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/uploads/products'), $filename);
            $validated['main_image'] = '/assets/uploads/products/' . $filename;
        }

        // Upload galeri foto
        $galleryPaths = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $filename = 'gal_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('assets/uploads/products'), $filename);
                $galleryPaths[] = '/assets/uploads/products/' . $filename;
            }
        }
        $validated['gallery'] = $galleryPaths;
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product): Response
    {
        return Inertia::render('Admin/Products/Edit', [
            'product' => $product->load('category'),
            'categories' => Category::all(),
        ]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name'] . '-' . $validated['variant']);
        }

        // Upload foto utama baru jika ada
        if ($request->hasFile('main_image')) {
            $file = $request->file('main_image');
            $filename = 'prod_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/uploads/products'), $filename);
            $validated['main_image'] = '/assets/uploads/products/' . $filename;
        } else {
            // Keep existing image if not replacing with file
            $validated['main_image'] = $product->main_image;
        }

        // Galeri foto
        $gallery = $product->gallery ?? [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $filename = 'gal_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('assets/uploads/products'), $filename);
                $gallery[] = '/assets/uploads/products/' . $filename;
            }
        }
        $validated['gallery'] = $gallery;
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}

