<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import type { Order } from '@/types';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import {
    ArrowLeft,
    Phone,
    User,
    MapPin,
    FileText,
    CheckCircle2,
    Clock,
    Trash2,
    ExternalLink
} from 'lucide-vue-next';

const props = defineProps<{
    order: Order;
}>();

const statusForm = useForm({
    status: props.order.status,
});

function updateStatus() {
    statusForm.put(`/admin/orders/${props.order.id}`, {
        preserveScroll: true,
    });
}

function deleteOrder() {
    if (confirm(`Apakah Anda yakin ingin menghapus arsip pesanan ${props.order.order_number}?`)) {
        router.delete(`/admin/orders/${props.order.id}`);
    }
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
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    }).format(d);
}

const waCustomerLink = `https://wa.me/${props.order.whatsapp_number.replace(/[^0-9]/g, '')}?text=${encodeURIComponent(`Halo ${props.order.customer_name}, kami dari Admin Moodles Banjarnegara ingin mengonfirmasi pesanan Anda dengan No. Pesanan ${props.order.order_number}.`)}`;
</script>

<template>
    <AdminLayout :title="`Pesanan: ${order.order_number}`">
        <Head :title="`Pesanan ${order.order_number} - Moodles Admin`" />

        <div class="max-w-5xl mx-auto space-y-6">
            <!-- Header Nav -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-serif font-bold text-2xl text-forest">Detail Pesanan</h2>
                    <p class="text-xs text-darkbrown/60">No. Registrasi: {{ order.order_number }} • Dibuat {{ formatDate(order.created_at) }}</p>
                </div>
                <Link
                    href="/admin/orders"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-cream text-xs font-poppins font-semibold text-darkbrown hover:text-forest transition-colors"
                >
                    <ArrowLeft class="w-4 h-4 text-moodles" />
                    <span>Daftar Pesanan</span>
                </Link>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Left: Items & Customer Details -->
                <div class="lg:col-span-8 space-y-6">
                    <!-- Customer Information Card -->
                    <div class="bg-white rounded-3xl border border-cream shadow-sm p-6 sm:p-8 space-y-4">
                        <h3 class="font-serif font-bold text-lg text-forest border-b border-cream pb-3 flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <User class="w-5 h-5 text-moodles" />
                                <span>Informasi Pemesan</span>
                            </span>
                            <a
                                :href="waCustomerLink"
                                target="_blank"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#25D366] text-white text-xs font-poppins font-semibold shadow-sm hover:bg-[#20ba59] transition-all"
                            >
                                <Phone class="w-3.5 h-3.5" />
                                <span>Hubungi di WA</span>
                                <ExternalLink class="w-3 h-3" />
                            </a>
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div class="space-y-1">
                                <span class="text-xs font-poppins font-bold uppercase text-darkbrown/50">Nama Lengkap</span>
                                <p class="font-semibold text-forest text-base">{{ order.customer_name }}</p>
                            </div>
                            <div class="space-y-1">
                                <span class="text-xs font-poppins font-bold uppercase text-darkbrown/50">Nomor WhatsApp</span>
                                <p class="font-poppins font-semibold text-forest text-base">{{ order.whatsapp_number }}</p>
                            </div>
                            <div class="sm:col-span-2 space-y-1 pt-2 border-t border-cream">
                                <span class="text-xs font-poppins font-bold uppercase text-darkbrown/50">Alamat Pengiriman</span>
                                <p class="text-darkbrown/85 whitespace-pre-line leading-relaxed">{{ order.address }}</p>
                            </div>
                            <div v-if="order.notes" class="sm:col-span-2 space-y-1 pt-2 border-t border-cream">
                                <span class="text-xs font-poppins font-bold uppercase text-darkbrown/50">Catatan Khusus Pembeli</span>
                                <p class="text-darkbrown/85 italic bg-cream/30 p-3 rounded-xl border border-cream">{{ order.notes }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Items Purchased Card -->
                    <div class="bg-white rounded-3xl border border-cream shadow-sm p-6 sm:p-8 space-y-4">
                        <h3 class="font-serif font-bold text-lg text-forest border-b border-cream pb-3">
                            Item Produk yang Dipesan
                        </h3>

                        <div class="divide-y divide-cream">
                            <div
                                v-for="item in order.items"
                                :key="item.id"
                                class="py-4 first:pt-0 last:pb-0 flex items-center justify-between gap-4"
                            >
                                <div class="space-y-1 min-w-0">
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-poppins font-bold uppercase tracking-wider bg-softgreen text-forest">
                                        Varian {{ item.variant_snapshot || 'Goreng' }}
                                    </span>
                                    <h4 class="font-bold text-forest text-sm truncate">
                                        {{ item.product_name_snapshot }}
                                    </h4>
                                    <p class="text-xs text-darkbrown/60 font-poppins">
                                        {{ item.quantity }} bungkus x {{ formatRupiah(item.price) }}
                                    </p>
                                </div>

                                <div class="text-right shrink-0">
                                    <span class="block text-[10px] font-poppins text-darkbrown/50 uppercase">Subtotal</span>
                                    <span class="font-poppins font-bold text-sm text-forest">
                                        {{ formatRupiah(item.subtotal) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Calculation summary -->
                        <div class="pt-4 border-t-2 border-cream space-y-2 text-sm">
                            <div class="flex justify-between text-darkbrown/70 font-poppins">
                                <span>Subtotal Belanja</span>
                                <span class="font-semibold text-darkbrown">{{ formatRupiah(order.subtotal) }}</span>
                            </div>
                            <div class="flex justify-between items-baseline pt-2 border-t border-cream">
                                <span class="font-serif font-bold text-base text-forest">Total Tagihan</span>
                                <span class="font-poppins font-black text-2xl text-moodles">
                                    {{ formatRupiah(order.total) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Status Update & Actions -->
                <div class="lg:col-span-4 space-y-6">
                    <!-- Update Status Box -->
                    <div class="bg-white rounded-3xl border border-cream shadow-sm p-6 space-y-4">
                        <h4 class="font-serif font-bold text-base text-forest border-b border-cream pb-3">
                            Perbarui Status Pesanan
                        </h4>

                        <form @submit.prevent="updateStatus" class="space-y-4">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                                    Status Saat Ini
                                </label>
                                <select
                                    v-model="statusForm.status"
                                    class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-xs font-poppins font-semibold text-darkbrown focus:ring-forest focus:border-forest"
                                >
                                    <option value="pending">Menunggu Konfirmasi (Pending)</option>
                                    <option value="confirmed">Pesanan Dikonfirmasi (Confirmed)</option>
                                    <option value="processing">Sedang Diproses/Dikemas (Processing)</option>
                                    <option value="completed">Pesanan Selesai (Completed)</option>
                                    <option value="cancelled">Dibatalkan (Cancelled)</option>
                                </select>
                            </div>

                            <button
                                type="submit"
                                :disabled="statusForm.processing"
                                class="w-full py-3 rounded-2xl bg-forest hover:bg-deep text-cream text-xs font-poppins font-bold shadow-card transition-all disabled:opacity-50"
                            >
                                <span v-if="statusForm.processing">Menyimpan...</span>
                                <span v-else>Update Status</span>
                            </button>
                        </form>
                    </div>

                    <!-- Delete Order Option -->
                    <div class="bg-red-50/50 rounded-3xl border border-red-100 p-6 space-y-3">
                        <h4 class="font-serif font-bold text-sm text-red-900">Hapus Data Pesanan</h4>
                        <p class="text-xs text-red-700 leading-relaxed">
                            Tindakan ini akan menghapus riwayat transaksi ini dari database. Gunakan hanya jika pesanan uji coba atau duplikat.
                        </p>
                        <button
                            type="button"
                            @click="deleteOrder"
                            class="w-full py-2.5 rounded-xl border border-red-300 text-red-700 hover:bg-red-100 text-xs font-poppins font-bold transition-colors"
                        >
                            Hapus Pesanan Ini
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
