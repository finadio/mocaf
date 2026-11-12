<script setup lang="ts">
import type { Component } from 'vue';

defineProps<{
    title: string;
    value: string | number;
    subtext?: string;
    icon: Component;
    variant?: 'forest' | 'olive' | 'moodles' | 'amber';
}>();
</script>

<template>
    <div class="p-6 rounded-3xl bg-white border border-cream shadow-sm flex items-start justify-between gap-4">
        <div class="space-y-1">
            <span class="text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/60 block">
                {{ title }}
            </span>
            <span class="font-poppins font-black text-2xl sm:text-3xl text-forest block">
                {{ value }}
            </span>
            <span v-if="subtext" class="text-xs text-darkbrown/70 font-light block">
                {{ subtext }}
            </span>
        </div>

        <div
            :class="[
                'w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-sm',
                variant === 'moodles' ? 'bg-moodles text-white' :
                variant === 'olive' ? 'bg-olive text-white' :
                variant === 'amber' ? 'bg-amber-100 text-amber-800' :
                'bg-forest text-cream'
            ]"
        >
            <component :is="icon" class="w-6 h-6" />
        </div>
    </div>
</template>

