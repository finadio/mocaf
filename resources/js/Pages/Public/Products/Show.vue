<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import type { Product } from '@/types';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import Breadcrumb from '@/Components/UI/Breadcrumb.vue';
import ProductGallery from '@/Components/Public/ProductGallery.vue';
import QuantitySelector from '@/Components/Public/QuantitySelector.vue';
import ProductGrid from '@/Components/Public/ProductGrid.vue';
import WhatsAppButton from '@/Components/Public/WhatsAppButton.vue';
import { useCartStore } from '@/Stores/cart';
import {
    ShoppingBag,
    Scale,
    ShieldCheck,
    ChefHat,
    Archive,
    Info,
    AlertCircle,
    CheckCircle2
} from 'lucide-vue-next';

const props = defineProps<{
    product: Product;
    relatedProducts: Product[];
}>();

const cartStore = useCartStore();
const selectedQty = ref(1);
const activeTab = ref<'deskripsi' | 'penyajian' | 'komposisi' | 'gizi'>('deskripsi');

function formatRupiah(amount: number): string {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
}

function handleAddToCart() {
    cartStore.addToCart(props.product.id, selectedQty.value, true);
}
</script>

<template>
    <PublicLayout>
        <Head :title="`${product.name} - Mi Instan Tepung MOCAF Banjarnegara`" />

        <div class="bg-offwhite pt-24 sm:pt-28 pb-8 sm:pb-12 border-b border-cream">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <!-- Breadcrumb -->
                <Breadcrumb
                    :items="[
                        { label: 'Katalog Produk', href: '/produk' },
                        { label: product.name }
                    ]"
                />

                <!-- Main Product Section -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 xl:gap-14 items-start">
                    <!-- Left: Gallery -->
                    <div class="lg:col-span-6 hero-enter">
                        <ProductGallery
                            :main-image="product.main_image"
                            :gallery="product.gallery"
                            :product-name="product.name"
                        />
                    </div>

                    <!-- Right: Info & Actions -->
                    <div class="lg:col-span-6 space-y-6 hero-enter-delay-1">
                        <!-- Category & Variant Badges -->
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full text-xs font-poppins font-bold uppercase tracking-wider bg-softgreen text-forest">
                                {{ product.category?.name || 'Mi Instan MOCAF' }}
                            </span>
                            <span
                                :class="[
                                    'px-3 py-1 rounded-full text-xs font-poppins font-bold uppercase tracking-wider',
                                    product.variant.toLowerCase().includes('goreng') ? 'bg-moodles text-white' :
                                    product.variant.toLowerCase().includes('kuah') ? 'bg-forest text-cream' : 'bg-olive text-white'
                                ]"
                            >
                                Varian {{ product.variant }}
                            </span>
                            <span v-if="product.is_featured" class="px-2.5 py-0.5 rounded-full text-xs font-poppins font-medium bg-deep text-cream">
                                ⭐ Unggulan
                            </span>
                        </div>

                        <!-- Product Title -->
                        <h1 class="font-poppins text-2xl sm:text-3xl lg:text-4xl font-bold text-forest leading-tight">
                            {{ product.name }}
                        </h1>

                        <!-- Price & Weight Row -->
                        <div class="flex items-baseline gap-6 p-4 rounded-2xl bg-cream/30 border border-cream">
                            <div>
                                <span class="text-xs text-darkbrown/60 uppercase font-poppins font-semibold block">Harga Satuan</span>
                                <span class="font-poppins font-black text-3xl text-moodles">
                                    {{ formatRupiah(product.price) }}
                                </span>
                            </div>
                            <div class="border-l border-cream pl-6 flex items-center gap-2">
                                <Scale class="w-5 h-5 text-olive" />
                                <div>
                                    <span class="text-xs text-darkbrown/60 uppercase font-poppins font-semibold block">Berat Bersih</span>
                                    <span class="font-poppins font-bold text-base text-forest">{{ product.weight }} gram</span>
                                </div>
                            </div>
                        </div>

                        <!-- Short Description -->
                        <p v-if="product.short_description" class="text-base text-darkbrown/80 leading-relaxed font-light">
                            {{ product.short_description }}
                        </p>

                        <!-- Quantity Selector & Action Buttons -->
                        <div class="space-y-4 pt-4 border-t border-cream">
                            <div class="flex items-center gap-4">
                                <span class="text-xs font-poppins font-bold text-darkbrown/70 uppercase tracking-wider">
                                    Jumlah:
                                </span>
                                <QuantitySelector
                                    v-model="selectedQty"
                                    size="lg"
                                />
                                <span class="text-xs text-darkbrown/60 font-poppins">
                                    Subtotal: <strong class="text-forest font-bold">{{ formatRupiah(product.price * selectedQty) }}</strong>
                                </span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                                <!-- Add to Cart Button -->
                                <button
                                    type="button"
                                    @click="handleAddToCart"
                                    class="w-full inline-flex items-center justify-center gap-2.5 px-6 py-4 rounded-2xl bg-forest hover:bg-deep text-cream font-poppins font-bold text-sm shadow-card hover:shadow-hover transition-all transform hover:-translate-y-0.5 focus:outline-none"
                                >
                                    <ShoppingBag class="w-5 h-5 text-moodles" />
                                    <span>Tambah ke Keranjang</span>
                                </button>

                                <!-- Direct WhatsApp Order Button -->
                                <WhatsAppButton
                                    :message="`Halo Admin Moodles Banjarnegara, saya ingin memesan langsung produk ${product.name} (Varian: ${product.variant}) sebanyak ${selectedQty} bungkus.`"
                                    label="Pesan via WhatsApp"
                                    size="lg"
                                    variant="primary"
                                />
                            </div>
                        </div>

                        <!-- Guarantees list -->
                        <div class="grid grid-cols-2 gap-3 pt-4 border-t border-cream text-xs text-darkbrown/70">
                            <div class="flex items-center gap-2">
                                <CheckCircle2 class="w-4 h-4 text-olive shrink-0" />
                                <span>Tepung MOCAF Alami Banjarnegara</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <CheckCircle2 class="w-4 h-4 text-olive shrink-0" />
                                <span>Tanpa Pewarna Sintetik Berbahaya</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs Section: Deskripsi, Penyajian, Komposisi, Nilai Gizi -->
                <div class="pt-10">
                    <div class="border-b border-cream flex gap-2 sm:gap-4 overflow-x-auto scrollbar-none">
                        <button
                            type="button"
                            @click="activeTab = 'deskripsi'"
                            :class="[
                                'pb-3 px-4 font-poppins font-bold text-base sm:text-lg transition-colors border-b-2 -mb-px focus:outline-none',
                                activeTab === 'deskripsi' ? 'border-moodles text-forest' : 'border-transparent text-darkbrown/60 hover:text-forest'
                            ]"
                        >
                            Deskripsi Lengkap
                        </button>
                        <button
                            type="button"
                            @click="activeTab = 'penyajian'"
                            :class="[
                                'pb-3 px-4 font-poppins font-bold text-base sm:text-lg transition-colors border-b-2 -mb-px focus:outline-none',
                                activeTab === 'penyajian' ? 'border-moodles text-forest' : 'border-transparent text-darkbrown/60 hover:text-forest'
                            ]"
                        >
                            Cara Penyajian &amp; Simpan
                        </button>
                        <button
                            type="button"
                            @click="activeTab = 'komposisi'"
                            :class="[
                                'pb-3 px-4 font-poppins font-bold text-base sm:text-lg transition-colors border-b-2 -mb-px focus:outline-none',
                                activeTab === 'komposisi' ? 'border-moodles text-forest' : 'border-transparent text-darkbrown/60 hover:text-forest'
                            ]"
                        >
                            Komposisi &amp; Alergen
                        </button>
                        <button
                            type="button"
                            @click="activeTab = 'gizi'"
                            :class="[
                                'pb-3 px-4 font-poppins font-bold text-base sm:text-lg transition-colors border-b-2 -mb-px focus:outline-none',
                                activeTab === 'gizi' ? 'border-moodles text-forest' : 'border-transparent text-darkbrown/60 hover:text-forest'
                            ]"
                        >
                            Informasi Nilai Gizi
                        </button>
                    </div>

                    <!-- Tab Contents -->
                    <div class="py-8 bg-white rounded-3xl border border-cream p-6 sm:p-8 mt-6 shadow-sm">
                        <!-- Tab 1: Deskripsi -->
                        <div v-if="activeTab === 'deskripsi'" class="space-y-4 text-darkbrown/80 leading-relaxed">
                            <h3 class="font-poppins font-bold text-xl text-forest">Tentang {{ product.name }}</h3>
                            <div class="whitespace-pre-line text-base">
                                {{ product.description || 'Deskripsi lengkap untuk produk ini sedang diperbarui.' }}
                            </div>
                        </div>

                        <!-- Tab 2: Cara Penyajian & Penyimpanan -->
                        <div v-else-if="activeTab === 'penyajian'" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <div class="flex items-center gap-2 text-forest font-poppins font-bold text-lg">
                                    <ChefHat class="w-5 h-5 text-moodles" />
                                    <span>Petunjuk Cara Memasak</span>
                                </div>
                                <div class="whitespace-pre-line text-sm text-darkbrown/80 leading-relaxed bg-cream/30 p-4 rounded-2xl border border-cream">
                                    {{ product.preparation_instructions || 'Petunjuk cara memasak tertera pada bagian belakang kemasan produk.' }}
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center gap-2 text-forest font-poppins font-bold text-lg">
                                    <Archive class="w-5 h-5 text-olive" />
                                    <span>Petunjuk Cara Penyimpanan</span>
                                </div>
                                <div class="whitespace-pre-line text-sm text-darkbrown/80 leading-relaxed bg-cream/30 p-4 rounded-2xl border border-cream">
                                    {{ product.storage_instructions || 'Simpan di tempat kering, sejuk, dan terhindar dari paparan sinar matahari langsung.' }}
                                </div>
                            </div>
                        </div>

                        <!-- Tab 3: Komposisi & Alergen -->
                        <div v-else-if="activeTab === 'komposisi'" class="space-y-6">
                            <div class="space-y-2">
                                <h4 class="font-poppins font-bold text-lg text-forest">Komposisi Bahan Baku</h4>
                                <p class="text-sm text-darkbrown/80 leading-relaxed">
                                    {{ product.ingredients || 'Informasi komposisi bahan baku sedang dalam pembaruan.' }}
                                </p>
                            </div>

                            <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/60 space-y-1.5">
                                <div class="flex items-center gap-2 text-amber-900 font-poppins font-semibold text-xs uppercase tracking-wider">
                                    <AlertCircle class="w-4 h-4 text-amber-700" />
                                    <span>Informasi Alergen</span>
                                </div>
                                <p class="text-xs text-amber-800 leading-relaxed">
                                    {{ product.allergen_information || 'Mengandung gluten dari tepung terigu. Diproduksi menggunakan peralatan yang mungkin juga memproses turunan kedelai.' }}
                                </p>
                            </div>
                        </div>

                        <!-- Tab 4: Informasi Nilai Gizi -->
                        <div v-else-if="activeTab === 'gizi'" class="space-y-4">
                            <h4 class="font-poppins font-bold text-lg text-forest">Status Informasi Nilai Gizi</h4>
                            <div class="p-5 rounded-2xl bg-softgreen/60 border border-forest/10 flex items-start gap-3">
                                <Info class="w-5 h-5 text-forest shrink-0 mt-0.5" />
                                <div class="text-xs sm:text-sm text-darkbrown/80 space-y-1">
                                    <p class="font-semibold text-forest">Standardisasi Laboratorium Berkala</p>
                                    <p>
                                        {{ product.nutrition_information || 'Informasi nilai gizi saat ini sedang dalam proses pengujian laboratorium terakreditasi resmi untuk memastikan data nutrisi yang akurat dan transparan.' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Products -->
                <div v-if="relatedProducts && relatedProducts.length > 0" class="pt-16 border-t border-cream space-y-8">
                    <div class="flex items-center justify-between">
                        <h2 class="font-poppins font-bold text-2xl sm:text-3xl text-forest">
                            Varian Moodles Lainnya
                        </h2>
                        <Link href="/produk" class="text-xs font-poppins font-bold text-olive hover:text-forest transition-colors">
                            Lihat Semua →
                        </Link>
                    </div>
                    <ProductGrid :products="relatedProducts" :columns="3" />
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
