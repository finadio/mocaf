<script setup lang="ts">
import type { Product } from '@/types';
import ProductCard from '@/Components/Public/ProductCard.vue';
import { vReveal } from '@/Composables/useReveal';

defineProps<{
    products: Product[];
    columns?: 2 | 3 | 4;
}>();
</script>

<template>
    <div
        :class="[
            'grid gap-6 sm:gap-8',
            columns === 2 ? 'grid-cols-1 sm:grid-cols-2 max-w-4xl mx-auto' :
            columns === 4 ? 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4' :
            'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3'
        ]"
    >
        <div
            v-for="(product, index) in products"
            :key="product.id"
            v-reveal="Math.min(index * 80, 240)"
        >
            <ProductCard :product="product" class="h-full" />
        </div>
    </div>
</template>
