<script setup lang="ts">
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import type { PageProps } from '@/types';
import AdminSidebar from '@/Components/Admin/AdminSidebar.vue';
import AdminNavbar from '@/Components/Admin/AdminNavbar.vue';
import { CheckCircle, AlertCircle } from 'lucide-vue-next';

defineProps<{
    title?: string;
}>();

const isMobileSidebarOpen = ref(false);
const page = usePage<PageProps>();
const flash = computed(() => page.props.flash);
</script>

<template>
    <div class="min-h-screen bg-offwhite flex text-darkbrown font-sans selection:bg-moodles selection:text-white">
        <!-- Sidebar Backdrop for mobile -->
        <div
            v-if="isMobileSidebarOpen"
            @click="isMobileSidebarOpen = false"
            class="fixed inset-0 bg-deep/60 backdrop-blur-sm z-40 lg:hidden"
        ></div>

        <!-- Sidebar Component -->
        <AdminSidebar
            :is-mobile-open="isMobileSidebarOpen"
            @close="isMobileSidebarOpen = false"
        />

        <!-- Main Admin Wrapper -->
        <div class="flex-1 flex flex-col min-w-0 min-h-screen">
            <AdminNavbar
                :title="title"
                @toggle-sidebar="isMobileSidebarOpen = !isMobileSidebarOpen"
            />

            <!-- Flash Alert (Toast) -->
            <transition
                enter-active-class="transform ease-out duration-300 transition"
                enter-from-class="translate-y-2 opacity-0"
                enter-to-class="translate-y-0 opacity-100"
                leave-active-class="transition ease-in duration-200"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="flash?.success || flash?.error"
                    class="mx-4 sm:mx-8 mt-4 p-4 rounded-2xl border flex items-center justify-between gap-3 shadow-sm"
                    :class="flash.success ? 'bg-softgreen/80 border-olive/30 text-forest' : 'bg-red-50 border-red-200 text-red-800'"
                >
                    <div class="flex items-center gap-2.5 text-xs sm:text-sm font-medium">
                        <CheckCircle v-if="flash.success" class="w-5 h-5 text-olive shrink-0" />
                        <AlertCircle v-else class="w-5 h-5 text-red-600 shrink-0" />
                        <span>{{ flash.success || flash.error }}</span>
                    </div>
                </div>
            </transition>

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-8">
                <slot />
            </main>
        </div>
    </div>
</template>
