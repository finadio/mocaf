<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { Article } from '@/types';
import { Calendar, ArrowRight, Tag } from 'lucide-vue-next';

defineProps<{
    article: Article;
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
</script>

<template>
    <article class="group flex flex-col bg-white rounded-3xl border border-cream overflow-hidden shadow-sm hover:shadow-hover hover:-translate-y-1.5 transition-all duration-300">
        <!-- Thumbnail -->
        <Link :href="`/jurnal/${article.slug}`" class="relative w-full aspect-video bg-cream/40 overflow-hidden block">
            <img
                :src="article.thumbnail || '/assets/ingredients/tepung_mocaf_dan_singkong.jpg'"
                :alt="article.title"
                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-500"
                loading="lazy"
            />
            <div class="absolute top-3 left-3">
                <span class="px-3 py-1 rounded-full font-poppins text-xs font-semibold bg-forest/90 text-cream backdrop-blur-sm flex items-center gap-1 shadow-sm">
                    <Tag class="w-3 h-3 text-moodles" />
                    <span>{{ article.category }}</span>
                </span>
            </div>
        </Link>

        <!-- Body -->
        <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
            <div class="space-y-2">
                <div class="flex items-center gap-1.5 text-xs text-darkbrown/60 font-poppins">
                    <Calendar class="w-3.5 h-3.5 text-olive" />
                    <time :datetime="article.published_at">{{ formatDate(article.published_at) }}</time>
                </div>

                <h3 class="font-poppins font-bold text-xl text-forest group-hover:text-moodles transition-colors leading-snug">
                    <Link :href="`/jurnal/${article.slug}`">
                        {{ article.title }}
                    </Link>
                </h3>

                <p v-if="article.excerpt" class="text-sm text-darkbrown/70 line-clamp-3 leading-relaxed">
                    {{ article.excerpt }}
                </p>
            </div>

            <!-- Footer Read Link -->
            <div class="pt-3 border-t border-cream">
                <Link
                    :href="`/jurnal/${article.slug}`"
                    class="inline-flex items-center gap-1.5 text-xs font-poppins font-bold text-forest group-hover:text-moodles transition-colors"
                >
                    <span>Baca Selengkapnya</span>
                    <ArrowRight class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" />
                </Link>
            </div>
        </div>
    </article>
</template>
