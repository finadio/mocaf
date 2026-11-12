<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MoodlesComprehensiveTest extends TestCase
{
    protected User $adminUser;
    protected User $regularUser;
    protected Product $productGoreng;
    protected Product $productKuah;
    protected Category $categoryMi;

    protected function setUp(): void
    {
        parent::setUp();

        // Siapkan user admin & regular
        $this->adminUser = User::firstOrCreate(
            ['email' => 'admin@moodles.id'],
            [
                'name' => 'Admin Moodles',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        $this->regularUser = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'Customer Biasa',
                'password' => Hash::make('secret123'),
                'role' => 'user',
            ]
        );

        $this->categoryMi = Category::firstOrCreate(
            ['slug' => 'mi-instan-mocaf'],
            ['name' => 'Mi Instan MOCAF', 'description' => 'Kategori Mi MOCAF']
        );

        $this->productGoreng = Product::firstOrCreate(
            ['slug' => 'moodles-mi-goreng'],
            [
                'category_id' => $this->categoryMi->id,
                'name' => 'Moodles Mi Goreng',
                'variant' => 'Goreng',
                'price' => 12000,
                'weight' => 85,
                'is_active' => true,
                'is_featured' => true,
            ]
        );

        $this->productKuah = Product::firstOrCreate(
            ['slug' => 'moodles-mi-kuah'],
            [
                'category_id' => $this->categoryMi->id,
                'name' => 'Moodles Mi Kuah',
                'variant' => 'Kuah',
                'price' => 12000,
                'weight' => 80,
                'is_active' => true,
                'is_featured' => true,
            ]
        );
    }

    /**
     * 1. Halaman Beranda
     */
    public function test_home_page_loads_successfully()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * 2. Navigasi & Halaman Tentang Kami
     */
    public function test_about_page_loads_successfully()
    {
        $response = $this->get('/tentang-kami');
        $response->assertStatus(200);
    }

    /**
     * 3. Katalog Produk
     */
    public function test_products_catalog_loads_successfully()
    {
        $response = $this->get('/produk');
        $response->assertStatus(200);

        // Filter kategori
        $filtered = $this->get('/produk?kategori=mi-instan-mocaf');
        $filtered->assertStatus(200);
    }

    /**
     * 4. Detail Produk
     */
    public function test_product_detail_page_loads()
    {
        $response = $this->get("/produk/{$this->productGoreng->slug}");
        $response->assertStatus(200);
    }

    /**
     * 5, 6, 7, 8. Keranjang: Tambah, Ubah Jumlah, Hapus, Perhitungan Subtotal
     */
    public function test_cart_operations_and_subtotal_calculation()
    {
        // Akses keranjang awal
        $cartPage = $this->get('/keranjang');
        $cartPage->assertStatus(200);

        // Tambah produk ke keranjang
        $addResponse = $this->post('/keranjang/tambah', [
            'product_id' => $this->productGoreng->id,
            'quantity' => 2,
        ]);
        $addResponse->assertSessionHas('cart');
        $addResponse->assertSessionHas('success');

        $sessionCart = session('cart');
        $this->assertArrayHasKey($this->productGoreng->id, $sessionCart);
        $this->assertEquals(2, $sessionCart[$this->productGoreng->id]['quantity']);
        $this->assertEquals(24000, $sessionCart[$this->productGoreng->id]['subtotal']);

        // Ubah jumlah produk
        $updateResponse = $this->patch("/keranjang/{$this->productGoreng->id}", [
            'quantity' => 3,
        ]);
        $updateResponse->assertSessionHas('success');
        $updatedCart = session('cart');
        $this->assertEquals(3, $updatedCart[$this->productGoreng->id]['quantity']);
        $this->assertEquals(36000, $updatedCart[$this->productGoreng->id]['subtotal']);

        // Hapus produk dari keranjang
        $deleteResponse = $this->delete("/keranjang/{$this->productGoreng->id}");
        $deleteResponse->assertSessionHas('success');
        $emptyCart = session('cart');
        $this->assertArrayNotHasKey($this->productGoreng->id, $emptyCart);
    }

    /**
     * 9, 10, 11, 12, 13. Checkout, Validasi, Nomor Pesanan Unik, Simpan Order, Tautan WhatsApp
     */
    public function test_checkout_validation_order_generation_and_whatsapp_link()
    {
        // 10. Validasi checkout saat kosong
        $checkoutEmpty = $this->get('/checkout');
        $checkoutEmpty->assertRedirect(route('cart.index'));

        // Isi keranjang
        $this->post('/keranjang/tambah', [
            'product_id' => $this->productGoreng->id,
            'quantity' => 2,
        ]);
        $this->post('/keranjang/tambah', [
            'product_id' => $this->productKuah->id,
            'quantity' => 1,
        ]);

        $checkoutPage = $this->get('/checkout');
        $checkoutPage->assertStatus(200);

        // Uji validasi input pelanggan
        $invalidCheckout = $this->post('/checkout', [
            'customer_name' => '',
            'whatsapp_number' => 'abc',
            'address' => '',
        ]);
        $invalidCheckout->assertSessionHasErrors(['customer_name', 'whatsapp_number', 'address']);

        // Uji checkout valid
        $validCheckout = $this->post('/checkout', [
            'customer_name' => 'Fina Rahmawati',
            'whatsapp_number' => '081234567890',
            'address' => 'Jl. Pemuda No. 10 Banjarnegara RT 01 RW 02',
            'notes' => 'Tolong bubble wrap tebal ya.',
        ]);
        $validCheckout->assertStatus(200);

        // Verifikasi database: Order dan Order Items tersimpan
        $order = Order::where('customer_name', 'Fina Rahmawati')->latest()->first();
        $this->assertNotNull($order);
        $this->assertStringStartsWith('MDL-', $order->order_number);
        $this->assertEquals(36000, $order->total); // 2x12000 + 1x12000
        $this->assertEquals(2, $order->items()->count());

        // Verifikasi session cart telah dikosongkan
        $this->assertEmpty(session('cart', []));
    }

    /**
     * 14 & 15. Login Admin & Proteksi Halaman Admin
     */
    public function test_admin_authentication_and_route_protection()
    {
        // Akses dashboard tanpa login -> redirect ke login admin
        $unauthorized = $this->get('/admin/dashboard');
        $unauthorized->assertRedirect(route('admin.login'));

        // User biasa (role: user) mencoba akses dashboard -> 403 atau redirect
        $userAttempt = $this->actingAs($this->regularUser)->get('/admin/dashboard');
        $userAttempt->assertRedirect(route('admin.login'));

        // Halaman login admin dapat dibuka
        $loginPage = $this->get('/admin/login');
        $loginPage->assertStatus(200);

        // Login dengan kredensial admin valid
        $loginPost = $this->post('/admin/login', [
            'email' => 'admin@moodles.id',
            'password' => 'password123',
        ]);
        $loginPost->assertRedirect(route('admin.dashboard'));

        // Akses dashboard dengan akun admin berhasil
        $adminDashboard = $this->actingAs($this->adminUser)->get('/admin/dashboard');
        $adminDashboard->assertStatus(200);
    }

    /**
     * 16. CRUD Produk Admin
     */
    public function test_admin_product_crud()
    {
        $this->actingAs($this->adminUser);

        // Index
        $this->get('/admin/products')->assertStatus(200);

        // Create page
        $this->get('/admin/products/create')->assertStatus(200);

        // Store
        $storeResponse = $this->post('/admin/products', [
            'category_id' => $this->categoryMi->id,
            'name' => 'Moodles Varian Uji Coba',
            'variant' => 'Goreng',
            'price' => 15000,
            'weight' => 90,
            'short_description' => 'Varian uji coba deskripsi',
            'is_active' => 1,
            'is_featured' => 1,
        ]);
        $storeResponse->assertRedirect(route('admin.products.index'));

        $createdProduct = Product::where('name', 'Moodles Varian Uji Coba')->first();
        $this->assertNotNull($createdProduct);

        // Edit page
        $this->get("/admin/products/{$createdProduct->id}/edit")->assertStatus(200);

        // Update
        $updateResponse = $this->put("/admin/products/{$createdProduct->id}", [
            'category_id' => $this->categoryMi->id,
            'name' => 'Moodles Varian Uji Coba Updated',
            'variant' => 'Kuah',
            'price' => 16000,
            'weight' => 95,
            'is_active' => 1,
        ]);
        $updateResponse->assertRedirect(route('admin.products.index'));
        $this->assertEquals(16000, $createdProduct->fresh()->price);

        // Destroy
        $destroyResponse = $this->delete("/admin/products/{$createdProduct->id}");
        $destroyResponse->assertRedirect(route('admin.products.index'));
        $this->assertNull(Product::find($createdProduct->id));
    }

    /**
     * 17. CRUD Kategori Admin
     */
    public function test_admin_category_crud()
    {
        $this->actingAs($this->adminUser);

        // Index
        $this->get('/admin/categories')->assertStatus(200);

        // Store
        $this->post('/admin/categories', [
            'name' => 'Kategori Percobaan',
            'description' => 'Deskripsi percobaan',
        ])->assertSessionHas('success');

        $cat = Category::where('name', 'Kategori Percobaan')->first();
        $this->assertNotNull($cat);

        // Update
        $this->put("/admin/categories/{$cat->id}", [
            'name' => 'Kategori Percobaan Edit',
        ])->assertSessionHas('success');
        $this->assertEquals('Kategori Percobaan Edit', $cat->fresh()->name);

        // Destroy
        $this->delete("/admin/categories/{$cat->id}")->assertSessionHas('success');
        $this->assertNull(Category::find($cat->id));
    }

    /**
     * 18. CRUD Artikel Admin & Jurnal Publik
     */
    public function test_admin_article_crud_and_public_journal()
    {
        $this->actingAs($this->adminUser);

        // Index
        $this->get('/admin/articles')->assertStatus(200);

        // Store article
        $this->post('/admin/articles', [
            'title' => 'Manfaat Luar Biasa Singkong Banjarnegara',
            'category' => 'Mengenal MOCAF',
            'excerpt' => 'Singkong kaya serat dan bebas gluten.',
            'content' => '<p>Artikel lengkap seputar singkong Banjarnegara.</p>',
            'status' => 'published',
            'published_at' => now(),
        ])->assertRedirect(route('admin.articles.index'));

        $art = Article::where('title', 'Manfaat Luar Biasa Singkong Banjarnegara')->first();
        $this->assertNotNull($art);

        // Public Journal Index & Show
        $this->get('/jurnal')->assertStatus(200);
        $this->get("/jurnal/{$art->slug}")->assertStatus(200);

        // Destroy
        $this->delete("/admin/articles/{$art->id}")->assertRedirect(route('admin.articles.index'));
        $this->assertNull(Article::find($art->id));
    }

    /**
     * 19. Pengelolaan Pesanan & Update Status
     */
    public function test_admin_order_management()
    {
        $this->actingAs($this->adminUser);

        $order = Order::create([
            'order_number' => 'MDL-TEST-' . uniqid(),
            'customer_name' => 'Tes Pengujian',
            'whatsapp_number' => '08999999999',
            'address' => 'Banjarnegara',
            'subtotal' => 12000,
            'total' => 12000,
            'status' => 'pending',
        ]);

        // Orders index
        $this->get('/admin/orders')->assertStatus(200);

        // Order show
        $this->get("/admin/orders/{$order->id}")->assertStatus(200);

        // Update status
        $this->put("/admin/orders/{$order->id}", [
            'status' => 'confirmed',
        ])->assertSessionHas('success');
        $this->assertEquals('confirmed', $order->fresh()->status);
    }
}
