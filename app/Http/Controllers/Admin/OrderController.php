<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Order::with('items');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('whatsapp_number', 'like', "%{$search}%");
            });
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Admin/Orders/Index', [
            'orders' => $orders,
            'filters' => [
                'status' => $request->status,
                'search' => $request->search,
            ],
        ]);
    }

    public function show(Order $order): Response
    {
        return Inertia::render('Admin/Orders/Show', [
            'order' => $order->load(['items.product']),
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,completed,cancelled',
        ]);

        $order->update($validated);

        return back()->with('success', "Status pesanan {$order->order_number} berhasil diperbarui.");
    }

    public function destroy(Order $order): RedirectResponse
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Pesanan berhasil dihapus.');
    }
}

