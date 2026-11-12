<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import type { Order } from '@/types';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import { Search, Eye, ShoppingCart, Trash2, Phone } from 'lucide-vue-next';

interface PaginatedOrders {
    data: Order[];
    links: { url: string | null; label: string; active: boolean }[];
    total: number;
}

const props = defineProps<{
    orders: PaginatedOrders;
    filters: { search?: string; status?: string };
}>();

const searchInput = ref(props.filters.search || '');
const selectedStatus = ref(props.filters.status || '');

function applyFilter() {
    router.get(
        '/admin/orders',
        {
            search: searchInput.value || undefined,
            status: selectedStatus.value || undefined,
        },
        { preserveState: true, preserveScroll: true }
    );
}

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
    <AdminLayout title="Kelola Pesanan Masuk">
        <Head title="Kelola Pesanan - Moodles Admin" />

        <div class="space-y-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <h2 class="font-serif font-bold text-2xl text-forest">Daftar Transaksi Pesanan</h2>
                    <p class="text-xs text-darkbrown/60">Pantau dan update status proses pesanan pelanggan Moodles</p>
                </div>
            </div>

            <!-- Filters Bar -->
            <div class="bg-white rounded-3xl border border-cream p-4 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
                <div class="w-full sm:w-80 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-darkbrown/40">
                        <Search class="w-4 h-4" />
                    </div>
                    <input
                        v-model="searchInput"
                        @keyup.enter="applyFilter"
                        type="text"
                        placeholder="Cari no. pesanan, nama, no. WA..."
                        class="w-full pl-10 pr-4 py-2 text-xs rounded-xl border-cream bg-cream/20 text-darkbrown focus:ring-forest focus:border-forest"
                    />
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <select
                        v-model="selectedStatus"
                        @change="applyFilter"
                        class="text-xs rounded-xl border-cream bg-cream/20 text-darkbrown py-2 pl-3 pr-8 focus:ring-forest focus:border-forest"
                    >
                        <option value="">Semua Status</option>
                        <option value="pending">Menunggu Konfirmasi</option>
                        <option value="confirmed">Terkonfirmasi</option>
                        <option value="processing">Sedang Diproses</option>
                        <option value="completed">Selesai</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>

                    <button
                        type="button"
                        @click="applyFilter"
                        class="px-4 py-2 rounded-xl bg-forest text-cream text-xs font-poppins font-semibold hover:bg-deep transition-colors"
                    >
                        Filter
                    </button>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="bg-white rounded-3xl border border-cream shadow-sm overflow-hidden p-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-cream/40 text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/70">
                            <tr>
                                <th class="py-3.5 px-4 rounded-l-xl">No. Pesanan</th>
                                <th class="py-3.5 px-4">Nama Pelanggan</th>
                                <th class="py-3.5 px-4">WhatsApp</th>
                                <th class="py-3.5 px-4">Item Pesanan</th>
                                <th class="py-3.5 px-4">Total</th>
                                <th class="py-3.5 px-4">Status</th>
                                <th class="py-3.5 px-4">Tanggal</th>
                                <th class="py-3.5 px-4 rounded-r-xl text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-cream/60">
                            <tr v-if="orders.data.length === 0">
                                <td colspan="8" class="py-12 text-center text-darkbrown/60 text-xs">
                                    Tidak ada data pesanan yang sesuai filter.
                                </td>
                            </tr>
                            <tr
                                v-for="order in orders.data"
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
                                    <a
                                        :href="`https://wa.me/${order.whatsapp_number.replace(/[^0-9]/g, '')}`"
                                        target="_blank"
                                        class="hover:text-forest flex items-center gap-1"
                                    >
                                        <Phone class="w-3.5 h-3.5 text-[#25D366]" />
                                        <span>{{ order.whatsapp_number }}</span>
                                    </a>
                                </td>
                                <td class="py-3.5 px-4 text-xs text-darkbrown/70">
                                    {{ order.items?.length || 0 }} varian mi
                                </td>
                                <td class="py-3.5 px-4 font-poppins font-bold text-xs text-moodles">
                                    {{ formatRupiah(order.total) }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span
                                        :class="[
                                            'px-2.5 py-0.5 rounded-full text-[10px] font-poppins font-bold uppercase border',
                                            getStatusBadge(order.status).class
                                        ]"
                                    >
                                        {{ getStatusBadge(order.status).label }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-xs font-poppins text-darkbrown/60">
                                    {{ formatDate(order.created_at) }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <Link
                                        :href="`/admin/orders/${order.id}`"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-forest hover:bg-deep text-cream text-xs font-poppins font-semibold transition-colors"
                                    >
                                        <Eye class="w-3.5 h-3.5" />
                                        <span>Kelola</span>
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <Pagination :links="orders.links" />
            </div>
        </div>
    </AdminLayout>
</template>
