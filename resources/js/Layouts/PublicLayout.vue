<script setup lang="ts">
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import type { PageProps } from '@/types';
import AnnouncementBar from '@/Components/Public/AnnouncementBar.vue';
import Navbar from '@/Components/Public/Navbar.vue';
import Footer from '@/Components/Public/Footer.vue';
import CartDrawer from '@/Components/Public/CartDrawer.vue';
import { Phone, CheckCircle, AlertCircle } from 'lucide-vue-next';

const page = usePage<PageProps>();
const flash = computed(() => page.props.flash);
const settings = computed(() => page.props.site_settings || {});
const waNumber = computed(() => (settings.value.whatsapp_number || '6281234567890').replace(/[^0-9]/g, ''));
</script>

<template>
    <div class="min-h-screen flex flex-col bg-offwhite text-darkbrown font-sans selection:bg-moodles selection:text-white">
        <!-- Top Announcement Bar -->
        <AnnouncementBar />

        <!-- Main Header & Navigation -->
        <Navbar />

        <!-- Flash Messages Alert (Toast) -->
        <transition
            enter-active-class="transform ease-out duration-300 transition"
            enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="flash?.success || flash?.error"
                class="fixed bottom-20 right-4 sm:right-6 z-50 max-w-sm w-full bg-white rounded-2xl p-4 shadow-xl border flex items-start gap-3"
                :class="flash.success ? 'border-olive/30 bg-softgreen/40' : 'border-red-200 bg-red-50/70'"
            >
                <CheckCircle v-if="flash.success" class="w-5 h-5 text-olive shrink-0 mt-0.5" />
                <AlertCircle v-else class="w-5 h-5 text-red-600 shrink-0 mt-0.5" />
                <div class="flex-1 text-xs sm:text-sm font-medium" :class="flash.success ? 'text-forest' : 'text-red-800'">
                    {{ flash.success || flash.error }}
                </div>
            </div>
        </transition>

        <!-- Main Content Area -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- Slide-Over Shopping Cart Drawer -->
        <CartDrawer />

        <!-- Floating WhatsApp Contact Button (Mobile / Quick access) -->
        <a
            :href="`https://wa.me/${waNumber}?text=${encodeURIComponent('Halo Admin Moodles Banjarnegara, saya ingin informasi mengenai produk mi MOCAF.')}`"
            target="_blank"
            rel="noopener noreferrer"
            class="fixed bottom-5 right-5 z-30 p-3.5 rounded-full bg-[#25D366] text-white shadow-2xl hover:scale-110 active:scale-95 transition-all duration-300 flex items-center justify-center group focus:outline-none"
            title="Chat dengan Kami via WhatsApp"
        >
            <Phone class="w-6 h-6 text-white group-hover:animate-pulse" />
            <span class="max-w-0 overflow-hidden whitespace-nowrap group-hover:max-w-xs transition-all duration-300 ease-in-out font-poppins font-semibold text-xs px-0 group-hover:px-2">
                Chat WhatsApp
            </span>
        </a>

        <!-- Footer -->
        <Footer />
    </div>
</template>
