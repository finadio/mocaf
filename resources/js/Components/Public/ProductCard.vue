<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { Product } from '@/types';
import { useCartStore } from '@/Stores/cart';
import { Plus, Eye, Scale } from 'lucide-vue-next';

const props = defineProps<{
    product: Product;
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
    <div class="group flex flex-col bg-white rounded-3xl border border-cream overflow-hidden shadow-sm hover:shadow-hover hover:-translate-y-1.5 transition-all duration-300">
        <!-- Thumbnail / Image Container -->
        <div class="relative w-full aspect-square bg-cream/40 overflow-hidden">
            <img
                :src="product.main_image || '/assets/products/moodles-duo-bundling.jpg'"
                :alt="product.name"
                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
                loading="lazy"
            />

            <!-- Varian & Feature Badges -->
            <div class="absolute top-3 left-3 flex flex-col gap-1.5 z-10">
                <span
                    :class="[
                        'px-3 py-1 rounded-full font-poppins text-xs font-bold uppercase tracking-wider shadow-sm',
                        product.variant.toLowerCase().includes('goreng') ? 'bg-moodles text-white' :
                        product.variant.toLowerCase().includes('kuah') ? 'bg-forest text-cream' : 'bg-olive text-white'
                    ]"
                >
                    Varian {{ product.variant }}
                </span>

                <span
                    v-if="product.is_featured"
                    class="px-2.5 py-0.5 rounded-full font-poppins text-[10px] font-semibold bg-deep/80 text-cream backdrop-blur-sm shadow-sm"
                >
                    ⭐ Unggulan
                </span>
            </div>

            <!-- Weight Badge -->
            <div class="absolute bottom-3 right-3 px-2.5 py-1 rounded-full bg-offwhite/90 backdrop-blur-sm text-darkbrown font-poppins text-xs font-semibold flex items-center gap-1 shadow-sm">
                <Scale class="w-3 h-3 text-olive" />
                <span>{{ product.weight }}g</span>
            </div>
        </div>

        <!-- Product Details -->
        <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
            <div class="space-y-1.5">
                <span class="text-[11px] font-poppins font-semibold uppercase tracking-wider text-olive">
                    {{ product.category?.name || 'Mi Instan MOCAF' }}
                </span>
                <h3 class="font-poppins font-bold text-xl text-forest group-hover:text-deep transition-colors line-clamp-1">
                    <Link :href="`/produk/${product.slug}`">
                        {{ product.name }}
                    </Link>
                </h3>
                <p v-if="product.short_description" class="text-xs text-darkbrown/70 line-clamp-2 leading-relaxed">
                    {{ product.short_description }}
                </p>
            </div>

            <!-- Price and Actions -->
            <div class="pt-2 border-t border-cream flex items-center justify-between gap-2">
                <div>
                    <span class="block text-[10px] font-poppins text-darkbrown/60 uppercase tracking-wider">Harga</span>
                    <span class="font-poppins font-extrabold text-lg text-moodles">
                        {{ formatRupiah(product.price) }}
                    </span>
                </div>

                <div class="flex items-center gap-1.5">
                    <!-- Detail Button -->
                    <Link
                        :href="`/produk/${product.slug}`"
                        class="p-2.5 rounded-2xl bg-cream/70 hover:bg-cream text-darkbrown hover:text-forest transition-colors focus:outline-none"
                        title="Lihat Detail Produk"
                    >
                        <Eye class="w-4 h-4" />
                    </Link>

                    <!-- Tambah ke Keranjang Button -->
                    <button
                        type="button"
                        @click.prevent="cartStore.addToCart(product.id, 1)"
                        class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-2xl bg-forest hover:bg-deep text-cream text-xs font-medium shadow-card transition-all transform active:scale-95 focus:outline-none"
                        title="Tambah ke Keranjang"
                    >
                        <Plus class="w-4 h-4 text-moodles" />
                        <span class="hidden sm:inline font-poppins font-semibold">Keranjang</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
