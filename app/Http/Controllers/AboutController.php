<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class AboutController extends Controller
{
    public function index(): Response
    {
        $milestones = [
            [
                'year' => 'Awal Gagasan',
                'title' => 'Melihat Potensi Singkong Banjarnegara',
                'description' => 'Kabupaten Banjarnegara memiliki limpahan hasil panen singkong berkualitas tinggi namun seringkali nilai tambahnya belum optimal bagi petani lokal.',
            ],
            [
                'year' => 'Riset Hilirisasi',
                'title' => 'Kolaborasi LPPM Unsoed & UD Usaha Mandiri',
                'description' => 'Program Hilirisasi Riset Prioritas (PHRP) LPPM Universitas Jenderal Soedirman bersama UD. Usaha Mandiri menggelar pelatihan produksi mi basah dan kering berbasis MOCAF.',
            ],
            [
                'year' => 'Formulasi Produk',
                'title' => 'Kelahiran Standar Mutu Moodles',
                'description' => 'Eksperimen berulang kali guna mendapatkan formulasi mi yang kenyal alami, ramah bagi lambung sensitif, tanpa pengawet sintetik.',
            ],
            [
                'year' => 'Peluncuran',
                'title' => 'Hadir untuk Seluruh Nusantara',
                'description' => 'Moodles Mi Goreng dan Mi Kuah siap dinikmati sebagai gaya baru menikmati mi yang lezat, praktis, dan berdaya lokal.',
            ],
        ];

        return Inertia::render('Public/About', [
            'milestones' => $milestones,
        ]);
    }
}

