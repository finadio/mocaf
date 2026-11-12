<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import type { Article } from '@/types';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import Breadcrumb from '@/Components/UI/Breadcrumb.vue';
import JournalCard from '@/Components/Public/JournalCard.vue';
import { vReveal } from '@/Composables/useReveal';
import { Calendar, Tag, ArrowLeft, Share2 } from 'lucide-vue-next';

const props = defineProps<{
    article: Article;
    relatedArticles: Article[];
}>();

function formatDate(dateStr?: string): string {
    if (!dateStr) return '';
    const d = new Date(dateStr);
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    }).format(d);
}

function shareArticle() {
    if (navigator.share) {
        navigator.share({
            title: props.article.title,
            url: window.location.href,
        }).catch(() => {});
    } else {
        navigator.clipboard.writeText(window.location.href);
        alert('Tautan artikel berhasil disalin ke clipboard!');
    }
}
</script>

<template>
    <PublicLayout>
        <Head :title="`${article.title} - Jurnal Moodles`" />

        <article class="bg-offwhite pt-24 sm:pt-28 pb-8 sm:pb-14">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                <!-- Breadcrumb -->
                <Breadcrumb
                    :items="[
                        { label: 'Jurnal MOCAF', href: '/jurnal' },
                        { label: article.category, href: `/jurnal?kategori=${encodeURIComponent(article.category)}` },
                        { label: article.title }
                    ]"
                />

                <!-- Article Header -->
                <header class="space-y-4 hero-enter">
                    <div class="flex items-center gap-2">
                        <span class="px-3.5 py-1 rounded-full text-xs font-poppins font-bold uppercase tracking-wider bg-softgreen text-forest flex items-center gap-1.5">
                            <Tag class="w-3.5 h-3.5 text-moodles" />
                            <span>{{ article.category }}</span>
                        </span>
                        <div class="flex items-center gap-1.5 text-xs text-darkbrown/60 font-poppins">
                            <Calendar class="w-3.5 h-3.5 text-olive" />
                            <time :datetime="article.published_at">{{ formatDate(article.published_at) }}</time>
                        </div>
                    </div>

                    <h1 class="font-poppins text-2xl sm:text-3xl lg:text-4xl font-black text-forest leading-tight">
                        {{ article.title }}
                    </h1>

                    <p v-if="article.excerpt" class="text-base sm:text-lg text-darkbrown/80 font-light leading-relaxed italic border-l-4 border-moodles pl-4 py-1">
                        {{ article.excerpt }}
                    </p>
                </header>

                <!-- Featured Image -->
                <div class="rounded-3xl overflow-hidden shadow-warm border border-cream aspect-video bg-cream/30 hero-enter-delay-1">
                    <img
                        :src="article.thumbnail || '/assets/ingredients/tepung_mocaf_dan_singkong.jpg'"
                        :alt="article.title"
                        class="w-full h-full object-cover object-center"
                    />
                </div>

                <!-- Article Content Body -->
                <div class="bg-white rounded-3xl border border-cream p-6 sm:p-10 shadow-sm">
                    <div
                        class="prose prose-lg max-w-none prose-headings:font-poppins prose-headings:text-forest prose-p:text-darkbrown/85 prose-p:leading-relaxed prose-a:text-forest hover:prose-a:text-moodles"
                        v-html="article.content"
                    ></div>

                    <!-- Article Footer Actions -->
                    <div class="pt-8 mt-10 border-t border-cream flex flex-col sm:flex-row items-center justify-between gap-4">
                        <Link
                            href="/jurnal"
                            class="inline-flex items-center gap-2 text-xs font-poppins font-bold text-forest hover:text-deep transition-colors"
                        >
                            <ArrowLeft class="w-4 h-4 text-moodles" />
                            <span>Kembali ke Semua Artikel</span>
                        </Link>

                        <button
                            type="button"
                            @click="shareArticle"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-cream/70 hover:bg-cream text-darkbrown font-poppins font-semibold text-xs transition-colors focus:outline-none"
                        >
                            <Share2 class="w-4 h-4 text-olive" />
                            <span>Bagikan Cerita Ini</span>
                        </button>
                    </div>
                </div>

                <!-- Related Articles Section -->
                <section v-if="relatedArticles.length > 0" v-reveal class="pt-12 border-t border-cream space-y-6">
                    <h2 class="font-poppins font-bold text-2xl text-forest">Artikel Terkait Lainnya</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <JournalCard
                            v-for="rel in relatedArticles"
                            :key="rel.id"
                            :article="rel"
                        />
                    </div>
                </section>
            </div>
        </article>
    </PublicLayout>
</template>
