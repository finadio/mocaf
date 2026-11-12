<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import type { Article } from '@/types';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import { Plus, Edit, Trash2, Search, Eye, Calendar, Tag } from 'lucide-vue-next';

interface PaginatedArticles {
    data: Article[];
    links: { url: string | null; label: string; active: boolean }[];
    total: number;
}

const props = defineProps<{
    articles: PaginatedArticles;
    filters: { search?: string; status?: string };
}>();

const searchInput = ref(props.filters.search || '');
const selectedStatus = ref(props.filters.status || '');

function applyFilter() {
    router.get(
        '/admin/articles',
        {
            search: searchInput.value || undefined,
            status: selectedStatus.value || undefined,
        },
        { preserveState: true, preserveScroll: true }
    );
}

function deleteArticle(art: Article) {
    if (confirm(`Hapus artikel "${art.title}"?`)) {
        router.delete(`/admin/articles/${art.id}`, {
            preserveScroll: true,
        });
    }
}

function formatDate(dateStr?: string): string {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    }).format(d);
}
</script>

<template>
    <AdminLayout title="Kelola Artikel & Jurnal">
        <Head title="Kelola Artikel - Moodles Admin" />

        <div class="space-y-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h2 class="font-serif font-bold text-2xl text-forest">Artikel &amp; Edukasi MOCAF</h2>
                    <p class="text-xs text-darkbrown/60">Tulis publikasi riset, dokumentasi kegiatan, dan tips memasak</p>
                </div>

                <Link
                    href="/admin/articles/create"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-forest hover:bg-deep text-cream text-xs font-poppins font-bold shadow-card transition-all transform hover:-translate-y-0.5"
                >
                    <Plus class="w-4 h-4 text-moodles" />
                    <span>Tulis Artikel Baru</span>
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
                        placeholder="Cari judul artikel..."
                        class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border-cream bg-cream/20 text-darkbrown focus:ring-forest focus:border-forest"
                    />
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <select
                        v-model="selectedStatus"
                        @change="applyFilter"
                        class="text-xs rounded-xl border-cream bg-cream/20 text-darkbrown py-2 pl-3 pr-8 focus:ring-forest focus:border-forest"
                    >
                        <option value="">Semua Status</option>
                        <option value="published">Terbit (Published)</option>
                        <option value="draft">Draf (Draft)</option>
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

            <!-- Articles Table -->
            <div class="bg-white rounded-3xl border border-cream shadow-sm overflow-hidden p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-cream/40 text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/70">
                            <tr>
                                <th class="py-3.5 px-4 rounded-l-xl">Artikel</th>
                                <th class="py-3.5 px-4">Kategori</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-4">Tanggal Rilis</th>
                                <th class="py-3.5 px-4 rounded-r-xl text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-cream/60">
                            <tr v-if="articles.data.length === 0">
                                <td colspan="5" class="py-12 text-center text-darkbrown/60 text-xs">
                                    Tidak ada artikel yang cocok dengan pencarian.
                                </td>
                            </tr>
                            <tr
                                v-for="art in articles.data"
                                :key="art.id"
                                class="hover:bg-cream/20 transition-colors"
                            >
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <img
                                            :src="art.thumbnail || '/assets/ingredients/tepung_mocaf_dan_singkong.jpg'"
                                            :alt="art.title"
                                            class="w-14 h-10 rounded-xl object-cover bg-cream/40 border border-cream shrink-0"
                                        />
                                        <div class="min-w-0">
                                            <span class="font-bold text-forest text-sm block truncate max-w-[280px]">
                                                {{ art.title }}
                                            </span>
                                            <span class="text-[11px] text-darkbrown/50 font-poppins block truncate max-w-[280px]">
                                                Slug: {{ art.slug }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-xs">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-poppins font-semibold bg-softgreen text-forest">
                                        {{ art.category }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span
                                        :class="[
                                            'px-2.5 py-0.5 rounded-full text-[10px] font-poppins font-bold uppercase',
                                            art.status === 'published' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'
                                        ]"
                                    >
                                        {{ art.status === 'published' ? 'Terbit' : 'Draf' }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-xs font-poppins text-darkbrown/70">
                                    {{ formatDate(art.published_at) }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link
                                            v-if="art.status === 'published'"
                                            :href="`/jurnal/${art.slug}`"
                                            target="_blank"
                                            class="p-2 rounded-xl text-darkbrown/60 hover:text-forest hover:bg-cream/60 transition-colors"
                                            title="Lihat Artikel"
                                        >
                                            <Eye class="w-4 h-4" />
                                        </Link>
                                        <Link
                                            :href="`/admin/articles/${art.id}/edit`"
                                            class="p-2 rounded-xl text-forest hover:bg-cream/60 transition-colors"
                                            title="Edit Artikel"
                                        >
                                            <Edit class="w-4 h-4" />
                                        </Link>
                                        <button
                                            type="button"
                                            @click="deleteArticle(art)"
                                            class="p-2 rounded-xl text-red-500 hover:text-red-700 hover:bg-red-50 transition-colors"
                                            title="Hapus Artikel"
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
                <Pagination :links="articles.links" />
            </div>
        </div>
    </AdminLayout>
</template>
