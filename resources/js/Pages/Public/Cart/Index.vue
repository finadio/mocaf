<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import type { CartItem } from '@/types';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import Breadcrumb from '@/Components/UI/Breadcrumb.vue';
import QuantitySelector from '@/Components/Public/QuantitySelector.vue';
import CartSummary from '@/Components/Public/CartSummary.vue';
import { useCartStore } from '@/Stores/cart';
import { Trash2, ShoppingBag, ArrowRight, ArrowLeft } from 'lucide-vue-next';

defineProps<{
    cartItems: CartItem[];
    subtotal: number;
    total: number;
}>();

const cartStore = useCartStore();

function formatRupiah(amount: number): string {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
}
</script>

<template>
    <PublicLayout>
        <Head title="Keranjang Belanja - Moodles" />

        <div class="bg-offwhite pt-24 sm:pt-28 pb-12 min-h-[600px]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 hero-enter">
                <!-- Breadcrumb -->
                <Breadcrumb :items="[{ label: 'Keranjang Belanja' }]" />

                <div class="border-b border-cream pb-4 flex items-baseline justify-between">
                    <div>
                        <h1 class="font-poppins text-3xl sm:text-4xl font-black text-forest">
                            Keranjang Belanja
                        </h1>
                        <p class="text-sm text-darkbrown/70 font-light mt-1">
                            Periksa rincian varian mi Moodles sebelum melanjutkan ke pemesanan WhatsApp.
                        </p>
                    </div>
                    <span v-if="cartItems.length > 0" class="text-xs font-poppins font-semibold text-olive hidden sm:inline">
                        {{ cartItems.length }} varian dipilih
                    </span>
                </div>

                <!-- Empty State -->
                <div v-if="cartItems.length === 0" class="bg-white rounded-3xl border border-cream p-12 text-center max-w-xl mx-auto space-y-6 shadow-sm my-10">
                    <div class="w-20 h-20 rounded-full bg-cream/70 flex items-center justify-center mx-auto text-olive">
                        <ShoppingBag class="w-10 h-10 text-olive/60" />
                    </div>
                    <div class="space-y-2">
                        <h2 class="font-poppins font-bold text-2xl text-forest">Keranjang Belanja Masih Kosong</h2>
                        <p class="text-sm text-darkbrown/70 leading-relaxed">
                            Anda belum menambahkan produk Moodles ke dalam keranjang. Silakan pilih varian Mi Goreng atau Mi Kuah favorit Anda di katalog!
                        </p>
                    </div>
                    <div class="pt-2">
                        <Link
                            href="/produk"
                            class="inline-flex items-center gap-2 px-8 py-3.5 rounded-full bg-forest text-cream font-poppins font-bold text-sm hover:bg-deep shadow-card transition-all"
                        >
                            <span>Jelajahi Katalog Produk</span>
                            <ArrowRight class="w-4 h-4 text-moodles" />
                        </Link>
                    </div>
                </div>

                <!-- Cart Content Grid -->
                <div v-else class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    <!-- Left: Items Table / List -->
                    <div class="lg:col-span-8 bg-white rounded-3xl border border-cream shadow-sm p-6 sm:p-8 space-y-6">
                        <div class="divide-y divide-cream">
                            <div
                                v-for="item in cartItems"
                                :key="item.product_id"
                                class="py-6 first:pt-0 last:pb-0 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4"
                            >
                                <!-- Product Thumbnail & Details -->
                                <div class="flex items-center gap-4 min-w-0">
                                    <img
                                        :src="item.main_image || '/assets/products/moodles-goreng-utama.jpg'"
                                        :alt="item.name"
                                        class="w-20 h-20 rounded-2xl object-cover bg-cream/30 border border-cream shrink-0"
                                    />
                                    <div class="space-y-1 min-w-0">
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-poppins font-bold uppercase tracking-wider bg-softgreen text-forest">
                                            Varian {{ item.variant }}
                                        </span>
                                        <h3 class="font-poppins font-bold text-base sm:text-lg text-forest hover:text-deep transition-colors truncate">
                                            <Link :href="`/produk/${item.slug}`">
                                                {{ item.name }}
                                            </Link>
                                        </h3>
                                        <p class="text-xs font-poppins font-bold text-moodles">
                                            {{ formatRupiah(item.price) }} <span class="text-darkbrown/50 font-normal">/ pcs</span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Quantity and Subtotal Actions -->
                                <div class="w-full sm:w-auto flex items-center justify-between sm:justify-end gap-6 pt-3 sm:pt-0 border-t sm:border-t-0 border-cream/60">
                                    <QuantitySelector
                                        :model-value="item.quantity"
                                        size="md"
                                        @change="(val) => cartStore.updateQuantity(item.product_id, val)"
                                    />

                                    <div class="text-right min-w-[90px]">
                                        <span class="block text-[10px] font-poppins text-darkbrown/50 uppercase">Subtotal</span>
                                        <span class="font-poppins font-black text-sm sm:text-base text-forest">
                                            {{ formatRupiah(item.subtotal) }}
                                        </span>
                                    </div>

                                    <button
                                        type="button"
                                        @click="cartStore.removeItem(item.product_id)"
                                        class="p-2 rounded-xl text-darkbrown/40 hover:text-red-600 hover:bg-red-50 transition-colors focus:outline-none"
                                        title="Hapus Produk"
                                    >
                                        <Trash2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Bottom Cart Actions -->
                        <div class="pt-6 border-t border-cream flex items-center justify-between">
                            <Link
                                href="/produk"
                                class="inline-flex items-center gap-2 text-xs font-poppins font-bold text-forest hover:text-deep transition-colors"
                            >
                                <ArrowLeft class="w-4 h-4 text-moodles" />
                                <span>Tambah Varian Lainnya</span>
                            </Link>
                        </div>
                    </div>

                    <!-- Right: Summary & Checkout Button -->
                    <div class="lg:col-span-4 space-y-4">
                        <CartSummary
                            :items="cartItems"
                            :subtotal="subtotal"
                            :total="total"
                        />

                        <Link
                            href="/checkout"
                            class="w-full inline-flex items-center justify-center gap-2.5 px-6 py-4 rounded-2xl bg-moodles hover:bg-moodles-hover text-white font-poppins font-bold text-sm shadow-warm transition-all transform hover:-translate-y-0.5"
                        >
                            <span>Lanjut ke Formulir Checkout</span>
                            <ArrowRight class="w-4 h-4" />
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
