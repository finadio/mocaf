<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin User
        User::updateOrCreate(
            ['email' => 'admin@moodles.id'],
            [
                'name' => 'Admin Moodles',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Kategori Produk
        $catMi = Category::updateOrCreate(
            ['slug' => 'mi-instan-mocaf'],
            [
                'name' => 'Mi Instan MOCAF',
                'description' => 'Varian mi instan berbahan dasar tepung MOCAF pilihan asal Banjarnegara.',
            ]
        );

        $catBundling = Category::updateOrCreate(
            ['slug' => 'paket-bundling'],
            [
                'name' => 'Paket Bundling',
                'description' => 'Pilihan paket hemat untuk mencoba seluruh varian Moodles.',
            ]
        );

        // 3. Produk Moodles
        $pGoreng = Product::updateOrCreate(
            ['slug' => 'moodles-mi-goreng'],
            [
                'category_id' => $catMi->id,
                'name' => 'Moodles Mi Goreng',
                'variant' => 'Goreng',
                'short_description' => 'Mi goreng instan berbahan dasar tepung MOCAF Banjarnegara dengan paduan bumbu rempah gurih khas nusantara.',
                'description' => "Moodles Mi Goreng menghadirkan kenikmatan mi goreng dengan tekstur kenyal alami yang diperoleh dari tepung singkong termodifikasi (MOCAF) pilihan hasil panen petani Banjarnegara.\n\nDipadukan dengan racikan bumbu rempah nusantara yang harum dan gurih, menghadirkan sajian mi yang lezat, praktis, dan ramah di perut. Sangat cocok dinikmati sebagai sarapan maupun santap malam praktis bersama keluarga.",
                'price' => 12000,
                'weight' => 85,
                'main_image' => '/assets/products/moodles-goreng-utama.jpg',
                'gallery' => [
                    '/assets/products/moodles-goreng-sumpit.jpg',
                    '/assets/products/moodles-goreng-tekstur.jpg',
                    '/assets/products/moodles-goreng-sajian.jpg',
                ],
                'ingredients' => 'Tepung MOCAF (Modified Cassava Flour) Banjarnegara, Tepung Terigu, Minyak Nabati, Garam, Penstabil Nabati. Bumbu: Garam, Gula, Bawang Merah, Bawang Putih, Kecap Manis, Minyak Bawang, Rempah-rempah Pilihan.',
                'nutrition_information' => 'Informasi nilai gizi saat ini sedang dalam proses verifikasi uji laboratorium berkala untuk standardisasi mutu produk.',
                'allergen_information' => 'Mengandung gluten dari tepung terigu dan turunan kedelai dari kecap manis.',
                'preparation_instructions' => "1. Rebus mi dalam 400ml air mendidih selama 3-4 menit sambil diaduk perlahan.\n2. Siapkan bumbu dan minyak bumbu di atas piring saji.\n3. Tiriskan mi dari air rebusan, lalu tuangkan ke dalam piring bumbu.\n4. Aduk hingga bumbu merata sempurna. Moodles Mi Goreng siap dinikmati selagi hangat.",
                'storage_instructions' => 'Simpan di tempat yang kering, sejuk, dan terhindar dari paparan sinar matahari langsung serta benda berbau menyengat.',
                'is_active' => true,
                'is_featured' => true,
            ]
        );

        $pKuah = Product::updateOrCreate(
            ['slug' => 'moodles-mi-kuah'],
            [
                'category_id' => $catMi->id,
                'name' => 'Moodles Mi Kuah',
                'variant' => 'Kuah',
                'short_description' => 'Mi kuah instan MOCAF dengan kehangatan kaldu gurih berempah yang sedap dan menyegarkan.',
                'description' => "Moodles Mi Kuah dibuat dari formulasi tepung MOCAF Banjarnegara yang menghasilkan kelembutan helai mi yang pas saat disiram kuah kaldu hangat.\n\nKuah kaldu beraroma rempah pilihan memberikan kesegaran alami yang menenangkan, cocok dinikmati kapan saja sebagai sajian keluarga yang menghangatkan suasana.",
                'price' => 12000,
                'weight' => 85,
                'main_image' => '/assets/products/moodles-kuah-utama.jpg',
                'gallery' => [
                    '/assets/products/moodles-duo-bundling.jpg',
                    '/assets/food/mie_kuah_bowl.jpg',
                    '/assets/food/mie_kuah_sajian_lifestyle.jpg',
                ],
                'ingredients' => 'Tepung MOCAF (Modified Cassava Flour) Banjarnegara, Tepung Terigu, Minyak Nabati, Garam, Pengatur Keasaman. Bumbu: Garam, Gula, Ekstrak Kaldu Jamur & Rempah Gurih, Bawang Putih, Daun Bawang Kering, Minyak Kaldu.',
                'nutrition_information' => 'Informasi nilai gizi saat ini sedang dalam proses verifikasi laboratorium terakreditasi.',
                'allergen_information' => 'Mengandung gluten dari tepung terigu.',
                'preparation_instructions' => "1. Rebus mi ke dalam 400ml air mendidih selama 3 menit.\n2. Tuangkan bumbu dan minyak ke dalam mangkuk saji.\n3. Tuang mi beserta kuah panas secukupnya ke dalam mangkuk.\n4. Aduk merata dan nikmati selagi hangat.",
                'storage_instructions' => 'Simpan di tempat yang sejuk dan kering, terlindung dari sinar matahari langsung.',
                'is_active' => true,
                'is_featured' => true,
            ]
        );

        $pBundle = Product::updateOrCreate(
            ['slug' => 'paket-duo-moodles'],
            [
                'category_id' => $catBundling->id,
                'name' => 'Paket Kombo Duo Moodles',
                'variant' => 'Mix',
                'short_description' => 'Paket kombo hemat 1 bungkus Mi Goreng dan 1 bungkus Mi Kuah MOCAF.',
                'description' => "Nikmati kedua varian Moodles sekaligus dengan harga lebih hemat. Paket berisi 1 pcs Moodles Mi Goreng dan 1 pcs Moodles Mi Kuah untuk pengalaman mencicipi mi lokal singkong yang utuh.",
                'price' => 23000,
                'weight' => 170,
                'main_image' => '/assets/products/moodles-duo-bundling.jpg',
                'gallery' => [
                    '/assets/products/moodles-goreng-utama.jpg',
                    '/assets/products/moodles-kuah-utama.jpg',
                    '/assets/products/moodles-goreng-sumpit.jpg',
                ],
                'ingredients' => 'Tepung MOCAF Banjarnegara pilihan dan rempah alami.',
                'nutrition_information' => 'Informasi nilai gizi mengikuti kemasan masing-masing varian.',
                'allergen_information' => 'Mengandung gluten dan turunan kedelai.',
                'preparation_instructions' => 'Ikuti petunjuk cara penyajian pada masing-masing kemasan varian Goreng dan Kuah.',
                'storage_instructions' => 'Simpan di tempat yang sejuk dan kering.',
                'is_active' => true,
                'is_featured' => true,
            ]
        );

        // 4. Artikel Jurnal
        Article::updateOrCreate(
            ['slug' => 'mengenal-tepung-mocaf-banjarnegara'],
            [
                'title' => 'Mengenal Tepung MOCAF: Inovasi Singkong Banjarnegara untuk Kemandirian Pangan',
                'category' => 'Mengenal MOCAF',
                'excerpt' => 'MOCAF (Modified Cassava Flour) bukan sekadar tepung singkong biasa. Melalui proses fermentasi alami, MOCAF menghadirkan karakteristik unggul sebagai alternatif bahan baku pangan masa depan.',
                'content' => "<p>Indonesia kaya akan potensi komoditas singkong yang melimpah, dan Kabupaten Banjarnegara di Jawa Tengah menjadi salah satu episentrum pengembangan tepung singkong termodifikasi atau yang dikenal luas sebagai <strong>MOCAF (Modified Cassava Flour)</strong>.</p><p>Berbeda dengan tepung tapioka atau gaplek konvensional, MOCAF diproduksi melalui proses fermentasi alami menggunakan mikroba asam laktat. Proses ini mengubah struktur karbohidrat singkong sehingga menghilangkan aroma khas singkong yang pekat, menghasilkan warna putih bersih, serta memberikan tekstur yang lebih elastis dan lembut.</p><p>Pemanfaatan MOCAF sebagai bahan baku utama mi instan Moodles merupakan ikhtiar nyata dalam mengurangi ketergantungan impor gandum sekaligus meningkatkan kesejahteraan para petani singkong lokal di Banjarnegara.</p>",
                'thumbnail' => '/assets/ingredients/tepung_mocaf_dan_singkong.jpg',
                'published_at' => now()->subDays(5),
                'status' => 'published',
            ]
        );

        Article::updateOrCreate(
            ['slug' => 'cerita-di-balik-tekstur-kenyal-moodles'],
            [
                'title' => 'Eksplorasi Rasa: Meracik Tekstur Kenyal Alami Mi Berbasis Singkong',
                'category' => 'Pengembangan Produk',
                'excerpt' => 'Tantangan terbesar membuat mi dari bahan singkong adalah menjaga elastisitas tanpa bahan kimia berlebih. Simak bagaimana Moodles menemukan formula terbaiknya.',
                'content' => "<p>Dalam perjalanan meracik Moodles, tim pengembang menghadapi tantangan menarik: bagaimana menghasilkan helai mi yang tetap kenyal, tidak mudah putus saat direbus, dan mampu mengikat bumbu dengan sempurna tanpa bergantung sepenuhnya pada gluten gandum.</p><p>Melalui puluhan kali uji coba rasio pencampuran MOCAF kualitas prima dan pengaturan suhu pengeringan bertahap, Moodles berhasil melahirkan helai mi yang memiliki karakteristik unik: kenyal alami, lembut di kunyah, dan terasa lebih ringan di perut.</p><p>Hasil riset ini kini dapat dinikmati masyarakat luas dalam dua varian utama, yaitu Mi Goreng dengan bumbu rempah nusantara dan Mi Kuah dengan kaldu hangat yang sedap.</p>",
                'thumbnail' => '/assets/ingredients/closeup_tekstur_mie.jpg',
                'published_at' => now()->subDays(3),
                'status' => 'published',
            ]
        );

        Article::updateOrCreate(
            ['slug' => 'tips-memasak-moodles-lebih-nikmat'],
            [
                'title' => '5 Tips Rahasia Memasak Moodles Mi Goreng Agar Bumbu Meresap Sempurna',
                'category' => 'Tips Penyajian',
                'excerpt' => 'Ingin menikmati sajian Moodles seperti ala cafe? Simak panduan waktu perebusan dan tips paduan topping pendukung berikut ini.',
                'content' => "<p>Memasak mi berbasis MOCAF memiliki seni tersendiri agar teksturnya mencapai titik kematangan sempurna (<em>al dente</em>). Berikut tips sederhana dari dapur Moodles:</p><ul><li><strong>Gunakan Air Melimpah:</strong> Pastikan mi terendam sepenuhnya saat air sudah mendidih bergejolak.</li><li><strong>Durasi 3 Menit Pas:</strong> Jangan merebus terlalu lama agar kelembutan dan kekenyalan helai mi tetap terjaga optimal.</li><li><strong>Tiriskan Maksimal:</strong> Untuk varian goreng, tiriskan air hingga kering sebelum mencampurnya dengan bumbu agar cita rasa rempah tidak terlarut air sisa.</li><li><strong>Tambahkan Topping Alami:</strong> Lengkapi sajian dengan telur mata sapi, irisan cabai rawit segar, dan daun bawang untuk kelezatan maksimal.</li></ul>",
                'thumbnail' => '/assets/food/mie_goreng_sajian_lifestyle.jpg',
                'published_at' => now()->subDay(),
                'status' => 'published',
            ]
        );

        // 5. Pengaturan Website
        $settings = [
            'whatsapp_number' => '6281234567890',
            'store_name' => 'Moodles Banjarnegara',
            'store_address' => 'Jl. Pemuda No. 45, Kutabanjarnegara, Kec. Banjarnegara, Kabupaten Banjarnegara, Jawa Tengah 53415',
            'store_email' => 'halo@moodles.id',
            'announcement_text' => 'Mi Instan Sehat Berbahan MOCAF Banjarnegara — Dapatkan Pengalaman Rasa Baru Sekarang!',
            'instagram_url' => 'https://instagram.com/moodles.id',
            'facebook_url' => 'https://facebook.com/moodles.id',
        ];

        foreach ($settings as $key => $val) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $val]);
        }

        // 6. Contoh Pesanan Awal untuk Dashboard Admin
        $order1 = Order::updateOrCreate(
            ['order_number' => 'MDL-20261110-1001'],
            [
                'customer_name' => 'Dewi Lestari',
                'whatsapp_number' => '081298765432',
                'address' => 'Jl. Merdeka No. 10, RT 02/04, Purwokerto, Banyumas, Jawa Tengah',
                'notes' => 'Tolong dibungkus bubble wrap tebal ya kak.',
                'subtotal' => 48000,
                'total' => 48000,
                'status' => 'confirmed',
                'created_at' => now()->subDays(2),
            ]
        );

        OrderItem::firstOrCreate(
            ['order_id' => $order1->id, 'product_name_snapshot' => 'Moodles Mi Goreng'],
            [
                'product_id' => $pGoreng->id,
                'variant_snapshot' => 'Goreng',
                'price' => 12000,
                'quantity' => 2,
                'subtotal' => 24000,
            ]
        );

        OrderItem::firstOrCreate(
            ['order_id' => $order1->id, 'product_name_snapshot' => 'Moodles Mi Kuah'],
            [
                'product_id' => $pKuah->id,
                'variant_snapshot' => 'Kuah',
                'price' => 12000,
                'quantity' => 2,
                'subtotal' => 24000,
            ]
        );

        $order2 = Order::updateOrCreate(
            ['order_number' => 'MDL-20261111-2045'],
            [
                'customer_name' => 'Bambang Triatmojo',
                'whatsapp_number' => '085712345678',
                'address' => 'Perumahan Griya Indah Blok C3, Banjarnegara',
                'notes' => 'Diantar sore hari setelah jam 16:00.',
                'subtotal' => 23000,
                'total' => 23000,
                'status' => 'pending',
                'created_at' => now()->subHours(4),
            ]
        );

        OrderItem::firstOrCreate(
            ['order_id' => $order2->id, 'product_name_snapshot' => 'Paket Kombo Duo Moodles'],
            [
                'product_id' => $pBundle->id,
                'variant_snapshot' => 'Mix',
                'price' => 23000,
                'quantity' => 1,
                'subtotal' => 23000,
            ]
        );
    }
}
