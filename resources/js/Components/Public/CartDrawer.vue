<script setup lang="ts">
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import type { PageProps, CartItem } from '@/types';
import { useCartStore } from '@/Stores/cart';
import { X, Trash2, ShoppingBag, ArrowRight } from 'lucide-vue-next';
import QuantitySelector from '@/Components/Public/QuantitySelector.vue';

const cartStore = useCartStore();
const page = usePage<PageProps>();

const cart = computed(() => page.props.cart);
const items = computed<CartItem[]>(() => {
    const raw = cart.value?.items;
    if (!raw) return [];
    if (Array.isArray(raw)) return raw;
    return Object.values(raw);
});

function formatRupiah(amount: number): string {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount);
}
</script>

<template>
    <div
        v-if="cartStore.isDrawerOpen"
        class="fixed inset-0 z-50 overflow-hidden"
        aria-labelledby="slide-over-title"
        role="dialog"
        aria-modal="true"
    >
        <!-- Backdrop -->
        <div
            @click="cartStore.closeDrawer()"
            class="fixed inset-0 bg-deep/60 backdrop-blur-sm transition-opacity"
        ></div>

        <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
            <div class="w-screen max-w-md bg-offwhite shadow-2xl flex flex-col border-l border-cream">
                <!-- Drawer Header -->
                <div class="p-6 bg-cream/40 border-b border-cream flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-forest flex items-center justify-center text-cream">
                            <ShoppingBag class="w-5 h-5 text-moodles" />
                        </div>
                        <div>
                            <h3 id="slide-over-title" class="font-poppins font-bold text-lg text-forest">
                                Keranjang Belanja
                            </h3>
                            <span class="text-xs text-darkbrown/70 font-poppins">
                                {{ cart.total_items }} item dipilih
                            </span>
                        </div>
                    </div>
                    <button
                        type="button"
                        @click="cartStore.closeDrawer()"
                        class="p-2 rounded-xl text-darkbrown/60 hover:text-darkbrown hover:bg-cream/60 transition-colors focus:outline-none"
                    >
                        <X class="w-5 h-5" />
                    </button>
                </div>

                <!-- Drawer Content -->
                <div class="flex-1 overflow-y-auto p-6 divide-y divide-cream">
                    <!-- Empty State -->
                    <div v-if="items.length === 0" class="h-full flex flex-col items-center justify-center text-center py-12 space-y-4">
                        <div class="w-20 h-20 rounded-full bg-cream/60 flex items-center justify-center text-olive">
                            <ShoppingBag class="w-10 h-10 stroke-1 text-olive/60" />
                        </div>
                        <h4 class="font-poppins font-bold text-xl text-forest">Keranjang Anda Masih Kosong</h4>
                        <p class="text-sm text-darkbrown/70 max-w-xs">
                            Belum ada mi Moodles yang dipilih. Mari jelajahi varian goreng dan kuah berbahan tepung MOCAF Banjarnegara!
                        </p>
                        <button
                            type="button"
                            @click="cartStore.closeDrawer()"
                            class="mt-2 inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-forest text-cream text-sm font-medium hover:bg-deep transition-colors"
                        >
                            <span>Mulai Belanja</span>
                            <ArrowRight class="w-4 h-4 text-moodles" />
                        </button>
                    </div>

                    <!-- Items List -->
                    <div
                        v-else
                        v-for="item in items"
                        :key="item.product_id"
                        class="py-4 first:pt-0 last:pb-0 flex gap-4"
                    >
                        <img
                            :src="item.main_image || '/assets/products/moodles-goreng-utama.jpg'"
                            :alt="item.name"
                            class="w-20 h-20 rounded-2xl object-cover bg-cream/30 border border-cream shrink-0"
                        />
                        <div class="flex-1 flex flex-col justify-between">
                            <div class="flex justify-between items-start gap-2">
                                <div>
                                    <span class="text-[10px] font-poppins uppercase tracking-wider text-olive font-semibold">
                                        Varian {{ item.variant }}
                                    </span>
                                    <h4 class="font-poppins font-bold text-sm text-forest line-clamp-1">
                                        {{ item.name }}
                                    </h4>
                                    <span class="text-xs font-poppins font-bold text-moodles">
                                        {{ formatRupiah(item.price) }}
                                    </span>
                                </div>
                                <button
                                    type="button"
                                    @click="cartStore.removeItem(item.product_id)"
                                    class="text-darkbrown/40 hover:text-red-600 transition-colors p-1"
                                    title="Hapus Item"
                                >
                                    <Trash2 class="w-4 h-4" />
                                </button>
                            </div>

                            <div class="flex items-center justify-between pt-2">
                                <QuantitySelector
                                    :model-value="item.quantity"
                                    size="sm"
                                    @change="(val) => cartStore.updateQuantity(item.product_id, val)"
                                />
                                <span class="text-xs font-poppins font-bold text-forest">
                                    {{ formatRupiah(item.subtotal) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Drawer Footer -->
                <div v-if="items.length > 0" class="p-6 bg-cream/30 border-t border-cream space-y-4">
                    <div class="space-y-1.5">
                        <div class="flex justify-between text-xs text-darkbrown/70 font-poppins">
                            <span>Subtotal Belanja</span>
                            <span>{{ formatRupiah(cart.subtotal) }}</span>
                        </div>
                        <div class="flex justify-between text-base font-poppins font-bold text-forest pt-1 border-t border-cream">
                            <span>Total</span>
                            <span class="text-moodles font-poppins font-black text-xl">{{ formatRupiah(cart.total) }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <Link
                            href="/keranjang"
                            @click="cartStore.closeDrawer()"
                            class="w-full py-3 px-4 rounded-2xl border-2 border-forest text-forest hover:bg-forest hover:text-cream text-center text-xs font-poppins font-semibold transition-colors flex items-center justify-center"
                        >
                            Lihat Keranjang
                        </Link>
                        <Link
                            href="/checkout"
                            @click="cartStore.closeDrawer()"
                            class="w-full py-3 px-4 rounded-2xl bg-moodles hover:bg-moodles-hover text-white text-center text-xs font-poppins font-bold shadow-card transition-all flex items-center justify-center gap-1.5"
                        >
                            <span>Checkout WA</span>
                            <ArrowRight class="w-3.5 h-3.5" />
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
