<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import type { Product, Category } from '@/types';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import Breadcrumb from '@/Components/UI/Breadcrumb.vue';
import ProductGrid from '@/Components/Public/ProductGrid.vue';
import { vReveal } from '@/Composables/useReveal';
import { Filter, Sparkles } from 'lucide-vue-next';

const props = defineProps<{
    products: Product[];
    categories: Category[];
    currentCategory?: string;
    currentVariant?: string;
}>();

function filterByCategory(slug: string | null) {
    router.get(
        '/produk',
        {
            kategori: slug || undefined,
            varian: props.currentVariant || undefined,
        },
        { preserveState: true, preserveScroll: true }
    );
}

function filterByVariant(variant: string | null) {
    router.get(
        '/produk',
        {
            kategori: props.currentCategory || undefined,
            varian: variant || undefined,
        },
        { preserveState: true, preserveScroll: true }
    );
}
</script>

<template>
    <PublicLayout>
        <Head title="Katalog Produk - Mi Instan MOCAF Moodles" />

        <!-- Header -->
        <section class="bg-forest text-cream pt-24 sm:pt-28 pb-12 sm:pb-16 border-b-4 border-moodles relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-3.5 hero-enter">
                <Breadcrumb :items="[{ label: 'Katalog Produk' }]" class="text-cream/70" />
                <h1 class="font-poppins text-3xl sm:text-4xl font-bold text-cream leading-[1.25] tracking-tight">
                    Katalog Mi Instan Moodles
                </h1>
                <p class="font-sans text-cream/85 text-sm sm:text-base max-w-2xl leading-relaxed">
                    Pilihan sajian mi instan berbahan dasar tepung MOCAF pilihan dari Banjarnegara. Temukan varian favorit Anda untuk santap hangat bersama keluarga.
                </p>
            </div>
        </section>

        <!-- Main Content & Filters -->
        <section class="py-16 bg-offwhite min-h-[500px]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
                <!-- Filter Pills -->
                <div v-reveal class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-4 rounded-3xl bg-cream/30 border border-cream">
                    <!-- Category Pills -->
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/60 flex items-center gap-1.5 mr-1">
                            <Filter class="w-3.5 h-3.5 text-olive" />
                            <span>Kategori:</span>
                        </span>
                        <button
                            type="button"
                            @click="filterByCategory(null)"
                            :class="[
                                'px-4 py-2 rounded-full text-xs font-poppins font-semibold transition-all focus:outline-none',
                                !currentCategory ? 'bg-forest text-cream shadow-sm' : 'bg-white text-darkbrown hover:bg-cream border border-cream'
                            ]"
                        >
                            Semua Kategori
                        </button>
                        <button
                            v-for="cat in categories"
                            :key="cat.id"
                            type="button"
                            @click="filterByCategory(cat.slug)"
                            :class="[
                                'px-4 py-2 rounded-full text-xs font-poppins font-semibold transition-all focus:outline-none',
                                currentCategory === cat.slug ? 'bg-forest text-cream shadow-sm' : 'bg-white text-darkbrown hover:bg-cream border border-cream'
                            ]"
                        >
                            {{ cat.name }} ({{ cat.products_count ?? 0 }})
                        </button>
                    </div>

                    <!-- Variant Pills -->
                    <div class="flex items-center gap-2 self-stretch sm:self-auto justify-end">
                        <button
                            type="button"
                            @click="filterByVariant(null)"
                            :class="[
                                'px-3.5 py-1.5 rounded-full text-xs font-poppins font-medium transition-colors',
                                !currentVariant ? 'bg-olive text-white' : 'text-darkbrown/70 hover:text-forest'
                            ]"
                        >
                            Semua Varian
                        </button>
                        <button
                            type="button"
                            @click="filterByVariant('Goreng')"
                            :class="[
                                'px-3.5 py-1.5 rounded-full text-xs font-poppins font-medium transition-colors',
                                currentVariant === 'Goreng' ? 'bg-moodles text-white font-bold' : 'text-darkbrown/70 hover:text-forest'
                            ]"
                        >
                            Goreng
                        </button>
                        <button
                            type="button"
                            @click="filterByVariant('Kuah')"
                            :class="[
                                'px-3.5 py-1.5 rounded-full text-xs font-poppins font-medium transition-colors',
                                currentVariant === 'Kuah' ? 'bg-forest text-cream font-bold' : 'text-darkbrown/70 hover:text-forest'
                            ]"
                        >
                            Kuah
                        </button>
                    </div>
                </div>

                <!-- Products Grid or Empty State -->
                <div v-if="products.length > 0">
                    <ProductGrid :products="products" :columns="3" />
                </div>

                <div v-else class="text-center py-20 bg-white rounded-3xl border border-cream p-8 space-y-4">
                    <div class="w-16 h-16 rounded-full bg-cream/70 flex items-center justify-center mx-auto text-olive">
                        <Sparkles class="w-8 h-8 text-olive/60" />
                    </div>
                    <h3 class="font-poppins font-bold text-2xl text-forest">Produk Tidak Ditemukan</h3>
                    <p class="text-sm text-darkbrown/70 max-w-sm mx-auto">
                        Tidak ada varian mi yang cocok dengan filter yang Anda pilih. Silakan reset filter untuk melihat seluruh produk.
                    </p>
                    <button
                        type="button"
                        @click="router.get('/produk')"
                        class="px-6 py-2.5 rounded-full bg-forest text-cream text-xs font-poppins font-bold hover:bg-deep transition-colors"
                    >
                        Tampilkan Semua Produk
                    </button>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
