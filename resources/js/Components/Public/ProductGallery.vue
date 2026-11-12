<script setup lang="ts">
import { ref, computed } from 'vue';

const props = defineProps<{
    mainImage?: string;
    gallery?: string[];
    productName: string;
}>();

const images = computed(() => {
    const list: string[] = [];
    if (props.mainImage) list.push(props.mainImage);
    if (props.gallery && Array.isArray(props.gallery)) {
        props.gallery.forEach((img) => {
            if (img && !list.includes(img)) list.push(img);
        });
    }
    if (list.length === 0) {
        list.push('/assets/products/moodles-duo-bundling.jpg');
    }
    return list;
});

const activeIndex = ref(0);
const activeImage = computed(() => images.value[activeIndex.value] || images.value[0]);
</script>

<template>
    <div class="space-y-4">
        <!-- Main Display Frame -->
        <div class="relative w-full aspect-square bg-cream/30 rounded-3xl overflow-hidden border border-cream shadow-warm group">
            <img
                :src="activeImage"
                :alt="productName"
                class="w-full h-full object-cover object-center transition-all duration-500 transform group-hover:scale-105"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/10 via-transparent to-transparent pointer-events-none"></div>
        </div>

        <!-- Thumbnails List -->
        <div v-if="images.length > 1" class="flex items-center gap-3 overflow-x-auto pb-2 scrollbar-none">
            <button
                v-for="(img, idx) in images"
                :key="idx"
                type="button"
                @click="activeIndex = idx"
                :class="[
                    'relative w-20 h-20 rounded-2xl overflow-hidden border-2 shrink-0 transition-all duration-200 focus:outline-none',
                    activeIndex === idx ? 'border-moodles ring-2 ring-moodles/30 scale-105' : 'border-cream hover:border-forest/40 opacity-70 hover:opacity-100'
                ]"
            >
                <img :src="img" :alt="`${productName} thumbnail ${idx + 1}`" class="w-full h-full object-cover object-center" />
            </button>
        </div>
    </div>
</template>

