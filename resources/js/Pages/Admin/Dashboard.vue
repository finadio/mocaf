<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import type { Order } from '@/types';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import StatCard from '@/Components/Admin/StatCard.vue';
import {
    Package,
    FolderTree,
    FileText,
    ShoppingCart,
    Clock,
    DollarSign,
    ArrowRight,
    ExternalLink,
    Eye
} from 'lucide-vue-next';

interface DashboardStats {
    active_products: number;
    total_products: number;
    categories_count: number;
    articles_count: number;
    orders_count: number;
    pending_orders: number;
    total_revenue: number;
}

const props = defineProps<{
    stats: DashboardStats;
    recentOrders: Order[];
}>();

function formatRupiah(amount: number): string {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
}

function formatDate(dateStr: string): string {
    const d = new Date(dateStr);
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    }).format(d);
}

function getStatusBadge(status: string): { label: string; class: string } {
    switch (status) {
        case 'pending':
            return { label: 'Menunggu', class: 'bg-amber-100 text-amber-800 border-amber-200' };
        case 'confirmed':
            return { label: 'Terkonfirmasi', class: 'bg-blue-100 text-blue-800 border-blue-200' };
        case 'processing':
            return { label: 'Diproses', class: 'bg-purple-100 text-purple-800 border-purple-200' };
        case 'completed':
            return { label: 'Selesai', class: 'bg-emerald-100 text-emerald-800 border-emerald-200' };
        case 'cancelled':
            return { label: 'Dibatalkan', class: 'bg-red-100 text-red-800 border-red-200' };
        default:
            return { label: status, class: 'bg-gray-100 text-gray-800 border-gray-200' };
    }
}
</script>

<template>
    <AdminLayout title="Ringkasan Operasional Moodles">
        <Head title="Dashboard Admin - Moodles" />

        <div class="space-y-8">
            <!-- Welcome Banner -->
            <div class="rounded-3xl bg-forest p-6 sm:p-8 text-cream shadow-warm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <span class="text-xs font-poppins font-bold uppercase tracking-wider text-moodles">
                        Panel Kontrol Utama
                    </span>
                    <h2 class="font-serif text-2xl sm:text-3xl font-black">
                        Selamat Datang di Moodles Admin
                    </h2>
                    <p class="text-xs sm:text-sm text-cream/80 max-w-xl font-light">
                        Kelola katalog mi MOCAF, artikel jurnal edukasi, dan pantau pesanan masuk langsung dari konsumen di Banjarnegara dan nusantara.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <Link
                        href="/admin/products/create"
                        class="px-5 py-2.5 rounded-full bg-moodles hover:bg-moodles-hover text-white text-xs font-poppins font-bold shadow-sm transition-all shrink-0"
                    >
                        + Tambah Produk
                    </Link>
                </div>
            </div>

            <!-- Stats Metric Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <StatCard
                    title="Produk Aktif"
                    :value="stats.active_products"
                    :subtext="`Total: ${stats.total_products} produk terdaftar`"
                    :icon="Package"
                    variant="forest"
                />

                <StatCard
                    title="Artikel Jurnal"
                    :value="stats.articles_count"
                    subtext="Edukasi & dokumentasi MOCAF"
                    :icon="FileText"
                    variant="olive"
                />

                <StatCard
                    title="Total Pesanan"
                    :value="stats.orders_count"
                    :subtext="`${stats.pending_orders} pesanan menunggu konfirmasi`"
                    :icon="ShoppingCart"
                    variant="amber"
                />

                <StatCard
                    title="Total Omzet"
                    :value="formatRupiah(stats.total_revenue)"
                    subtext="Pesanan sukses & diproses"
                    :icon="DollarSign"
                    variant="moodles"
                />
            </div>

            <!-- Recent Orders Section -->
            <div class="bg-white rounded-3xl border border-cream shadow-sm overflow-hidden space-y-4 p-6 sm:p-8">
                <div class="flex items-center justify-between border-b border-cream pb-4">
                    <div>
                        <h3 class="font-serif font-bold text-xl text-forest">Pesanan Masuk Terbaru</h3>
                        <p class="text-xs text-darkbrown/60">Daftar transaksi checkout via WhatsApp dari pelanggan</p>
                    </div>

                    <Link
                        href="/admin/orders"
                        class="text-xs font-poppins font-bold text-forest hover:text-moodles flex items-center gap-1 transition-colors"
                    >
                        <span>Lihat Semua Pesanan</span>
                        <ArrowRight class="w-4 h-4" />
                    </Link>
                </div>

                <!-- Orders Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-cream/40 text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/70">
                            <tr>
                                <th class="py-3 px-4 rounded-l-xl">No. Pesanan</th>
                                <th class="py-3 px-4">Nama Pelanggan</th>
                                <th class="py-3 px-4">No. WhatsApp</th>
                                <th class="py-3 px-4">Total</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Waktu</th>
                                <th class="py-3 px-4 rounded-r-xl text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-cream/60">
                            <tr v-if="recentOrders.length === 0">
                                <td colspan="7" class="py-8 text-center text-darkbrown/60 text-xs">
                                    Belum ada pesanan yang masuk.
                                </td>
                            </tr>
                            <tr
                                v-for="order in recentOrders"
                                :key="order.id"
                                class="hover:bg-cream/20 transition-colors"
                            >
                                <td class="py-3.5 px-4 font-poppins font-bold text-xs text-forest">
                                    {{ order.order_number }}
                                </td>
                                <td class="py-3.5 px-4 font-medium text-darkbrown">
                                    {{ order.customer_name }}
                                </td>
                                <td class="py-3.5 px-4 text-xs font-poppins text-darkbrown/80">
                                    {{ order.whatsapp_number }}
                                </td>
                                <td class="py-3.5 px-4 font-poppins font-bold text-xs text-moodles">
                                    {{ formatRupiah(order.total) }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span
                                        :class="[
                                            'px-2.5 py-1 rounded-full text-[11px] font-poppins font-semibold border',
                                            getStatusBadge(order.status).class
                                        ]"
                                    >
                                        {{ getStatusBadge(order.status).label }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-xs text-darkbrown/60 font-poppins">
                                    {{ formatDate(order.created_at) }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <Link
                                        :href="`/admin/orders/${order.id}`"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-forest hover:bg-deep text-cream text-xs font-poppins font-semibold transition-colors"
                                    >
                                        <Eye class="w-3.5 h-3.5" />
                                        <span>Detail</span>
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
