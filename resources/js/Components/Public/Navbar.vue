<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import type { PageProps } from '@/types';
import { useCartStore } from '@/Stores/cart';
import { ShoppingBag, Menu, X, ArrowRight } from 'lucide-vue-next';

const isMobileMenuOpen = ref(false);
const isScrolled = ref(false);
const cartStore = useCartStore();
const page = usePage<PageProps>();

function handleScroll(): void {
    isScrolled.value = window.scrollY > 24;
}

onMounted(() => {
    handleScroll();
    window.addEventListener('scroll', handleScroll, { passive: true });
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

const totalItems = computed(() => {
    return page.props.cart?.total_items || 0;
});

const navLinks = [
    { name: 'Beranda', href: '/' },
    { name: 'Tentang Kami', href: '/tentang-kami' },
    { name: 'Katalog Produk', href: '/produk' },
    { name: 'Jurnal MOCAF', href: '/jurnal' },
];

function isCurrent(href: string): boolean {
    const currentPath = page.url.split('?')[0].split('#')[0];
    if (href === '/') {
        return currentPath === '/' || currentPath === '';
    }
    return currentPath.startsWith(href);
}
</script>

<template>
    <header class="sticky top-0 pt-3 sm:pt-4 z-40 px-3 sm:px-6 lg:px-8 w-full pointer-events-none transition-all duration-300 -mb-[76px] sm:-mb-[84px]">
        <div class="max-w-7xl mx-auto pointer-events-auto">
            <!-- Floating Rounded Pill Container -->
            <div
                :class="[
                    'rounded-full border px-4 sm:px-6 py-2 sm:py-2.5 flex items-center justify-between gap-4 transition-all duration-300',
                    isScrolled
                        ? 'bg-white/45 backdrop-blur-xl border-white/50 shadow-xl ring-1 ring-black/5'
                        : 'bg-white border-cream/90 shadow-lg'
                ]"
            >
                <!-- Brand Logo -->
                <Link href="/" class="flex items-center group focus:outline-none shrink-0">
                    <img
                        src="/assets/brand/moodles-logo.png"
                        alt="Moodles - Mi MOCAF Banjarnegara"
                        class="h-8 sm:h-10 w-auto object-contain transform group-hover:scale-105 transition-transform duration-300 drop-shadow-[0_1px_6px_rgba(255,255,255,0.85)]"
                    />
                </Link>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center space-x-2 lg:space-x-3">
                    <Link
                        v-for="link in navLinks"
                        :key="link.name"
                        :href="link.href"
                        :class="[
                            'text-xs sm:text-sm font-poppins transition-all duration-200 py-1.5 px-4 rounded-full focus:outline-none',
                            isCurrent(link.href)
                                ? 'bg-forest text-cream font-semibold shadow-sm hover:bg-moodles hover:text-white'
                                : isScrolled
                                    ? 'text-[#18120B] font-semibold [text-shadow:0_0_12px_rgba(255,255,255,0.95),0_1px_2px_rgba(255,255,255,0.9)] hover:bg-moodles hover:text-white hover:[text-shadow:none]'
                                    : 'text-darkbrown font-semibold hover:bg-moodles/20 hover:text-moodles-dark'
                        ]"
                    >
                        {{ link.name }}
                    </Link>
                </nav>

                <!-- Header Actions -->
                <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                    <!-- Cart Button with Counter Badge -->
                    <button
                        type="button"
                        @click="cartStore.openDrawer()"
                        class="relative p-2 rounded-full bg-cream/70 hover:bg-cream text-forest hover:text-deep transition-all transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-forest/30 cursor-pointer"
                        title="Lihat Keranjang Belanja"
                    >
                        <ShoppingBag class="w-4 h-4 sm:w-[18px] sm:h-[18px]" />
                        <span
                            v-if="totalItems > 0"
                            class="absolute -top-1 -right-1 bg-moodles text-white font-poppins text-[10px] font-bold w-4 h-4 sm:w-[18px] sm:h-[18px] rounded-full flex items-center justify-center shadow-md animate-bounce"
                        >
                            {{ totalItems }}
                        </span>
                    </button>

                    <!-- Order CTA Button -->
                    <Link
                        href="/produk"
                        class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-full bg-forest text-cream hover:bg-deep text-xs sm:text-sm font-poppins font-medium shadow-sm hover:shadow transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none"
                    >
                        <span>Pesan Sekarang</span>
                        <ArrowRight class="w-3.5 h-3.5 text-moodles" />
                    </Link>

                    <!-- Mobile Menu Toggle Button -->
                    <button
                        type="button"
                        @click="isMobileMenuOpen = !isMobileMenuOpen"
                        class="md:hidden p-2 rounded-full text-darkbrown hover:bg-cream/60 focus:outline-none cursor-pointer"
                        aria-label="Toggle Menu"
                    >
                        <Menu v-if="!isMobileMenuOpen" class="w-5 h-5 text-forest" />
                        <X v-else class="w-5 h-5 text-forest" />
                    </button>
                </div>
            </div>

            <!-- Mobile Navigation Floating Card -->
            <transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="transform -translate-y-2 opacity-0"
                enter-to-class="transform translate-y-0 opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="transform translate-y-0 opacity-100"
                leave-to-class="transform -translate-y-2 opacity-0"
            >
                <div
                    v-show="isMobileMenuOpen"
                    :class="[
                        'mt-2 rounded-3xl border px-5 pt-4 pb-6 space-y-2 shadow-xl md:hidden transition-all duration-300',
                        isScrolled
                            ? 'bg-white/60 backdrop-blur-xl border-white/50 ring-1 ring-black/5'
                            : 'bg-white/95 backdrop-blur-md border-cream/90'
                    ]"
                >
                    <Link
                        v-for="link in navLinks"
                        :key="link.name"
                        :href="link.href"
                        @click="isMobileMenuOpen = false"
                        :class="[
                            'block px-4 py-2.5 rounded-2xl text-sm font-poppins transition-colors',
                            isCurrent(link.href)
                                ? 'bg-forest text-cream font-semibold shadow-sm hover:bg-moodles hover:text-white'
                                : isScrolled
                                    ? 'text-[#18120B] font-semibold [text-shadow:0_0_10px_rgba(255,255,255,0.9)] hover:bg-moodles hover:text-white hover:[text-shadow:none]'
                                    : 'text-darkbrown font-medium hover:bg-moodles/15 hover:text-moodles-dark'
                        ]"
                    >
                        {{ link.name }}
                    </Link>

                    <div class="pt-2 border-t border-cream">
                        <Link
                            href="/produk"
                            @click="isMobileMenuOpen = false"
                            class="w-full flex items-center justify-center gap-2 py-3 rounded-full bg-forest text-cream text-sm font-medium shadow-card text-center"
                        >
                            <span>Katalog Mi Moodles</span>
                            <ArrowRight class="w-4 h-4 text-moodles" />
                        </Link>
                    </div>
                </div>
            </transition>
        </div>
    </header>
</template>
