<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CheckoutController extends Controller
{
    public function index(Request $request): Response|RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        $items = array_values($cart);

        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }

        return Inertia::render('Public/Checkout/Index', [
            'cartItems' => $items,
            'subtotal' => $subtotal,
            'total' => $subtotal,
        ]);
    }

    public function store(CheckoutRequest $request): Response|RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        $items = array_values($cart);

        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang belanja Anda masih kosong.');
        }

        $validated = $request->validated();

        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += ($item['price'] * $item['quantity']);
        }
        $total = $subtotal;

        /** @var Order $order */
        $order = null;

        // Gunakan DB Transaction untuk menjamin atomisitas order dan item
        DB::transaction(function () use ($validated, $items, $subtotal, $total, &$order) {
            // Generate nomor unik: MDL-YYYYMMDD-XXXX
            $uniqueCode = strtoupper(Str::random(4));
            $orderNumber = 'MDL-' . date('Ymd') . '-' . $uniqueCode;

            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_name' => $validated['customer_name'],
                'whatsapp_number' => $validated['whatsapp_number'],
                'address' => $validated['address'],
                'notes' => $validated['notes'] ?? null,
                'subtotal' => $subtotal,
                'total' => $total,
                'status' => 'pending',
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'] ?? null,
                    'product_name_snapshot' => $item['name'],
                    'variant_snapshot' => $item['variant'] ?? null,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);
            }
        });

        // Kosongkan keranjang di session
        $request->session()->forget('cart');

        // Buat pesan WhatsApp terformat
        $storePhone = SiteSetting::get('whatsapp_number') ?? config('app.whatsapp_number', env('WHATSAPP_NUMBER', '6281234567890'));
        $cleanPhone = preg_replace('/[^0-9]/', '', $storePhone);

        $waMessage = "Halo Admin Moodles Banjarnegara,\n";
        $waMessage .= "Saya ingin mengonfirmasi pemesanan mi instan MOCAF:\n\n";
        $waMessage .= "📋 *No. Pesanan:* {$order->order_number}\n";
        $waMessage .= "👤 *Nama Pemesan:* {$order->customer_name}\n";
        $waMessage .= "📱 *WhatsApp:* {$order->whatsapp_number}\n";
        $waMessage .= "📍 *Alamat Pengiriman:*\n{$order->address}\n\n";
        $waMessage .= "🍜 *Rincian Pesanan:*\n";

        foreach ($items as $item) {
            $formattedPrice = number_format($item['price'], 0, ',', '.');
            $formattedItemSub = number_format($item['subtotal'], 0, ',', '.');
            $waMessage .= "- {$item['name']} ({$item['variant']}) x {$item['quantity']} = Rp {$formattedItemSub}\n";
        }

        $formattedTotal = number_format($order->total, 0, ',', '.');
        $waMessage .= "\n💰 *Total Pembayaran:* Rp {$formattedTotal}\n";

        if (!empty($order->notes)) {
            $waMessage .= "📝 *Catatan:* {$order->notes}\n";
        }

        $waMessage .= "\nMohon konfirmasi ketersediaan stok, ongkos kirim, dan nomor rekening pembayaran. Terima kasih!";

        $whatsappUrl = 'https://wa.me/' . $cleanPhone . '?text=' . urlencode($waMessage);

        return Inertia::render('Public/Checkout/Index', [
            'orderSuccess' => [
                'order' => $order->load('items'),
                'whatsappUrl' => $whatsappUrl,
            ],
            'cartItems' => [],
            'subtotal' => 0,
            'total' => 0,
        ]);
    }
}

