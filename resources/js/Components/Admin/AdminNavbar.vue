<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import type { PageProps } from '@/types';
import { Menu, User, ShieldCheck } from 'lucide-vue-next';

defineProps<{
    title?: string;
}>();

const emit = defineEmits<{
    (e: 'toggleSidebar'): void;
}>();

const page = usePage<PageProps>();
const user = page.props.auth.user;
</script>

<template>
    <header class="h-20 bg-white border-b border-cream px-4 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-sm">
        <div class="flex items-center gap-3">
            <button
                type="button"
                @click="emit('toggleSidebar')"
                class="lg:hidden p-2 rounded-xl text-darkbrown hover:bg-cream/50 focus:outline-none"
            >
                <Menu class="w-6 h-6" />
            </button>
            <h1 class="font-serif font-bold text-xl sm:text-2xl text-forest">
                {{ title || 'Dashboard Admin' }}
            </h1>
        </div>

        <!-- User Info -->
        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
                <span class="block font-poppins font-semibold text-xs text-forest">
                    {{ user?.name || 'Administrator' }}
                </span>
                <span class="block text-[11px] text-darkbrown/60">
                    {{ user?.email }}
                </span>
            </div>

            <div class="w-10 h-10 rounded-2xl bg-forest text-cream flex items-center justify-center shadow-sm">
                <User class="w-5 h-5 text-moodles" />
            </div>
        </div>
    </header>
</template>
