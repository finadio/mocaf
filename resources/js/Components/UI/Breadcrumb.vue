<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronRight, Home } from 'lucide-vue-next';

interface BreadcrumbItem {
    label: string;
    href?: string;
}

defineProps<{
    items: BreadcrumbItem[];
}>();
</script>

<template>
    <nav class="flex items-center text-xs sm:text-sm font-medium text-darkbrown/60 py-3" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1.5 sm:space-x-2">
            <li class="inline-flex items-center">
                <Link href="/" class="inline-flex items-center gap-1 text-darkbrown/70 hover:text-forest transition-colors">
                    <Home class="w-3.5 h-3.5 text-olive" />
                    <span>Beranda</span>
                </Link>
            </li>

            <li v-for="(item, index) in items" :key="index" class="inline-flex items-center space-x-1.5 sm:space-x-2">
                <ChevronRight class="w-3.5 h-3.5 text-darkbrown/40 shrink-0" />
                <Link
                    v-if="item.href && index !== items.length - 1"
                    :href="item.href"
                    class="text-darkbrown/70 hover:text-forest transition-colors truncate max-w-[150px] sm:max-w-none"
                >
                    {{ item.label }}
                </Link>
                <span
                    v-else
                    class="text-forest font-semibold truncate max-w-[200px] sm:max-w-none"
                    aria-current="page"
                >
                    {{ item.label }}
                </span>
            </li>
        </ol>
    </nav>
</template>

