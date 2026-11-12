<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import type { PageProps } from '@/types';
import { Phone, Mail, MapPin, ArrowUpRight } from 'lucide-vue-next';

const page = usePage<PageProps>();
const settings = page.props.site_settings || {};
const waNumber = settings.whatsapp_number || '6281234567890';
const waUrl = `https://wa.me/${waNumber}?text=${encodeURIComponent('Halo Admin Moodles Banjarnegara, saya ingin bertanya seputar produk mi MOCAF.')}`;
</script>

<template>
    <footer class="bg-deep text-cream/90 pt-14 pb-10 border-t-4 border-moodles">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Main Footer Links & Info -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-10 pb-12">
                <!-- Brand Narrative -->
                <div class="md:col-span-5 space-y-4">
                    <Link href="/" class="inline-block focus:outline-none">
                        <img
                            src="/assets/brand/moodles-logo.png"
                            alt="Moodles - Mi MOCAF Banjarnegara"
                            class="h-12 sm:h-14 w-auto object-contain drop-shadow-md"
                        />
                    </Link>
                    <p class="text-cream/80 text-sm leading-relaxed max-w-sm">
                        "Dari Singkong, Hadir Cara Baru Menikmati Mi." Moodles menghadirkan mi instan berbasis tepung MOCAF dari Banjarnegara dalam varian Goreng dan Kuah yang kenyal alami, gurih sedap, dan ramah bagi keluarga.
                    </p>
                    <div class="pt-2">
                        <a
                            :href="waUrl"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-moodles hover:bg-moodles-hover text-white text-sm font-medium transition-all shadow-md transform hover:-translate-y-0.5"
                        >
                            <Phone class="w-4 h-4" />
                            <span>Hubungi Kami via WhatsApp</span>
                            <ArrowUpRight class="w-4 h-4" />
                        </a>
                    </div>
                </div>

                <!-- Navigasi -->
                <div class="md:col-span-2 space-y-3">
                    <h5 class="font-poppins font-bold text-cream text-base tracking-wide uppercase text-xs text-moodles">Eksplorasi</h5>
                    <ul class="space-y-2 text-sm text-cream/80">
                        <li><Link href="/" class="hover:text-moodles transition-colors">Beranda</Link></li>
                        <li><Link href="/tentang-kami" class="hover:text-moodles transition-colors">Tentang Kami</Link></li>
                        <li><Link href="/produk" class="hover:text-moodles transition-colors">Katalog Mi Moodles</Link></li>
                        <li><Link href="/jurnal" class="hover:text-moodles transition-colors">Jurnal MOCAF</Link></li>
                        <li><Link href="/keranjang" class="hover:text-moodles transition-colors">Keranjang Belanja</Link></li>
                    </ul>
                </div>

                <!-- Varian Produk -->
                <div class="md:col-span-2 space-y-3">
                    <h5 class="font-poppins font-bold text-cream text-base tracking-wide uppercase text-xs text-moodles">Varian Mi</h5>
                    <ul class="space-y-2 text-sm text-cream/80">
                        <li><Link href="/produk/moodles-mi-goreng" class="hover:text-moodles transition-colors">Moodles Mi Goreng</Link></li>
                        <li><Link href="/produk/moodles-mi-kuah" class="hover:text-moodles transition-colors">Moodles Mi Kuah</Link></li>
                        <li><Link href="/produk/paket-duo-moodles" class="hover:text-moodles transition-colors">Paket Duo Kombo</Link></li>
                    </ul>
                </div>

                <!-- Kontak & Alamat -->
                <div class="md:col-span-3 space-y-3">
                    <h5 class="font-poppins font-bold text-cream text-base tracking-wide uppercase text-xs text-moodles">Lokasi & Kontak</h5>
                    <ul class="space-y-3 text-sm text-cream/80">
                        <li class="flex items-start gap-2.5">
                            <MapPin class="w-4 h-4 text-moodles shrink-0 mt-0.5" />
                            <span>{{ settings.store_address || 'Banjarnegara, Jawa Tengah, Indonesia' }}</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <Phone class="w-4 h-4 text-moodles shrink-0" />
                            <span>+{{ waNumber }}</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <Mail class="w-4 h-4 text-moodles shrink-0" />
                            <span>{{ settings.store_email || 'halo@moodles.id' }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Copyright & Admin link -->
            <div class="pt-8 mt-4 border-t border-forest-600 flex flex-col sm:flex-row items-center justify-between text-xs text-cream/60 gap-4">
                <p>&copy; {{ new Date().getFullYear() }} Moodles Banjarnegara. Seluruh hak cipta dilindungi undang-undang.</p>
                <div class="flex items-center gap-4">
                    <span>Bangga Mengolah Pangan Lokal</span>
                    <span>•</span>
                    <Link href="/admin/login" class="text-cream/50 hover:text-moodles transition-colors">
                        Panel Admin
                    </Link>
                </div>
            </div>
        </div>
    </footer>
</template>
