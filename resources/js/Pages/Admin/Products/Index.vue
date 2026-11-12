<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import type { Product, Category } from '@/types';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import { Plus, Edit, Trash2, Search, Filter, Eye, Star } from 'lucide-vue-next';

interface PaginatedProducts {
    data: Product[];
    links: { url: string | null; label: string; active: boolean }[];
    total: number;
}

const props = defineProps<{
    products: PaginatedProducts;
    categories: Category[];
    filters: { search?: string; category_id?: string };
}>();

const searchInput = ref(props.filters.search || '');
const selectedCategory = ref(props.filters.category_id || '');

function applyFilter() {
    router.get(
        '/admin/products',
        {
            search: searchInput.value || undefined,
            category_id: selectedCategory.value || undefined,
        },
        { preserveState: true, preserveScroll: true }
    );
}

function deleteProduct(product: Product) {
    if (confirm(`Apakah Anda yakin ingin menghapus produk "${product.name}"?`)) {
        router.delete(`/admin/products/${product.id}`, {
            preserveScroll: true,
        });
    }
}

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
    <AdminLayout title="Kelola Katalog Produk">
        <Head title="Kelola Produk - Moodles Admin" />

        <div class="space-y-6">
            <!-- Top Action Header -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h2 class="font-serif font-bold text-2xl text-forest">Daftar Produk</h2>
                    <p class="text-xs text-darkbrown/60">Kelola varian mi MOCAF, harga, foto, dan status ketersediaan</p>
                </div>

                <Link
                    href="/admin/products/create"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-forest hover:bg-deep text-cream text-xs font-poppins font-bold shadow-card transition-all transform hover:-translate-y-0.5"
                >
                    <Plus class="w-4 h-4 text-moodles" />
                    <span>Tambah Produk Baru</span>
                </Link>
            </div>

            <!-- Filters Bar -->
            <div class="bg-white rounded-3xl border border-cream p-4 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
                <div class="w-full sm:w-72 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-darkbrown/40">
                        <Search class="w-4 h-4" />
                    </div>
                    <input
                        v-model="searchInput"
                        @keyup.enter="applyFilter"
                        type="text"
                        placeholder="Cari nama atau varian..."
                        class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border-cream bg-cream/20 text-darkbrown focus:ring-forest focus:border-forest"
                    />
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <select
                        v-model="selectedCategory"
                        @change="applyFilter"
                        class="text-xs rounded-xl border-cream bg-cream/20 text-darkbrown py-2 pl-3 pr-8 focus:ring-forest focus:border-forest"
                    >
                        <option value="">Semua Kategori</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                            {{ cat.name }}
                        </option>
                    </select>

                    <button
                        type="button"
                        @click="applyFilter"
                        class="px-4 py-2 rounded-xl bg-forest text-cream text-xs font-poppins font-semibold hover:bg-deep transition-colors"
                    >
                        Filter
                    </button>
                </div>
            </div>

            <!-- Products Table -->
            <div class="bg-white rounded-3xl border border-cream shadow-sm overflow-hidden p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-cream/40 text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/70">
                            <tr>
                                <th class="py-3.5 px-4 rounded-l-xl">Produk</th>
                                <th class="py-3.5 px-4">Kategori</th>
                                <th class="py-3.5 px-4">Varian</th>
                                <th class="py-3.5 px-4">Berat</th>
                                <th class="py-3.5 px-4">Harga</th>
                                <th class="py-3.5 px-4">Unggulan</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-4 rounded-r-xl text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-cream/60">
                            <tr v-if="products.data.length === 0">
                                <td colspan="8" class="py-12 text-center text-darkbrown/60 text-xs">
                                    Tidak ada produk yang cocok dengan pencarian.
                                </td>
                            </tr>
                            <tr
                                v-for="product in products.data"
                                :key="product.id"
                                class="hover:bg-cream/20 transition-colors"
                            >
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <img
                                            :src="product.main_image || '/assets/products/moodles-duo-bundling.jpg'"
                                            :alt="product.name"
                                            class="w-12 h-12 rounded-xl object-cover bg-cream/40 border border-cream shrink-0"
                                        />
                                        <div class="min-w-0">
                                            <span class="font-bold text-forest text-sm block truncate max-w-[180px]">
                                                {{ product.name }}
                                            </span>
                                            <span class="text-[11px] text-darkbrown/50 font-poppins block">
                                                Slug: {{ product.slug }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-xs font-medium text-darkbrown/80">
                                    {{ product.category?.name || '-' }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-poppins font-bold uppercase tracking-wider bg-softgreen text-forest">
                                        {{ product.variant }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-xs font-poppins">
                                    {{ product.weight }}g
                                </td>
                                <td class="py-3.5 px-4 font-poppins font-bold text-xs text-moodles">
                                    {{ formatRupiah(product.price) }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span v-if="product.is_featured" class="text-amber-500 font-poppins text-xs font-semibold flex items-center gap-1">
                                        <Star class="w-3.5 h-3.5 fill-amber-500" />
                                        <span>Ya</span>
                                    </span>
                                    <span v-else class="text-darkbrown/40 text-xs">-</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span
                                        :class="[
                                            'px-2.5 py-0.5 rounded-full text-[10px] font-poppins font-bold uppercase',
                                            product.is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-600'
                                        ]"
                                    >
                                        {{ product.is_active ? 'Aktif' : 'Non-Aktif' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link
                                            :href="`/produk/${product.slug}`"
                                            target="_blank"
                                            class="p-2 rounded-xl text-darkbrown/60 hover:text-forest hover:bg-cream/60 transition-colors"
                                            title="Lihat di Toko"
                                        >
                                            <Eye class="w-4 h-4" />
                                        </Link>
                                        <Link
                                            :href="`/admin/products/${product.id}/edit`"
                                            class="p-2 rounded-xl text-forest hover:bg-cream/60 transition-colors"
                                            title="Edit Produk"
                                        >
                                            <Edit class="w-4 h-4" />
                                        </Link>
                                        <button
                                            type="button"
                                            @click="deleteProduct(product)"
                                            class="p-2 rounded-xl text-red-500 hover:text-red-700 hover:bg-red-50 transition-colors"
                                            title="Hapus Produk"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <Pagination :links="products.links" />
            </div>
        </div>
    </AdminLayout>
</template>
