<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $activeProductsCount = Product::where('is_active', true)->count();
        $totalProductsCount = Product::count();
        $categoriesCount = Category::count();
        $articlesCount = Article::count();
        $ordersCount = Order::count();
        $pendingOrdersCount = Order::where('status', 'pending')->count();

        $recentOrders = Order::with('items')
            ->latest()
            ->take(6)
            ->get();

        $totalRevenue = Order::whereIn('status', ['confirmed', 'processing', 'completed'])->sum('total');

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'active_products' => $activeProductsCount,
                'total_products' => $totalProductsCount,
                'categories_count' => $categoriesCount,
                'articles_count' => $articlesCount,
                'orders_count' => $ordersCount,
                'pending_orders' => $pendingOrdersCount,
                'total_revenue' => (float) $totalRevenue,
            ],
            'recentOrders' => $recentOrders,
        ]);
    }
}

