<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import type { PageProps } from '@/types';
import {
    LayoutDashboard,
    Package,
    FolderTree,
    FileText,
    ShoppingCart,
    Image,
    Settings,
    LogOut,
    ExternalLink,
    X
} from 'lucide-vue-next';

defineProps<{
    isMobileOpen?: boolean;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
}>();

const page = usePage<PageProps>();

const navigation = [
    { name: 'Dashboard', href: '/admin/dashboard', icon: LayoutDashboard },
    { name: 'Kelola Produk', href: '/admin/products', icon: Package },
    { name: 'Kelola Kategori', href: '/admin/categories', icon: FolderTree },
    { name: 'Kelola Artikel', href: '/admin/articles', icon: FileText },
    { name: 'Kelola Pesanan', href: '/admin/orders', icon: ShoppingCart },
    { name: 'Pengelolaan Media', href: '/admin/media', icon: Image },
    { name: 'Pengaturan Website', href: '/admin/settings', icon: Settings },
];

function isCurrent(href: string): boolean {
    return page.url.startsWith(href);
}
</script>

<template>
    <!-- Desktop Sidebar -->
    <aside
        :class="[
            'fixed inset-y-0 left-0 z-50 w-64 bg-forest text-cream flex flex-col justify-between transition-transform duration-300 lg:translate-x-0 lg:static',
            isMobileOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full'
        ]"
    >
        <div class="flex-1 flex flex-col min-h-0">
            <!-- Sidebar Header & Logo -->
            <div class="h-20 flex items-center justify-between px-6 border-b border-forest-600 bg-deep/50">
                <Link href="/admin/dashboard" class="flex items-center gap-3">
                    <img
                        src="/assets/brand/moodles-logo.png"
                        alt="Moodles Admin"
                        class="h-10 w-auto object-contain drop-shadow-sm"
                    />
                    <div>
                        <span class="block text-[11px] font-poppins text-olive-light uppercase tracking-wider font-semibold">
                            Admin Panel
                        </span>
                    </div>
                </Link>

                <button
                    type="button"
                    @click="emit('close')"
                    class="lg:hidden p-1.5 rounded-xl text-cream/70 hover:text-white hover:bg-forest-600 focus:outline-none"
                >
                    <X class="w-5 h-5" />
                </button>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
                <Link
                    v-for="item in navigation"
                    :key="item.name"
                    :href="item.href"
                    @click="emit('close')"
                    :class="[
                        'flex items-center gap-3 px-4 py-3 rounded-2xl text-xs font-poppins font-semibold transition-all duration-200 focus:outline-none',
                        isCurrent(item.href)
                            ? 'bg-moodles text-white shadow-card translate-x-1'
                            : 'text-cream/80 hover:bg-forest-600/70 hover:text-cream'
                    ]"
                >
                    <component :is="item.icon" class="w-4 h-4 shrink-0" />
                    <span>{{ item.name }}</span>
                </Link>
            </nav>
        </div>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-forest-600 bg-deep/40 space-y-2">
            <!-- Link to Public Website -->
            <Link
                href="/"
                target="_blank"
                class="flex items-center justify-between px-4 py-2.5 rounded-2xl text-xs font-poppins font-medium text-cream/70 hover:text-white hover:bg-forest-600/50 transition-colors"
            >
                <span class="flex items-center gap-2">
                    <ExternalLink class="w-3.5 h-3.5 text-moodles" />
                    <span>Lihat Toko Publik</span>
                </span>
                <span class="text-[10px] font-bold text-olive-light">↗</span>
            </Link>

            <!-- Logout Button -->
            <Link
                href="/admin/logout"
                method="post"
                as="button"
                class="w-full flex items-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-poppins font-semibold text-red-300 hover:text-red-200 hover:bg-red-950/40 transition-colors text-left focus:outline-none"
            >
                <LogOut class="w-4 h-4 text-red-400" />
                <span>Keluar (Logout)</span>
            </Link>
        </div>
    </aside>
</template>
