<script setup lang="ts">
import { onMounted } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import type { CartItem, Order } from '@/types';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import Breadcrumb from '@/Components/UI/Breadcrumb.vue';
import CartSummary from '@/Components/Public/CartSummary.vue';
import {
    Phone,
    User,
    MapPin,
    FileText,
    CheckCircle2,
    ShieldCheck,
    ArrowRight,
    ExternalLink
} from 'lucide-vue-next';

interface OrderSuccessData {
    order: Order;
    whatsappUrl: string;
}

const props = defineProps<{
    cartItems: CartItem[];
    subtotal: number;
    total: number;
    orderSuccess?: OrderSuccessData;
}>();

const form = useForm({
    customer_name: '',
    whatsapp_number: '',
    address: '',
    notes: '',
});

function submitCheckout() {
    form.post('/checkout', {
        preserveScroll: true,
    });
}

onMounted(() => {
    if (props.orderSuccess?.whatsappUrl) {
        // Auto-redirect or provide clear prominent trigger button
        window.open(props.orderSuccess.whatsappUrl, '_blank');
    }
});
</script>

<template>
    <PublicLayout>
        <Head title="Checkout Pemesanan via WhatsApp - Moodles" />

        <div class="bg-offwhite pt-24 sm:pt-28 pb-12 min-h-[600px]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 hero-enter">
                <!-- Breadcrumb -->
                <Breadcrumb
                    :items="[
                        { label: 'Keranjang', href: '/keranjang' },
                        { label: 'Checkout' }
                    ]"
                />

                <!-- SUCCESS STATE: ORDER CREATED -->
                <div v-if="orderSuccess" class="max-w-2xl mx-auto bg-white rounded-3xl border border-cream p-8 sm:p-12 shadow-card text-center space-y-6">
                    <div class="w-20 h-20 rounded-full bg-softgreen flex items-center justify-center mx-auto text-forest shadow-sm">
                        <CheckCircle2 class="w-10 h-10 text-olive" />
                    </div>

                    <div class="space-y-2">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-poppins font-bold bg-softgreen text-forest">
                            Pesanan Berhasil Disimpan
                        </span>
                        <h1 class="font-poppins font-black text-3xl sm:text-4xl text-forest">
                            Terima Kasih, {{ orderSuccess.order.customer_name }}!
                        </h1>
                        <p class="text-sm font-poppins text-darkbrown/70">
                            No. Pesanan: <strong class="text-forest font-bold">{{ orderSuccess.order.order_number }}</strong>
                        </p>
                    </div>

                    <div class="p-5 rounded-2xl bg-cream/40 border border-cream text-left text-sm text-darkbrown/80 space-y-3">
                        <p class="text-xs font-poppins uppercase tracking-wider text-olive font-bold">Langkah Selanjutnya:</p>
                        <p class="leading-relaxed">
                            Pesanan Anda telah tercatat aman di sistem kami. Klik tombol hijau di bawah ini untuk mengirimkan rincian pesanan langsung ke WhatsApp admin Moodles Banjarnegara guna verifikasi ongkos kirim dan konfirmasi pembayaran.
                        </p>
                    </div>

                    <div class="pt-2 space-y-3">
                        <a
                            :href="orderSuccess.whatsappUrl"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="w-full inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl bg-[#25D366] hover:bg-[#20ba59] text-white font-poppins font-bold text-base shadow-lg transition-all transform hover:-translate-y-0.5"
                        >
                            <Phone class="w-5 h-5 text-white" />
                            <span>Kirim Rincian ke WhatsApp Toko</span>
                            <ExternalLink class="w-4 h-4 text-white" />
                        </a>

                        <Link
                            href="/"
                            class="inline-block text-xs font-poppins font-semibold text-darkbrown/70 hover:text-forest pt-2"
                        >
                            ← Kembali ke Halaman Utama
                        </Link>
                    </div>
                </div>

                <!-- CHECKOUT FORM & SUMMARY -->
                <div v-else>
                    <div class="border-b border-cream pb-4 mb-8">
                        <h1 class="font-poppins text-3xl sm:text-4xl font-black text-forest">
                            Checkout Pemesanan
                        </h1>
                        <p class="text-sm text-darkbrown/70 font-light mt-1">
                            Lengkapi data diri dan alamat pengiriman Anda untuk diteruskan ke WhatsApp admin Moodles Banjarnegara.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                        <!-- Left: Customer Form -->
                        <div class="lg:col-span-7 bg-white rounded-3xl border border-cream shadow-sm p-6 sm:p-8 space-y-6">
                            <h2 class="font-poppins font-bold text-xl text-forest border-b border-cream pb-3 flex items-center gap-2">
                                <User class="w-5 h-5 text-moodles" />
                                <span>Informasi Pemesan &amp; Pengiriman</span>
                            </h2>

                            <form @submit.prevent="submitCheckout" class="space-y-5">
                                <!-- Nama Lengkap -->
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-darkbrown/40">
                                            <User class="w-4 h-4" />
                                        </div>
                                        <input
                                            v-model="form.customer_name"
                                            type="text"
                                            placeholder="Contoh: Budi Santoso"
                                            required
                                            class="w-full pl-10 pr-4 py-3 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown placeholder:text-darkbrown/40 focus:ring-forest focus:border-forest transition-colors"
                                        />
                                    </div>
                                    <p v-if="form.errors.customer_name" class="text-xs text-red-600 font-medium">
                                        {{ form.errors.customer_name }}
                                    </p>
                                </div>

                                <!-- Nomor WhatsApp -->
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                                        Nomor WhatsApp Aktif <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-darkbrown/40">
                                            <Phone class="w-4 h-4" />
                                        </div>
                                        <input
                                            v-model="form.whatsapp_number"
                                            type="tel"
                                            placeholder="Contoh: 081234567890"
                                            required
                                            class="w-full pl-10 pr-4 py-3 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown placeholder:text-darkbrown/40 focus:ring-forest focus:border-forest transition-colors"
                                        />
                                    </div>
                                    <span class="text-[11px] text-darkbrown/60">
                                        Pastikan nomor terdaftar di WhatsApp untuk mempermudah koordinasi pengiriman.
                                    </span>
                                    <p v-if="form.errors.whatsapp_number" class="text-xs text-red-600 font-medium">
                                        {{ form.errors.whatsapp_number }}
                                    </p>
                                </div>

                                <!-- Alamat Lengkap -->
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                                        Alamat Pengiriman Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute top-3.5 left-3.5 pointer-events-none text-darkbrown/40">
                                            <MapPin class="w-4 h-4" />
                                        </div>
                                        <textarea
                                            v-model="form.address"
                                            rows="4"
                                            placeholder="Tuliskan nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota/kabupaten, provinsi, dan kode pos..."
                                            required
                                            class="w-full pl-10 pr-4 py-3 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown placeholder:text-darkbrown/40 focus:ring-forest focus:border-forest transition-colors"
                                        ></textarea>
                                    </div>
                                    <p v-if="form.errors.address" class="text-xs text-red-600 font-medium">
                                        {{ form.errors.address }}
                                    </p>
                                </div>

                                <!-- Catatan Khusus -->
                                <div class="space-y-1.5">
                                    <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                                        Catatan Khusus (Opsional)
                                    </label>
                                    <div class="relative">
                                        <div class="absolute top-3.5 left-3.5 pointer-events-none text-darkbrown/40">
                                            <FileText class="w-4 h-4" />
                                        </div>
                                        <textarea
                                            v-model="form.notes"
                                            rows="2"
                                            placeholder="Contoh: Packing bubble wrap ekstra, titipkan ke satpam jika tidak ada orang di rumah..."
                                            class="w-full pl-10 pr-4 py-3 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown placeholder:text-darkbrown/40 focus:ring-forest focus:border-forest transition-colors"
                                        ></textarea>
                                    </div>
                                    <p v-if="form.errors.notes" class="text-xs text-red-600 font-medium">
                                        {{ form.errors.notes }}
                                    </p>
                                </div>

                                <!-- Submit Button -->
                                <div class="pt-4 border-t border-cream">
                                    <button
                                        type="submit"
                                        :disabled="form.processing"
                                        class="w-full inline-flex items-center justify-center gap-2.5 px-8 py-4 rounded-2xl bg-moodles hover:bg-moodles-hover text-white font-poppins font-bold text-base shadow-warm hover:shadow-hover transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <span v-if="form.processing">Memproses Pesanan...</span>
                                        <span v-else>Konfirmasi &amp; Lanjut ke WhatsApp Toko</span>
                                        <ArrowRight class="w-5 h-5 text-white" />
                                    </button>
                                    <p class="text-center text-[11px] text-darkbrown/60 mt-2">
                                        🔒 Data Anda disimpan aman dan tidak disebarluaskan.
                                    </p>
                                </div>
                            </form>
                        </div>

                        <!-- Right: Order Summary -->
                        <div class="lg:col-span-5">
                            <CartSummary
                                :items="cartItems"
                                :subtotal="subtotal"
                                :total="total"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
