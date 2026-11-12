<?php

namespace App\Http\Middleware;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $rawCart = $request->session()->get('cart', []);
        $totalItems = 0;
        $subtotal = 0;

        foreach ($rawCart as $item) {
            $qty = (int) ($item['quantity'] ?? 1);
            $price = (float) ($item['price'] ?? 0);
            $totalItems += $qty;
            $subtotal += ($qty * $price);
        }

        // Default settings fallback
        $siteSettings = [
            'whatsapp_number' => config('app.whatsapp_number', env('WHATSAPP_NUMBER', '6281234567890')),
            'store_name' => config('app.whatsapp_store_name', env('WHATSAPP_STORE_NAME', 'Moodles Banjarnegara')),
            'store_address' => 'Banjarnegara, Jawa Tengah, Indonesia',
            'announcement_text' => 'Mi Instan MOCAF Banjarnegara — Rasa Lezat, Tekstur Kenyal Alami!',
        ];

        try {
            $dbSettings = SiteSetting::pluck('value', 'key')->toArray();
            $siteSettings = array_merge($siteSettings, $dbSettings);
        } catch (\Throwable $e) {
            // DB not ready or running during tests
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'cart' => [
                'items' => $rawCart,
                'total_items' => $totalItems,
                'subtotal' => $subtotal,
                'total' => $subtotal,
            ],
            'site_settings' => $siteSettings,
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
        ];
    }
}
