<script setup lang="ts">
import { computed } from 'vue';
import type { CartItem } from '@/types';
import { ShieldCheck, Truck, MessageSquare } from 'lucide-vue-next';

const props = defineProps<{
    items: CartItem[];
    subtotal: number;
    total: number;
}>();

function formatRupiah(amount: number): string {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
}

const totalWeight = computed(() => {
    return props.items.reduce((acc, curr) => acc + (curr.weight * curr.quantity), 0);
});
</script>

<template>
    <div class="bg-white rounded-3xl border border-cream p-6 sm:p-8 shadow-card space-y-6">
        <h3 class="font-poppins font-bold text-xl text-forest border-b border-cream pb-4">
            Ringkasan Belanja
        </h3>

        <!-- Items Breakdown -->
        <div class="space-y-3 max-h-80 overflow-y-auto pr-1 divide-y divide-cream/60">
            <div
                v-for="item in items"
                :key="item.product_id"
                class="pt-3 first:pt-0 flex items-center justify-between gap-3 text-sm"
            >
                <div class="flex items-center gap-3 min-w-0">
                    <img
                        :src="item.main_image || '/assets/products/moodles-goreng-utama.jpg'"
                        :alt="item.name"
                        class="w-12 h-12 rounded-xl object-cover bg-cream/30 border border-cream shrink-0"
                    />
                    <div class="min-w-0">
                        <p class="font-medium text-darkbrown truncate">{{ item.name }}</p>
                        <p class="text-xs text-olive font-poppins">
                            {{ item.quantity }} x {{ formatRupiah(item.price) }}
                        </p>
                    </div>
                </div>
                <span class="font-poppins font-bold text-forest shrink-0">
                    {{ formatRupiah(item.subtotal) }}
                </span>
            </div>
        </div>

        <!-- Calculations -->
        <div class="space-y-3 pt-4 border-t border-cream text-sm">
            <div class="flex justify-between text-darkbrown/70 font-poppins">
                <span>Total Estimasi Berat</span>
                <span class="font-semibold text-darkbrown">{{ totalWeight }} gram</span>
            </div>
            <div class="flex justify-between text-darkbrown/70 font-poppins">
                <span>Subtotal Produk</span>
                <span class="font-semibold text-darkbrown">{{ formatRupiah(subtotal) }}</span>
            </div>
            <div class="flex justify-between text-darkbrown/70 font-poppins">
                <span>Ongkos Kirim</span>
                <span class="text-olive font-semibold italic text-xs">Dikonfirmasi via WhatsApp</span>
            </div>

            <div class="flex justify-between items-baseline pt-3 border-t-2 border-cream">
                <span class="font-poppins font-bold text-lg text-forest">Total Pembayaran</span>
                <span class="font-poppins font-black text-2xl text-moodles">
                    {{ formatRupiah(total) }}
                </span>
            </div>
        </div>

        <!-- Guarantees Notice -->
        <div class="pt-4 border-t border-cream space-y-2.5 text-xs text-darkbrown/70">
            <div class="flex items-center gap-2">
                <ShieldCheck class="w-4 h-4 text-olive shrink-0" />
                <span>Mi MOCAF Asli Hasil Produksi Banjarnegara</span>
            </div>
            <div class="flex items-center gap-2">
                <MessageSquare class="w-4 h-4 text-moodles shrink-0" />
                <span>Pemesanan Langsung Terhubung ke WhatsApp Penjual</span>
            </div>
            <div class="flex items-center gap-2">
                <Truck class="w-4 h-4 text-forest shrink-0" />
                <span>Pengiriman Aman ke Seluruh Penjuru Nusantara</span>
            </div>
        </div>
    </div>
</template>
