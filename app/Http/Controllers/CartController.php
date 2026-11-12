<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function index(Request $request): Response
    {
        $cart = $request->session()->get('cart', []);
        $items = array_values($cart);

        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        return Inertia::render('Public/Cart/Index', [
            'cartItems' => $items,
            'subtotal' => $subtotal,
            'total' => $subtotal,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1|max:99',
        ]);

        $product = Product::where('id', $validated['product_id'])
            ->where('is_active', true)
            ->firstOrFail();

        $quantity = (int) ($validated['quantity'] ?? 1);
        $cart = $request->session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
            $cart[$product->id]['subtotal'] = $cart[$product->id]['quantity'] * $product->price;
        } else {
            $cart[$product->id] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'variant' => $product->variant,
                'price' => (float) $product->price,
                'weight' => (int) $product->weight,
                'main_image' => $product->main_image,
                'quantity' => $quantity,
                'subtotal' => (float) ($product->price * $quantity),
            ];
        }

        $request->session()->put('cart', $cart);

        return back()->with('success', "{$product->name} berhasil ditambahkan ke keranjang!");
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0|max:99',
        ]);

        $cart = $request->session()->get('cart', []);

        if (isset($cart[$id])) {
            if ($validated['quantity'] <= 0) {
                unset($cart[$id]);
                $message = 'Produk dihapus dari keranjang.';
            } else {
                $cart[$id]['quantity'] = $validated['quantity'];
                $cart[$id]['subtotal'] = $cart[$id]['quantity'] * $cart[$id]['price'];
                $message = 'Jumlah pesanan berhasil diperbarui.';
            }
            $request->session()->put('cart', $cart);
            return back()->with('success', $message);
        }

        return back()->with('error', 'Item tidak ditemukan di keranjang.');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            $request->session()->put('cart', $cart);
            return back()->with('success', 'Produk berhasil dihapus dari keranjang.');
        }

        return back()->with('error', 'Item tidak ditemukan di keranjang.');
    }
}

