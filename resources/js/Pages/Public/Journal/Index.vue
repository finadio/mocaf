<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import type { Article } from '@/types';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import Breadcrumb from '@/Components/UI/Breadcrumb.vue';
import JournalCard from '@/Components/Public/JournalCard.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import { vReveal } from '@/Composables/useReveal';
import { BookOpen } from 'lucide-vue-next';

interface PaginatedArticles {
    data: Article[];
    links: { url: string | null; label: string; active: boolean }[];
    total: number;
}

const props = defineProps<{
    articles: PaginatedArticles;
    categories: string[];
    currentCategory?: string;
}>();

function filterCategory(cat: string | null) {
    router.get(
        '/jurnal',
        { kategori: cat || undefined },
        { preserveState: true, preserveScroll: true }
    );
}
</script>

<template>
    <PublicLayout>
        <Head title="Jurnal MOCAF & Cerita Pangan Banjarnegara - Moodles" />

        <!-- Header Hero -->
        <section class="bg-forest text-cream pt-24 sm:pt-28 pb-12 sm:pb-16 border-b-4 border-moodles relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-3.5 hero-enter">
                <Breadcrumb :items="[{ label: 'Jurnal MOCAF' }]" class="text-cream/70" />
                <h1 class="font-poppins text-3xl sm:text-4xl font-bold text-cream leading-[1.25] tracking-tight">
                    Jurnal &amp; Inspirasi Pangan Lokal
                </h1>
                <p class="font-sans text-cream/85 text-sm sm:text-base max-w-2xl leading-relaxed">
                    Kumpulan artikel edukatif, cerita riset pengembangan mi MOCAF, panduan tips memasak, dan dokumentasi aktivitas nyata bersama masyarakat Banjarnegara.
                </p>
            </div>
        </section>

        <!-- Main Content -->
        <section class="py-16 bg-offwhite min-h-[500px]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
                <!-- Category Tabs -->
                <div v-reveal class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none border-b border-cream">
                    <button
                        type="button"
                        @click="filterCategory(null)"
                        :class="[
                            'px-4 py-2 rounded-full text-xs font-poppins font-semibold transition-all shrink-0 focus:outline-none',
                            !currentCategory ? 'bg-forest text-cream shadow-sm' : 'bg-white text-darkbrown hover:bg-cream border border-cream'
                        ]"
                    >
                        Semua Kategori
                    </button>
                    <button
                        v-for="cat in categories"
                        :key="cat"
                        type="button"
                        @click="filterCategory(cat)"
                        :class="[
                            'px-4 py-2 rounded-full text-xs font-poppins font-semibold transition-all shrink-0 focus:outline-none',
                            currentCategory === cat ? 'bg-forest text-cream shadow-sm' : 'bg-white text-darkbrown hover:bg-cream border border-cream'
                        ]"
                    >
                        {{ cat }}
                    </button>
                </div>

                <!-- Articles Grid -->
                <div v-if="articles.data.length > 0" class="space-y-10">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div
                            v-for="(article, index) in articles.data"
                            :key="article.id"
                            v-reveal="Math.min(index * 80, 240)"
                        >
                            <JournalCard
                                :article="article"
                                class="h-full"
                            />
                        </div>
                    </div>

                    <!-- Pagination -->
                    <Pagination :links="articles.links" />
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-20 bg-white rounded-3xl border border-cream p-8 space-y-4 max-w-md mx-auto">
                    <div class="w-16 h-16 rounded-full bg-cream/70 flex items-center justify-center mx-auto text-olive">
                        <BookOpen class="w-8 h-8 text-olive/60" />
                    </div>
                    <h3 class="font-poppins font-bold text-2xl text-forest">Belum Ada Artikel</h3>
                    <p class="text-sm text-darkbrown/70">
                        Tidak ada artikel pada kategori ini saat ini. Silakan pilih kategori lain.
                    </p>
                    <button
                        type="button"
                        @click="filterCategory(null)"
                        class="px-6 py-2.5 rounded-full bg-forest text-cream text-xs font-poppins font-bold hover:bg-deep transition-colors"
                    >
                        Lihat Semua Artikel
                    </button>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
