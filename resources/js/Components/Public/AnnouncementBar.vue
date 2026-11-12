<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import type { PageProps } from '@/types';

const page = usePage<PageProps>();
const text = computed(() => {
    const raw = page.props.site_settings?.announcement_text || 'Mi Instan Berbahan Dasar Tepung MOCAF Banjarnegara — Cita Rasa Baru, Kenyal Alami!';
    // Menghilangkan emoji jika ada agar tampilan tetap bersih dan konsisten
    return raw.replace(/[\u{1F300}-\u{1F9FF}]/gu, '').trim();
});
</script>

<template>
    <div class="bg-deep text-cream text-xs sm:text-sm py-2 px-4 transition-colors border-b border-white/5">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-1 text-center sm:text-left">
            <div class="font-medium tracking-wide">
                <span>{{ text }}</span>
            </div>
            <div class="hidden md:block text-cream/70 text-xs tracking-wider">
                <span>Banjarnegara, Jawa Tengah</span>
            </div>
        </div>
    </div>
</template>
