<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

defineProps<{
    links: PaginationLink[];
}>();

function cleanLabel(label: string): string {
    return label
        .replace('&laquo; Previous', '« Sebelumnya')
        .replace('Next &raquo;', 'Berikutnya »')
        .replace('&laquo;', '«')
        .replace('&raquo;', '»');
}
</script>

<template>
    <div v-if="links.length > 3" class="flex flex-wrap justify-center items-center gap-1.5 pt-8">
        <template v-for="(link, index) in links" :key="index">
            <div
                v-if="link.url === null"
                class="px-3.5 py-2 text-xs font-poppins rounded-xl border border-cream text-darkbrown/40 cursor-not-allowed select-none"
                v-html="cleanLabel(link.label)"
            />
            <Link
                v-else
                :href="link.url"
                :class="[
                    'px-3.5 py-2 text-xs font-poppins font-semibold rounded-xl border transition-all duration-200 focus:outline-none',
                    link.active
                        ? 'bg-forest text-cream border-forest shadow-sm'
                        : 'bg-white text-darkbrown border-cream hover:bg-cream/40 hover:text-forest'
                ]"
                v-html="cleanLabel(link.label)"
            />
        </template>
    </div>
</template>

