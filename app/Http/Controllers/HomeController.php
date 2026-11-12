<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with('category')
            ->orderBy('id', 'asc')
            ->get();

        $allProducts = Product::where('is_active', true)
            ->with('category')
            ->orderBy('id', 'asc')
            ->get();

        $latestArticles = Article::published()
            ->latest('published_at')
            ->take(3)
            ->get();

        $activities = [
            [
                'title' => 'Pelatihan Sentra Produksi LPPM Unsoed',
                'description' => 'Program Hilirisasi Riset Prioritas bersama mitra UD. Usaha Mandiri Banjarnegara dalam pelatihan produksi mi MOCAF bermutu tinggi.',
                'image' => '/assets/activities/riset_lppm_unsoed_tim.jpg',
                'badge' => 'Riset Hilirisasi',
            ],
            [
                'title' => 'Teknologi Pencetakan Mi MOCAF',
                'description' => 'Eksperimen pencetakan mi basah dan kering dengan elastisitas alami tanpa bahan kimia pengenyal sintetik.',
                'image' => '/assets/activities/teknologi_pencetakan_mi_unsoed.jpg',
                'badge' => 'Teknologi Pangan',
            ],
            [
                'title' => 'Uji Organoleptik & Cicip Rasa',
                'description' => 'Sesi evaluasi rasa mi goreng & kuah bersama warga dan konsumen untuk menjamin kelezatan dan kenyamanan cerna.',
                'image' => '/assets/activities/uji_cicip_konsumen_mi.png',
                'badge' => 'Uji Konsumen',
            ],
            [
                'title' => 'Pemberdayaan Perajin Lokal Banjarnegara',
                'description' => 'Pelatihan intensif tim produksi lokal guna menjaga kesinambungan pasokan dan mutu pangan sehat.',
                'image' => '/assets/activities/pelatihan_produksi_mi_mocaf.png',
                'badge' => 'Kemitraan UMKM',
            ],
        ];

        return Inertia::render('Public/Home', [
            'featuredProducts' => $featuredProducts,
            'products' => $allProducts,
            'latestArticles' => $latestArticles,
            'activities' => $activities,
        ]);
    }
}

