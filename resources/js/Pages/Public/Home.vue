<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import type { Product, Article } from '@/types';
import { useCartStore } from '@/Stores/cart';
import { vReveal } from '@/Composables/useReveal';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import SectionHeading from '@/Components/Public/SectionHeading.vue';
import ProductGrid from '@/Components/Public/ProductGrid.vue';
import JournalCard from '@/Components/Public/JournalCard.vue';
import WhatsAppButton from '@/Components/Public/WhatsAppButton.vue';
import {
    ArrowRight,
    Leaf,
    Sparkles,
    CheckCircle2,
    Utensils,
    Flame,
    Droplets,
    Clock,
    Award,
    HeartHandshake,
    BookOpen,
    Play,
    ShieldCheck,
    ChevronDown,
    Star,
    Package,
    Store,
    X,
    ShoppingBag,
    PhoneCall,
    ExternalLink
} from 'lucide-vue-next';

interface ActivityItem {
    title: string;
    description: string;
    image: string;
    badge?: string;
}

const props = defineProps<{
    featuredProducts: Product[];
    products: Product[];
    latestArticles: Article[];
    activities: ActivityItem[];
}>();

const cartStore = useCartStore();

// State interaktif
const activeFaqIndex = ref<number | null>(0);
const showStoryModal = ref<boolean>(false);
const activeGalleryItem = ref<ActivityItem | null>(null);

function toggleFaq(index: number) {
    activeFaqIndex.value = activeFaqIndex.value === index ? null : index;
}

function openStoryModal() {
    showStoryModal.value = true;
}

function closeStoryModal() {
    showStoryModal.value = false;
}

function openGalleryModal(item: ActivityItem) {
    activeGalleryItem.value = item;
}

function closeGalleryModal() {
    activeGalleryItem.value = null;
}

// Helper untuk menemukan produk goreng & kuah
const gorengProduct = computed(() => {
    return (
        props.products.find((p) => p.variant.toLowerCase().includes('goreng') || p.slug.includes('goreng')) ||
        props.products[0]
    );
});

const kuahProduct = computed(() => {
    return (
        props.products.find((p) => p.variant.toLowerCase().includes('kuah') || p.slug.includes('kuah')) ||
        props.products[1] ||
        props.products[0]
    );
});

function handleAddToCart(product: Product) {
    if (product && product.id) {
        cartStore.addToCart(product.id, 1, true);
    }
}

// 4 Pilar Keunggulan MOCAF
const mocafPillars = [
    {
        title: 'Ramah Pencernaan & Lambung',
        description: 'Bebas gluten sintetis terigu, tidak memicu asam lambung kambuh, begah, atau kembung bagi mereka yang memiliki pencernaan sensitif.',
        icon: Leaf,
        color: 'text-olive',
        bg: 'bg-softgreen',
        badge: 'Gluten-Free MOCAF',
    },
    {
        title: 'Fermentasi Alami Terstandar',
        description: 'Proses bioteknologi asam laktat alami menghasilkan struktur mikroskopis kenyal empuk tanpa boraks maupun pengenyal sintetik.',
        icon: Sparkles,
        color: 'text-moodles',
        bg: 'bg-amber-50',
        badge: 'Bioteknologi Alami',
    },
    {
        title: 'Bumbu Rempah Segar Nusantara',
        description: 'Dipadukan bersama bawang merah lokal Banjarnegara, daun seledri segar, dan minyak rempah nabati alami non-kolesterol.',
        icon: Award,
        color: 'text-forest',
        bg: 'bg-emerald-50',
        badge: 'Rempah Asli',
    },
    {
        title: 'Kedaulatan Petani Lokal',
        description: 'Setiap kemasan Moodles turut memberdayakan kelompok tani singkong Banjarnegara dan memperkuat hilirisasi pangan nasional.',
        icon: HeartHandshake,
        color: 'text-darkbrown',
        bg: 'bg-cream',
        badge: 'Hilirisasi Riset',
    },
];

// Langkah Memasak
const cookingSteps = [
    {
        step: '01',
        title: 'Didihkan 400ml Air',
        desc: 'Panaskan air bersih dalam panci hingga mendidih bergolak. Masukkan keping mi Moodles secara perlahan.',
        icon: Flame,
    },
    {
        step: '02',
        title: 'Rebus 3 - 4 Menit',
        desc: 'Aduk sesekali hingga helai mi terurai lembut dan mengembang dengan tekstur kenyal elastis yang pas.',
        icon: Clock,
    },
    {
        step: '03',
        title: 'Campurkan Bumbu Rempah',
        desc: 'Tiriskan untuk varian goreng, atau tuang kuah bersama bumbu aromatik ke dalam mangkuk saji.',
        icon: Utensils,
    },
    {
        step: '04',
        title: 'Sajikan Selagi Hangat',
        desc: 'Tambahkan topping favorit seperti telur rebus, cabai rawit, atau sayuran segar. Nikmati kehangatannya!',
        icon: Droplets,
    },
];

// Testimoni Konsumen
const testimonials = [
    {
        quote: 'Sebagai penderita GERD kambuhan, saya sudah bertahun-tahun menghindari mi instan biasa. Moodles kuah kaldu ini penyelamat! Perut tetap adem dan kuahnya gurih alami tanpa rasa haus berlebih.',
        author: 'Dian Rahmawati',
        role: 'Ibu Rumah Tangga',
        location: 'Bandung',
        initial: 'DR',
        rating: 5,
    },
    {
        quote: 'Tekstur kenyalnya luar biasa unik, tidak lembek sama sekali. Varian Mi Goreng punya aroma bawang goreng lokal yang harum banget. Bangga produk singkong Banjarnegara kualitasnya sekelas brand artisan!',
        author: 'Agung Wicaksono',
        role: 'Pegiat Kuliner Nusantara',
        location: 'Semarang',
        initial: 'AW',
        rating: 5,
    },
    {
        quote: 'Anak-anak suka sekali makan mi instan, tapi saya khawatir dengan bahan pengawet dan glutennya. Sejak beralih ke Moodles, saya tenang menyajikannya sebagai sarapan sehat di akhir pekan.',
        author: 'Siti Lestari',
        role: 'Praktisi Pola Makan Sehat',
        location: 'Jakarta',
        initial: 'SL',
        rating: 5,
    },
];

// Daftar FAQ / QnA
const faqItems = [
    {
        question: 'Apa yang membedakan MOCAF dengan tepung singkong biasa atau tepung terigu?',
        answer: 'MOCAF (Modified Cassava Flour) diproses melalui bioteknologi fermentasi menggunakan mikroorganisme asam laktat alami. Proses ini memodifikasi struktur granula pati sehingga menghasilkan tekstur yang lebih kenyal, tidak berbau langu singkong mentah, dan memiliki elastisitas menyerupai terigu namun 100% bebas dari gluten gandum.',
    },
    {
        question: 'Apakah mi Moodles aman untuk penderita maag, GERD, atau lambung sensitif?',
        answer: 'Sangat aman. Mi Moodles dibuat dari pati singkong alami tanpa bahan pengawet sintetis (bebas boraks dan formalin), serta dikeringkan higienis tanpa minyak jenuh berlebih. Karakteristik serat MOCAF yang lembut membuatnya mudah dicerna dan tidak memicu naiknya asam lambung atau rasa kembung begah di perut.',
    },
    {
        question: 'Berapa lama masa simpan mi Moodles dan bagaimana cara penyimpanannya?',
        answer: 'Karena melalui proses pengeringan dehidrasi suhu stabil berstandar higienis, mi Moodles memiliki daya tahan 8 hingga 12 bulan dalam kemasan tertutup rapat. Simpanlah di tempat yang sejuk, kering, dan terhindar dari paparan sinar matahari langsung.',
    },
    {
        question: 'Bagaimana pengiriman paket ke luar kota atau luar Pulau Jawa?',
        answer: 'Kami melayani pengiriman ke seluruh wilayah Indonesia. Setiap paket pesanan dilindungi dengan lapisan bubble wrap tebal dan kardus pengaman kokoh agar keping mi tetap utuh sempurna selama perjalanan ekspedisi.',
    },
    {
        question: 'Apakah tersedia paket hemat atau bundling untuk keluarga dan reseller?',
        answer: 'Tentu saja! Kami menyediakan Paket Bundling Box 10 Pcs (Mix Rasa Goreng & Kuah) dengan potongan harga hemat 15%. Kami juga membuka peluang kemitraan reseller dan distributor pangan sehat dengan harga grosir khusus. Hubungi WhatsApp resmi kami untuk informasi kemitraan.',
    },
];

// Data Galeri Sentra & Riset LPPM Unsoed
const galleryBento = [
    {
        id: 1,
        title: 'Program Hilirisasi Riset LPPM Unsoed & UD Usaha Mandiri',
        subtitle: 'Sentra Riset Banjarnegara',
        description: 'Pelatihan resmi produksi mi basah dan mi kering berbasis tepung MOCAF di Banjarnegara, melahirkan standar mi sehat berkualitas tinggi.',
        image: '/assets/activities/riset_lppm_unsoed_tim.jpg',
        badge: 'Riset Prioritas LPPM',
    },
    {
        id: 2,
        title: 'Pemberdayaan Komunitas Perajin MOCAF',
        subtitle: 'Dapur Produksi Banjarnegara',
        description: 'Ibu-ibu dan mitra perajin lokal antusias memproduksi mi MOCAF bernilai tambah tinggi dengan higienitas ketat.',
        image: '/assets/activities/pelatihan_produksi_mi_mocaf.png',
        badge: 'Kemitraan UMKM',
    },
    {
        id: 3,
        title: 'Teknologi Pencetakan Mi MOCAF',
        subtitle: 'Mesin Cetak Higienis',
        description: 'Proses pencetakan helai mi lentur dengan tekstur kenyal alami tanpa boraks maupun pengenyal sintetik.',
        image: '/assets/activities/teknologi_pencetakan_mi_unsoed.jpg',
        badge: 'Teknologi Pangan',
    },
    {
        id: 4,
        title: 'Uji Organoleptik & Kepuasan Cicip Rasa',
        subtitle: 'Sesi Uji Konsumen',
        description: 'Evaluasi rasa mi goreng & kuah bersama konsumen membuktikan kelezatan rempah alami nusantara.',
        image: '/assets/activities/uji_cicip_konsumen_mi.png',
        badge: 'Uji Konsumen Terverifikasi',
    },
];
</script>

<template>
    <PublicLayout>
        <Head title="Moodles - Dari Singkong, Hadir Cara Baru Menikmati Mi" />

        <!-- ============================================================== -->
        <!-- 3. HERO SECTION (MIXED WITH REFERENSI: BENTO/CARD + DOKUMENTASI)-->
        <!-- ============================================================== -->
        <section class="relative min-h-[540px] lg:min-h-[600px] bg-deep overflow-hidden flex items-center pt-24 sm:pt-28 pb-12 lg:pb-16">
            <!-- Background Texture with Balanced Dark Overlay -->
            <div class="absolute inset-0 z-0 pointer-events-none">
                <img
                    src="/assets/hero/backgroundhero.png"
                    alt="Komposisi Dedaunan Singkong & Nuansa Alami"
                    class="w-full h-full object-cover object-center opacity-45"
                />
                <div class="absolute inset-0 bg-gradient-to-r from-[#072415]/90 via-[#0e331f]/78 to-[#092718]/65"></div>
                <!-- Radial Ambient Glowing Orbs -->
                <div class="absolute top-1/4 left-1/4 w-96 h-96 rounded-full bg-moodles/10 blur-3xl"></div>
                <div class="absolute bottom-1/4 right-1/4 w-96 h-96 rounded-full bg-olive/15 blur-3xl"></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    
                    <!-- LEFT COLUMN: EDITORIAL CONTENT & BADGES -->
                    <div class="lg:col-span-7 flex flex-col items-start text-left space-y-5 hero-enter">
                        <!-- Origin & Feature Badge -->
                        <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-[#133524]/85 border border-olive/40 backdrop-blur-md shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full bg-moodles animate-ping"></span>
                            <Leaf class="w-3.5 h-3.5 text-olive-light shrink-0" />
                            <span class="font-poppins text-[11px] font-semibold uppercase tracking-wider text-cream">
                                Inovasi Pangan Banjarnegara • 100% Bebas Gluten Sintetis
                            </span>
                        </div>

                        <!-- Headline: Poppins Bold + Playfair Italic Accent -->
                        <h1 class="font-poppins text-3xl sm:text-4xl lg:text-[42px] xl:text-[48px] font-bold text-cream leading-[1.3] tracking-tight">
                            <span class="block">Dari Singkong,</span>
                            <span class="block mt-1">Hadir Cara Baru</span>
                            <span class="block mt-1 font-serif text-moodles italic font-normal">Menikmati Mi.</span>
                        </h1>

                        <!-- Rich Subtitle Description -->
                        <p class="font-sans text-sm sm:text-base text-cream/90 max-w-xl leading-relaxed font-light">
                            Menghadirkan kelembutan dan kekenyalan sejati dari pati singkong MOCAF pilihan lereng pegunungan Banjarnegara. Diproses higienis tanpa boraks maupun pengawet sintetik, ramah lambung bagi penderita maag & GERD, dan berpadu selaras bersama rempah warisan tradisi Nusantara.
                        </p>

                        <!-- Action Buttons -->
                        <div class="flex flex-wrap items-center gap-3 pt-1 w-full sm:w-auto">
                            <Link
                                href="/produk"
                                class="inline-flex items-center gap-2.5 px-6 py-3 rounded-full bg-moodles hover:bg-moodles-hover text-white font-poppins font-bold text-xs sm:text-sm tracking-wide shadow-warm hover:shadow-hover transition-all duration-300 transform hover:-translate-y-0.5 active:scale-95 focus:outline-none group"
                            >
                                <span>Jelajahi Produk</span>
                                <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-white transition-transform duration-300 group-hover:translate-x-0.5">
                                    <ArrowRight class="w-3.5 h-3.5" />
                                </div>
                            </Link>

                            <a
                                href="#mengenal-mocaf"
                                class="inline-flex items-center gap-2 px-5 py-3 rounded-full border-2 border-cream/40 hover:border-cream text-cream hover:bg-cream/10 font-poppins font-semibold text-xs sm:text-sm transition-all duration-300 active:scale-95 focus:outline-none backdrop-blur-xs"
                            >
                                <Leaf class="w-3.5 h-3.5 text-olive-light" />
                                <span>Kenali MOCAF</span>
                            </a>
                        </div>

                        <!-- 3 Stat Highlights -->
                        <div class="pt-4 border-t border-white/15 grid grid-cols-3 gap-4 sm:gap-6 text-left w-full max-w-lg hero-enter-delay-1">
                            <div>
                                <span class="block font-poppins font-extrabold text-xl lg:text-2xl text-moodles">100%</span>
                                <span class="text-[11px] text-cream/85 font-medium leading-tight block mt-0.5">Pati Singkong Terfermentasi</span>
                            </div>
                            <div>
                                <span class="block font-poppins font-extrabold text-xl lg:text-2xl text-cream">2 Rasa</span>
                                <span class="text-[11px] text-cream/85 font-medium leading-tight block mt-0.5">Goreng Gurih & Kuah Kaldu</span>
                            </div>
                            <div>
                                <span class="block font-poppins font-extrabold text-xl lg:text-2xl text-olive-light">0%</span>
                                <span class="text-[11px] text-cream/85 font-medium leading-tight block mt-0.5">Pengawet Sintetis & Boraks</span>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN: INTERACTIVE BENTO COMPOSITE -->
                    <div class="lg:col-span-5 flex flex-col gap-3.5 hero-enter-delay-2">
                        <!-- Kartu 1: Preview Visual Produk dengan Tombol Tonton Cerita -->
                        <div class="relative rounded-3xl overflow-hidden shadow-2xl group border border-white/15 bg-forest-600/30 backdrop-blur-md h-48 sm:h-56 hover:-translate-y-1 transition-all duration-300">
                            <img
                                src="/assets/products/moodles-duo-bundling.jpg"
                                alt="Moodles Mi Goreng & Mi Kuah Banjarnegara"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-[#072415]/85 via-transparent to-transparent flex flex-col justify-end p-3.5">
                                <!-- Center Play Trigger -->
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <button
                                        type="button"
                                        @click="openStoryModal"
                                        class="w-11 h-11 rounded-full bg-white/90 hover:bg-white text-forest flex items-center justify-center shadow-lg transition-transform hover:scale-110 cursor-pointer group/btn"
                                        aria-label="Buka dokumentasi riset dan dapur sentra"
                                    >
                                        <Play class="w-4 h-4 text-forest fill-forest translate-x-0.5 group-hover/btn:text-moodles group-hover/btn:fill-moodles transition-colors" />
                                    </button>
                                </div>

                                <!-- Bottom description label -->
                                <p class="relative z-10 text-[11px] text-white/95 text-center font-medium bg-black/45 backdrop-blur-xs py-1 px-2.5 rounded-xl border border-white/10">
                                    Dua varian rasa asli siap seduh • Mitra UD. Usaha Mandiri Banjarnegara
                                </p>
                            </div>
                        </div>

                        <!-- Kartu 2: Bento Trust Card 100% Pati Singkong Terfermentasi Alami -->
                        <div class="rounded-3xl p-4 sm:p-5 bg-[#0a2316]/85 backdrop-blur-xl border border-white/15 shadow-2xl flex flex-col justify-between space-y-2.5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-olive/30 text-olive-light flex items-center justify-center shrink-0 border border-olive/30">
                                    <Leaf class="w-5 h-5 text-olive-light" />
                                </div>
                                <div>
                                    <h3 class="font-poppins font-bold text-white text-sm sm:text-base leading-snug">
                                        100% Pati Singkong MOCAF
                                    </h3>
                                    <p class="font-poppins text-xs text-olive-light font-medium leading-tight">
                                        Terfermentasi Alami & Higienis
                                    </p>
                                </div>
                            </div>

                            <p class="font-sans text-xs sm:text-sm text-cream/90 leading-relaxed">
                                Bebas gluten terigu, tanpa pengawet sintetis, dan ramah untuk pencernaan sensitif atau penderita maag & GERD.
                            </p>

                            <div class="pt-2.5 border-t border-white/10 flex items-center justify-between text-xs text-cream/70">
                                <span class="font-semibold text-moodles flex items-center gap-1">
                                    <ShieldCheck class="w-4 h-4 text-moodles" />
                                    <span>Riset LPPM Unsoed</span>
                                </span>
                                <span>Sentra Banjarnegara</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- ============================================================== -->
        <!-- 4. BRAND INTRODUCTION (FILOSOFI & KEASLIAN BAHAN)              -->
        <!-- ============================================================== -->
        <section class="py-16 bg-offwhite border-b border-cream">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                    <div v-reveal class="lg:col-span-5 relative group">
                        <div class="relative rounded-3xl overflow-hidden shadow-card border-4 border-white aspect-[4/3]">
                            <img
                                src="/assets/products/moodles-goreng-utama.jpg"
                                alt="Sajian Moodles Mi Goreng MOCAF"
                                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700"
                            />
                        </div>
                        <div class="absolute -bottom-6 -right-6 hidden sm:block w-48 rounded-2xl overflow-hidden shadow-xl border-4 border-white aspect-square animate-float-subtle">
                            <img
                                src="/assets/about/about-tekstur-mi.jpg"
                                alt="Tekstur Mi MOCAF"
                                class="w-full h-full object-cover"
                            />
                        </div>
                    </div>

                    <div v-reveal="120" class="lg:col-span-7 lg:pl-6">
                        <SectionHeading
                            tag="Filosofi Moodles"
                            title="Harmoni Rasa Nusantara dari Hasil Bumi Banjarnegara"
                            subtitle="Kami percaya bahwa inovasi pangan terbaik bermula dari kecintaan terhadap potensi tanah sendiri."
                            :compact="true"
                        />

                        <div class="font-sans text-darkbrown/80 space-y-4 text-sm sm:text-[15px] leading-[1.7]">
                            <p>
                                Mi telah menjadi bagian tak terpisahkan dari denyut kuliner harian masyarakat Indonesia. Namun hampir seluruh mi konvensional bergantung pada terigu impor. <strong>Moodles</strong> hadir mendobrak kebiasaan tersebut dengan menjadikan tepung MOCAF (<strong class="font-semibold italic">Modified Cassava Flour</strong>) dari Banjarnegara sebagai bintang utama.
                            </p>
                            <p>
                                Melalui sentuhan fermentasi alami dan racikan bumbu khas nusantara, kami membuktikan bahwa singkong dapat ditransformasikan menjadi sajian mi berkelas: kenyal alami, sedap, mudah disajikan, dan memberikan dampak nyata bagi kesejahteraan petani singkong di Jawa Tengah.
                            </p>
                        </div>

                        <div class="pt-5 flex flex-wrap gap-4">
                            <Link
                                href="/tentang-kami"
                                class="inline-flex items-center gap-2 text-forest hover:text-deep font-poppins font-bold text-sm border-b-2 border-forest pb-0.5 transition-colors"
                            >
                                <span>Pelajari Cerita Lengkap Kami</span>
                                <ArrowRight class="w-4 h-4 text-moodles" />
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================================== -->
        <!-- 5. DUA RASA UTAMA & PAKET BUNDLING (DUA MAHAKARYA RASA)        -->
        <!-- ============================================================== -->
        <section class="py-16 bg-cream/40 border-b border-cream" id="katalog-varian">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div v-reveal class="max-w-2xl mx-auto text-center mb-10 space-y-2.5">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-poppins font-semibold uppercase tracking-wider bg-softgreen text-forest border border-forest/10">
                        <span class="w-1.5 h-1.5 rounded-full bg-moodles"></span>
                        <span>Koleksi Rasa Utama</span>
                    </span>
                    <h2 class="font-poppins text-2xl sm:text-3xl font-bold text-forest leading-[1.3] tracking-tight">
                        Dua Mahakarya Rasa, Satu Keaslian Bahan Pokok
                    </h2>
                    <p class="font-sans text-darkbrown/75 text-sm sm:text-base leading-relaxed">
                        Setiap bumbu diracik dari rempah alami nusantara untuk menciptakan cita rasa rumahan yang menghangatkan dan menjaga ketenangan pencernaan Anda.
                    </p>
                </div>

                <!-- Dua Card Showcase Varian -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                    <!-- Varian 1: Mi Goreng Spesial -->
                    <div v-reveal="80" class="bg-white rounded-3xl p-6 lg:p-8 shadow-card border border-cream hover:shadow-hover hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <span class="px-3.5 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-poppins font-bold flex items-center gap-1.5">
                                    <Flame class="w-4 h-4 text-moodles" />
                                    <span>Bestseller Gurih</span>
                                </span>
                                <span class="text-xs font-poppins text-darkbrown/60 font-semibold">Netto: 85 gr</span>
                            </div>

                            <div class="w-full h-64 rounded-2xl bg-cream/30 mb-6 overflow-hidden">
                                <img
                                    src="/assets/products/moodles-goreng-utama.jpg"
                                    alt="Moodles Mi Goreng Kemasan Oranye"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                />
                            </div>

                            <div class="mb-4">
                                <h3 class="font-poppins text-xl font-bold text-forest leading-snug">
                                    Moodles Mi Goreng Spesial
                                </h3>
                                <p class="text-darkbrown/75 text-sm mt-2 leading-relaxed">
                                    Perpaduan kecap manis kental gurih, bawang merah goreng wangi khas Banjarnegara, dan minyak rempah nabati yang kaya rasa namun tetap ringan bagi lambung.
                                </p>
                            </div>

                            <div class="space-y-2 mb-6">
                                <div class="flex items-center gap-2 text-xs text-darkbrown/80 font-medium">
                                    <CheckCircle2 class="w-4 h-4 text-olive" />
                                    <span>Minyak rempah nabati alami non-kolesterol</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-darkbrown/80 font-medium">
                                    <CheckCircle2 class="w-4 h-4 text-olive" />
                                    <span>Taburan bawang merah goreng asli Banjarnegara</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-darkbrown/80 font-medium">
                                    <CheckCircle2 class="w-4 h-4 text-olive" />
                                    <span>Tekstur kenyal lembut tanpa pengenyal sintetis</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-cream flex items-center justify-between gap-4">
                            <div>
                                <p class="text-[11px] font-poppins text-darkbrown/60 uppercase font-semibold">Harga Resmi</p>
                                <p class="font-poppins text-2xl font-black text-forest">
                                    Rp 12.000 <span class="text-xs font-normal text-darkbrown/60">/ bungkus</span>
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <Link
                                    v-if="gorengProduct"
                                    :href="`/produk/${gorengProduct.slug}`"
                                    class="p-2.5 rounded-full border border-cream hover:border-forest text-forest hover:bg-forest hover:text-white transition-all text-xs font-poppins font-semibold"
                                    title="Lihat Detail Produk"
                                >
                                    Detail
                                </Link>
                                <button
                                    type="button"
                                    @click="handleAddToCart(gorengProduct)"
                                    class="inline-flex items-center gap-2 bg-moodles hover:bg-moodles-hover text-white px-5 py-2.5 rounded-full font-poppins text-sm font-bold shadow-warm hover:shadow transition-all cursor-pointer"
                                >
                                    <ShoppingBag class="w-4 h-4" />
                                    <span>Beli</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Varian 2: Mi Kuah Kaldu Bawang -->
                    <div v-reveal="160" class="bg-white rounded-3xl p-6 lg:p-8 shadow-card border border-cream hover:shadow-hover hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <span class="px-3.5 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-poppins font-bold flex items-center gap-1.5">
                                    <Droplets class="w-4 h-4 text-olive" />
                                    <span>Segar Menghangatkan</span>
                                </span>
                                <span class="text-xs font-poppins text-darkbrown/60 font-semibold">Netto: 85 gr</span>
                            </div>

                            <div class="w-full h-64 rounded-2xl bg-cream/30 mb-6 overflow-hidden">
                                <img
                                    src="/assets/products/moodles-kuah-utama.jpg"
                                    alt="Moodles Mi Kuah Kaldu Kemasan Hijau"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                />
                            </div>

                            <div class="mb-4">
                                <h3 class="font-poppins text-xl font-bold text-forest leading-snug">
                                    Moodles Mi Kuah Kaldu Bawang
                                </h3>
                                <p class="text-darkbrown/75 text-sm mt-2 leading-relaxed">
                                    Kuah kaldu bening yang sarat sari bawang putih, daun seledri segar, dan merica putih alami. Memberikan kehangatan sempurna saat cuaca dingin tanpa bikin enek.
                                </p>
                            </div>

                            <div class="space-y-2 mb-6">
                                <div class="flex items-center gap-2 text-xs text-darkbrown/80 font-medium">
                                    <CheckCircle2 class="w-4 h-4 text-olive" />
                                    <span>Kaldu sayur dan sari bawang murni</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-darkbrown/80 font-medium">
                                    <CheckCircle2 class="w-4 h-4 text-olive" />
                                    <span>Dilengkapi bubuk seledri dan lada putih alami</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-darkbrown/80 font-medium">
                                    <CheckCircle2 class="w-4 h-4 text-olive" />
                                    <span>Kuah ringan menenangkan dan tidak memicu mual</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-cream flex items-center justify-between gap-4">
                            <div>
                                <p class="text-[11px] font-poppins text-darkbrown/60 uppercase font-semibold">Harga Resmi</p>
                                <p class="font-poppins text-2xl font-black text-forest">
                                    Rp 12.000 <span class="text-xs font-normal text-darkbrown/60">/ bungkus</span>
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <Link
                                    v-if="kuahProduct"
                                    :href="`/produk/${kuahProduct.slug}`"
                                    class="p-2.5 rounded-full border border-cream hover:border-forest text-forest hover:bg-forest hover:text-white transition-all text-xs font-poppins font-semibold"
                                    title="Lihat Detail Produk"
                                >
                                    Detail
                                </Link>
                                <button
                                    type="button"
                                    @click="handleAddToCart(kuahProduct)"
                                    class="inline-flex items-center gap-2 bg-moodles hover:bg-moodles-hover text-white px-5 py-2.5 rounded-full font-poppins text-sm font-bold shadow-warm hover:shadow transition-all cursor-pointer"
                                >
                                    <ShoppingBag class="w-4 h-4" />
                                    <span>Beli</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Paket Bundling Banner -->
                <div v-reveal="100" class="bg-forest text-cream rounded-3xl p-6 lg:p-8 shadow-xl relative overflow-hidden flex flex-col lg:flex-row items-center justify-between gap-6 group">
                    <!-- Background Glow -->
                    <div class="absolute -right-10 -bottom-10 w-72 h-72 rounded-full bg-moodles/20 blur-3xl pointer-events-none"></div>

                    <div class="flex flex-col sm:flex-row items-center gap-5 relative z-10">
                        <img
                            src="/assets/products/moodles-duo-bundling.jpg"
                            alt="Paket Bundling Moodles Goreng & Kuah"
                            class="w-full sm:w-44 h-40 sm:h-32 rounded-2xl object-cover border-2 border-white/15 shadow-lg shrink-0 group-hover:scale-105 transition-transform duration-500"
                        />
                        <div class="space-y-2 max-w-lg text-center sm:text-left">
                            <div class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-olive/30 text-cream border border-olive/30 text-xs font-poppins font-semibold">
                                <Package class="w-4 h-4 text-moodles" />
                                <span>Paket Bundling Keluarga Hemat</span>
                            </div>
                            <h3 class="font-poppins text-xl sm:text-2xl font-bold text-cream leading-[1.3]">
                                Beli Paket Box 10 Bungkus (Mix Rasa Sesuai Selera)
                            </h3>
                            <p class="text-cream/85 text-sm leading-relaxed">
                                Hemat 15% dari harga normal + kemasan gift box kokoh aman untuk pengiriman ke seluruh kota di Indonesia.
                            </p>
                        </div>
                    </div>

                    <div class="shrink-0 flex items-center gap-5 relative z-10">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs text-cream/60 line-through">Rp 120.000</p>
                            <p class="font-poppins text-2xl font-black text-moodles">Rp 102.000</p>
                        </div>
                        <WhatsAppButton
                            label="Pesan Paket 10 Pcs via WA"
                            message="Halo Admin Moodles, saya ingin memesan Paket Bundling Box 10 Bungkus (Mix Rasa Goreng & Kuah) seharga promo Rp 102.000."
                            size="md"
                            variant="primary"
                        />
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================================== -->
        <!-- 6. MENGENAL MOCAF (4 PILAR BENTO GRID)                         -->
        <!-- ============================================================== -->
        <section id="mengenal-mocaf" class="py-16 bg-offwhite border-b border-cream">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div v-reveal>
                    <SectionHeading
                        tag="Edukasi Pangan"
                        title="Mengenal Tepung MOCAF Lebih Dekat"
                        subtitle="Mengapa tepung singkong termodifikasi menjadi terobosan penting bagi pangan sehat dan mandiri di Indonesia?"
                        align="center"
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div
                        v-for="(pillar, idx) in mocafPillars"
                        :key="idx"
                        v-reveal="idx * 80"
                        class="p-6 sm:p-7 rounded-3xl bg-white border border-cream shadow-sm hover:shadow-card hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between space-y-4 group"
                    >
                        <div class="space-y-3">
                            <div :class="['w-12 h-12 rounded-2xl flex items-center justify-center p-3 transition-transform duration-300 group-hover:scale-110', pillar.bg]">
                                <component :is="pillar.icon" :class="['w-6 h-6', pillar.color]" />
                            </div>
                            <h3 class="font-poppins font-bold text-lg text-forest leading-snug">{{ pillar.title }}</h3>
                            <p class="font-sans text-sm text-darkbrown/75 leading-relaxed">{{ pillar.description }}</p>
                        </div>
                        <div class="pt-3 border-t border-cream text-xs font-poppins font-semibold text-olive flex items-center gap-1.5">
                            <CheckCircle2 class="w-4 h-4 text-olive" />
                            <span>{{ pillar.badge }}</span>
                        </div>
                    </div>
                </div>

                <!-- Banner Tambahan Edukasi Riset -->
                <div v-reveal="100" class="mt-12 rounded-3xl bg-forest p-8 sm:p-10 text-cream shadow-warm flex flex-col lg:flex-row items-center justify-between gap-8">
                    <div class="space-y-2.5 max-w-2xl text-center lg:text-left">
                        <span class="text-[11px] font-poppins font-bold uppercase tracking-widest text-moodles">Riset Hilirisasi Banjarnegara</span>
                        <h3 class="font-poppins text-xl sm:text-2xl font-bold leading-[1.3]">Kaya Serat Pangan &amp; Ramah Pencernaan</h3>
                        <p class="font-sans text-cream/85 text-sm sm:text-base leading-relaxed">
                            Karakteristik tepung MOCAF yang telah difermentasi membuat rantai karbohidrat lebih bersahabat bagi usus, memberikan rasa kenyang menenangkan tanpa rasa begah berlebih.
                        </p>
                    </div>
                    <Link
                        href="/jurnal/mengenal-tepung-mocaf-banjarnegara"
                        class="inline-flex items-center gap-2 px-6 py-3.5 rounded-full bg-cream text-forest font-poppins font-bold text-xs sm:text-sm hover:bg-white hover:scale-105 transition-all shrink-0 shadow-md"
                    >
                        <span>Baca Riset MOCAF</span>
                        <BookOpen class="w-4 h-4 text-moodles" />
                    </Link>
                </div>
            </div>
        </section>

        <!-- ============================================================== -->
        <!-- 7. CERITA PENGEMBANGAN PRODUK                                  -->
        <!-- ============================================================== -->
        <section class="py-16 bg-deep text-cream relative overflow-hidden">
            <div class="absolute inset-0 opacity-15">
                <img src="/assets/banners/lanskap_hijau_ilustratif.jpg" alt="Lanskap Pertanian Banjarnegara" class="w-full h-full object-cover" />
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                    <div v-reveal class="lg:col-span-6">
                        <SectionHeading
                            tag="Perjalanan Riset"
                            title="Dari Dapur Eksperimen Menuju Meja Makan Anda"
                            subtitle="Menemukan tekstur kenyal yang pas dari tepung singkong membutuhkan dedikasi dan riset teknologi pangan berkelanjutan."
                            theme="dark"
                            :compact="true"
                        />

                        <div class="space-y-4 font-sans text-cream/85 text-sm sm:text-[15px] leading-[1.7]">
                            <p>
                                Menggantikan sebagian tepung gandum dengan MOCAF bukanlah perkara sederhana. Tanpa gluten alami dalam jumlah dominan, adonan mi rentan rapuh atau lembek bila tidak ditangani dengan formulasi yang presisi.
                            </p>
                            <p>
                                Melalui formulasi riset intensif di Banjarnegara, kami mengombinasikan MOCAF terfermentasi dengan teknik pengeringan suhu stabil. Hasilnya adalah helai mi yang lentur, mengembang indah saat diseduh, dan menyerap bumbu rempah secara mendalam.
                            </p>
                        </div>

                        <div class="pt-6 grid grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-forest/80 border border-forest-400/20 hover:border-moodles/50 transition-colors">
                                <span class="font-poppins font-bold text-lg sm:text-xl text-moodles block">Varian Goreng</span>
                                <span class="font-sans text-xs sm:text-sm text-cream/75 mt-1 block leading-relaxed">Rempah gurih nusantara dengan sentuhan kecap dan bawang merah harum.</span>
                            </div>
                            <div class="p-4 rounded-2xl bg-forest/80 border border-forest-400/20 hover:border-olive-light/50 transition-colors">
                                <span class="font-poppins font-bold text-lg sm:text-xl text-olive-light block">Varian Kuah</span>
                                <span class="font-sans text-xs sm:text-sm text-cream/75 mt-1 block leading-relaxed">Kaldu hangat menyegarkan yang sedap dinikmati di cuaca dingin.</span>
                            </div>
                        </div>
                    </div>

                    <div v-reveal="140" class="lg:col-span-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-4">
                                <div class="rounded-2xl overflow-hidden shadow-lg border border-forest-400/30">
                                    <img
                                        src="/assets/ingredients/proses_pencampuran_ilustratif.jpg"
                                        alt="Proses Pencampuran MOCAF"
                                        class="object-cover object-top aspect-square w-full hover:scale-105 transition-transform duration-500"
                                    />
                                </div>
                                <div class="rounded-2xl overflow-hidden shadow-lg border border-forest-400/30">
                                    <img
                                        src="/assets/ingredients/mie_kering_di_papan.jpg"
                                        alt="Mi Kering Hasil Produksi"
                                        class="object-cover object-center aspect-video w-full hover:scale-105 transition-transform duration-500"
                                    />
                                </div>
                            </div>
                            <div class="space-y-4 pt-8">
                                <div class="rounded-2xl overflow-hidden shadow-lg border border-forest-400/30">
                                    <img
                                        src="/assets/ingredients/proses_pencetakan_ilustratif.jpg"
                                        alt="Proses Pencetakan Mi"
                                        class="object-cover object-center aspect-video w-full hover:scale-105 transition-transform duration-500"
                                    />
                                </div>
                                <div class="rounded-2xl overflow-hidden shadow-lg border border-forest-400/30">
                                    <img
                                        src="/assets/banners/mie_diangkat_dengan_sumpit.jpg"
                                        alt="Helai Mi MOCAF Kenyal"
                                        class="object-cover object-center aspect-square w-full hover:scale-105 transition-transform duration-500"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================================== -->
        <!-- 8. GRID FOTO DOKUMENTASI ASIMETRIS (DARI SENTRA HINGGA KONSUMEN)-->
        <!-- ============================================================== -->
        <section class="py-16 bg-offwhite border-b border-cream" id="galeri-sentra">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div v-reveal class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6">
                    <div class="space-y-2.5 max-w-2xl">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-poppins font-semibold uppercase tracking-wider bg-softgreen text-forest border border-forest/10">
                            <span class="w-1.5 h-1.5 rounded-full bg-moodles"></span>
                            <span>Dokumentasi Nyata Sentra</span>
                        </span>
                        <h2 class="font-poppins text-2xl sm:text-3xl font-bold text-forest leading-[1.3] tracking-tight">
                            Dari Dapur Sentra Hingga Meja Makan Pelanggan
                        </h2>
                        <p class="font-sans text-darkbrown/75 text-sm sm:text-base leading-relaxed">
                            Bukti nyata riset hilirisasi pangan MOCAF di Banjarnegara, pelatihan perajin lokal bersama LPPM Unsoed dan UD Usaha Mandiri, hingga kehangatan santap keluarga.
                        </p>
                    </div>

                    <a
                        href="#faq"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white border border-cream hover:border-forest/30 text-forest text-sm font-poppins font-semibold shadow-sm hover:shadow transition-all shrink-0"
                    >
                        <span>Lihat Pertanyaan Umum</span>
                        <ChevronDown class="w-4 h-4 text-moodles" />
                    </a>
                </div>

                <!-- Bento Grid Asimetris (Kiri Tinggi + Kanan 1 Lebar & 2 Berdampingan) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
                    <!-- Kiri: Kartu Vertikal Tinggi (Spanduk LPPM Unsoed x UD Usaha Mandiri) -->
                    <div
                        v-reveal="60"
                        @click="openGalleryModal(galleryBento[0])"
                        class="lg:col-span-5 relative rounded-3xl overflow-hidden shadow-card hover:shadow-hover transition-all duration-500 group min-h-[460px] lg:min-h-[580px] flex flex-col justify-end cursor-pointer border border-cream"
                    >
                        <img
                            :src="galleryBento[0].image"
                            :alt="galleryBento[0].title"
                            class="absolute inset-0 w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-700"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>

                        <div class="relative z-10 m-5 p-5 rounded-2xl bg-black/50 backdrop-blur-md border border-white/15 text-white shadow-xl group-hover:-translate-y-1 transition-transform duration-300">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2.5 py-0.5 rounded-full bg-moodles text-white text-[10px] font-poppins font-bold uppercase tracking-wider">
                                    {{ galleryBento[0].badge }}
                                </span>
                                <span class="text-xs text-cream/80 font-poppins font-medium">{{ galleryBento[0].subtitle }}</span>
                            </div>
                            <h4 class="font-poppins text-xl font-bold text-white mb-1.5 leading-snug">
                                {{ galleryBento[0].title }}
                            </h4>
                            <p class="text-xs text-white/85 leading-relaxed">
                                {{ galleryBento[0].description }}
                            </p>
                            <div class="mt-3 pt-2 border-t border-white/15 flex items-center justify-between text-[11px] text-cream/70">
                                <span>Klik untuk perbesar</span>
                                <ExternalLink class="w-3.5 h-3.5 text-moodles" />
                            </div>
                        </div>
                    </div>

                    <!-- Kanan: 1 Card Lebar di Atas + 2 Card di Bawah -->
                    <div class="lg:col-span-7 flex flex-col gap-6">
                        <!-- Kanan Atas: Pelatihan Perajin & Mitra Lokal -->
                        <div
                            v-reveal="120"
                            @click="openGalleryModal(galleryBento[1])"
                            class="relative rounded-3xl overflow-hidden shadow-card hover:shadow-hover transition-all duration-500 group h-64 sm:h-72 flex flex-col justify-end cursor-pointer border border-cream"
                        >
                            <img
                                :src="galleryBento[1].image"
                                :alt="galleryBento[1].title"
                                class="absolute inset-0 w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700"
                            />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/25 to-transparent"></div>

                            <div class="relative z-10 m-4 sm:m-5 p-4 rounded-2xl bg-black/50 backdrop-blur-md border border-white/15 text-white shadow-lg group-hover:-translate-y-1 transition-transform duration-300">
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-600 text-white text-[10px] font-poppins font-bold uppercase tracking-wider">
                                        {{ galleryBento[1].badge }}
                                    </span>
                                    <span class="text-xs text-cream/80 font-poppins font-medium">{{ galleryBento[1].subtitle }}</span>
                                </div>
                                <h4 class="font-poppins text-lg font-bold text-white leading-snug mb-1">
                                    {{ galleryBento[1].title }}
                                </h4>
                                <p class="text-xs text-white/85 leading-tight line-clamp-2">
                                    {{ galleryBento[1].description }}
                                </p>
                            </div>
                        </div>

                        <!-- Kanan Bawah: 2 Card Berdampingan (Mesin Pencetak & Uji Cicip) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 flex-1">
                            <!-- Bawah 1: Mesin Pencetak Mi -->
                            <div
                                v-reveal="180"
                                @click="openGalleryModal(galleryBento[2])"
                                class="relative rounded-3xl overflow-hidden shadow-card hover:shadow-hover transition-all duration-500 group min-h-[220px] flex flex-col justify-end cursor-pointer border border-cream"
                            >
                                <img
                                    :src="galleryBento[2].image"
                                    :alt="galleryBento[2].title"
                                    class="absolute inset-0 w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500"
                                />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>

                                <div class="relative z-10 m-3.5 p-3.5 rounded-2xl bg-black/55 backdrop-blur-md border border-white/15 text-white group-hover:-translate-y-1 transition-transform duration-300">
                                    <span class="text-[10px] font-poppins font-bold text-moodles uppercase tracking-wide block mb-0.5">
                                        {{ galleryBento[2].badge }}
                                    </span>
                                    <h5 class="font-poppins text-sm font-bold text-white leading-snug">
                                        {{ galleryBento[2].title }}
                                    </h5>
                                    <p class="text-[11px] text-white/80 line-clamp-2 mt-1">
                                        {{ galleryBento[2].description }}
                                    </p>
                                </div>
                            </div>

                            <!-- Bawah 2: Uji Cicip Konsumen -->
                            <div
                                v-reveal="240"
                                @click="openGalleryModal(galleryBento[3])"
                                class="relative rounded-3xl overflow-hidden shadow-card hover:shadow-hover transition-all duration-500 group min-h-[220px] flex flex-col justify-end cursor-pointer border border-cream"
                            >
                                <img
                                    :src="galleryBento[3].image"
                                    :alt="galleryBento[3].title"
                                    class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent"></div>

                                <div class="relative z-10 m-3.5 p-3.5 rounded-2xl bg-black/55 backdrop-blur-md border border-white/15 text-white group-hover:-translate-y-1 transition-transform duration-300">
                                    <span class="text-[10px] font-poppins font-bold text-emerald-400 uppercase tracking-wide block mb-0.5">
                                        {{ galleryBento[3].badge }}
                                    </span>
                                    <h5 class="font-poppins text-sm font-bold text-white leading-snug">
                                        {{ galleryBento[3].title }}
                                    </h5>
                                    <p class="text-[11px] text-white/80 line-clamp-2 mt-1">
                                        {{ galleryBento[3].description }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================================== -->
        <!-- 9. PROSES DAN CARA PENYAJIAN (PANDUAN DAPUR PRAKTIS)           -->
        <!-- ============================================================== -->
        <section class="py-16 bg-cream/30 border-b border-cream">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div v-reveal>
                    <SectionHeading
                        tag="Panduan Praktis"
                        title="Cara Memasak Mi Moodles dengan Sempurna"
                        subtitle="Hanya butuh waktu kurang dari 5 menit untuk menyajikan santapan mi lezat bergizi di meja makan Anda."
                        align="center"
                    />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div
                        v-for="(step, idx) in cookingSteps"
                        :key="step.step"
                        v-reveal="idx * 80"
                        class="p-6 rounded-3xl bg-white border border-cream shadow-sm hover:shadow-card hover:-translate-y-1 transition-all duration-300 flex flex-col space-y-3 group"
                    >
                        <div class="flex items-center justify-between">
                            <div class="w-10 h-10 rounded-2xl bg-softgreen text-forest group-hover:bg-forest group-hover:text-moodles flex items-center justify-center transition-colors duration-300">
                                <component :is="step.icon" class="w-5 h-5" />
                            </div>
                            <span class="font-poppins font-bold text-xs text-olive uppercase tracking-wider">
                                Langkah {{ step.step }}
                            </span>
                        </div>
                        <h3 class="font-poppins font-bold text-base sm:text-lg text-forest leading-snug pt-1">
                            {{ step.title }}
                        </h3>
                        <p class="font-sans text-sm text-darkbrown/75 leading-relaxed">
                            {{ step.desc }}
                        </p>
                    </div>
                </div>

                <div v-reveal="100" class="mt-10 text-center">
                    <Link
                        href="/jurnal/tips-memasak-moodles-lebih-nikmat"
                        class="inline-flex items-center gap-2 text-sm font-poppins font-bold text-forest hover:text-deep border-b border-forest/40 pb-0.5 transition-colors"
                    >
                        <span>Lihat Tips &amp; Kreasi Topping Selengkapnya</span>
                        <ArrowRight class="w-4 h-4 text-moodles" />
                    </Link>
                </div>
            </div>
        </section>

        <!-- ============================================================== -->
        <!-- 10. TESTIMONI KONSUMEN (SUARA PENIKMAT MI MOCAF)               -->
        <!-- ============================================================== -->
        <section class="py-16 bg-offwhite border-b border-cream">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div v-reveal class="text-center max-w-2xl mx-auto mb-10 space-y-2.5">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-poppins font-semibold uppercase tracking-wider bg-softgreen text-forest border border-forest/10">
                        <span class="w-1.5 h-1.5 rounded-full bg-moodles"></span>
                        <span>Suara Penikmat</span>
                    </span>
                    <h2 class="font-poppins text-2xl sm:text-3xl font-bold text-forest leading-[1.3] tracking-tight">
                        Pengalaman Nyata Bersama Moodles
                    </h2>
                    <p class="font-sans text-darkbrown/75 text-sm sm:text-base leading-relaxed">
                        Dengarkan apa kata mereka yang telah beralih ke mi sehat berbahan dasar MOCAF Banjarnegara.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                    <div
                        v-for="(testi, idx) in testimonials"
                        :key="idx"
                        v-reveal="idx * 90"
                        class="bg-white p-6 sm:p-7 rounded-3xl shadow-card border border-cream flex flex-col justify-between hover:-translate-y-1 transition-all duration-300"
                    >
                        <div>
                            <!-- Rating Stars -->
                            <div class="flex items-center gap-1 text-moodles mb-4">
                                <Star v-for="s in testi.rating" :key="s" class="w-4 h-4 fill-moodles" />
                            </div>
                            <p class="font-sans text-sm text-darkbrown/80 italic leading-[1.7] mb-6">
                                "{{ testi.quote }}"
                            </p>
                        </div>

                        <div class="flex items-center gap-3 pt-4 border-t border-cream">
                            <div class="w-10 h-10 rounded-full bg-forest text-cream flex items-center justify-center font-poppins font-bold text-sm">
                                {{ testi.initial }}
                            </div>
                            <div>
                                <p class="font-poppins font-bold text-sm text-forest leading-tight">
                                    {{ testi.author }}
                                </p>
                                <p class="text-[11px] text-darkbrown/60 font-poppins mt-0.5">
                                    {{ testi.role }} • {{ testi.location }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================================== -->
        <!-- 11. FAQ / QnA AKORDION LENGKAP                                 -->
        <!-- ============================================================== -->
        <section class="py-16 bg-cream/30 border-b border-cream" id="faq">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div v-reveal class="text-center max-w-2xl mx-auto mb-10 space-y-2.5">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-poppins font-semibold uppercase tracking-wider bg-softgreen text-forest border border-forest/10">
                        <span class="w-1.5 h-1.5 rounded-full bg-moodles"></span>
                        <span>Tanya &amp; Jawab</span>
                    </span>
                    <h2 class="font-poppins text-2xl sm:text-3xl font-bold text-forest leading-[1.3] tracking-tight">
                        Pertanyaan yang Sering Diajukan
                    </h2>
                    <p class="font-sans text-darkbrown/75 text-sm sm:text-base leading-relaxed">
                        Semua yang perlu Anda ketahui mengenai kualitas, keamanan lambung, daya tahan simpan, dan kemudahan pemesanan Moodles.
                    </p>
                </div>

                <div class="space-y-3.5">
                    <div
                        v-for="(item, idx) in faqItems"
                        :key="idx"
                        v-reveal="idx * 60"
                        class="rounded-3xl bg-white border border-cream shadow-sm overflow-hidden transition-all duration-300"
                        :class="{ 'border-forest/30 shadow-card': activeFaqIndex === idx }"
                    >
                        <button
                            type="button"
                            @click="toggleFaq(idx)"
                            class="w-full flex items-center justify-between text-left p-5 sm:p-6 focus:outline-none cursor-pointer gap-4 hover:bg-cream/15 transition-colors"
                            :aria-expanded="activeFaqIndex === idx"
                        >
                            <span class="font-poppins font-semibold text-sm sm:text-base text-forest leading-snug">
                                {{ item.question }}
                            </span>
                            <div
                                :class="[
                                    'w-8 h-8 rounded-full flex items-center justify-center shrink-0 transition-all duration-300',
                                    activeFaqIndex === idx ? 'bg-forest text-white rotate-180' : 'bg-cream text-forest'
                                ]"
                            >
                                <ChevronDown class="w-4 h-4" />
                            </div>
                        </button>

                        <div
                            class="grid transition-all duration-300 ease-out"
                            :class="activeFaqIndex === idx ? 'grid-rows-[1fr] opacity-100' : 'grid-rows-[0fr] opacity-0'"
                        >
                            <div class="overflow-hidden">
                                <div class="px-5 pb-5 sm:px-6 sm:pb-6 font-sans text-darkbrown/80 text-sm sm:text-[15px] leading-[1.7] border-t border-cream/50 pt-4">
                                    {{ item.answer }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bantuan Lebih Lanjut -->
                <div v-reveal="100" class="mt-8 p-5 sm:p-6 rounded-2xl bg-white border border-cream text-center flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-center sm:text-left space-y-0.5">
                        <p class="font-poppins font-bold text-forest text-sm sm:text-base">Masih punya pertanyaan lain seputar Moodles?</p>
                        <p class="font-sans text-xs sm:text-sm text-darkbrown/70">Tim kami siap membantu Anda setiap hari kerja melalui WhatsApp resmi.</p>
                    </div>
                    <WhatsAppButton
                        label="Hubungi Customer Care"
                        message="Halo Tim Moodles, saya ingin bertanya seputar produk mi MOCAF."
                        size="sm"
                        variant="primary"
                    />
                </div>
            </div>
        </section>

        <!-- ============================================================== -->
        <!-- 12. JURNAL TERBARU                                             -->
        <!-- ============================================================== -->
        <section class="py-16 bg-offwhite border-b border-cream">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div v-reveal class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6">
                    <div class="space-y-2.5 max-w-2xl">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-poppins font-semibold uppercase tracking-wider bg-softgreen text-forest border border-forest/10">
                            <span class="w-1.5 h-1.5 rounded-full bg-moodles"></span>
                            <span>Edukasi &amp; Cerita</span>
                        </span>
                        <h2 class="font-poppins text-2xl sm:text-3xl font-bold text-forest leading-[1.3] tracking-tight">
                            Jurnal &amp; Inspirasi MOCAF
                        </h2>
                        <p class="font-sans text-darkbrown/75 text-sm sm:text-base leading-relaxed">
                            Artikel pilihan mengenai inovasi pangan singkong, gaya hidup sehat, dan geliat UMKM Banjarnegara.
                        </p>
                    </div>

                    <Link
                        href="/jurnal"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-white border border-cream hover:border-forest/30 text-forest text-sm font-poppins font-semibold shadow-sm hover:shadow transition-all shrink-0"
                    >
                        <span>Baca Seluruh Jurnal</span>
                        <ArrowRight class="w-4 h-4 text-moodles" />
                    </Link>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                    <div
                        v-for="(article, idx) in latestArticles"
                        :key="article.id"
                        v-reveal="idx * 90"
                    >
                        <JournalCard :article="article" />
                    </div>
                </div>
            </div>
        </section>

        <!-- ============================================================== -->
        <!-- 13. CTA PEMESANAN FINAL (CONVERSION BANNER)                    -->
        <!-- ============================================================== -->
        <section class="py-16 bg-gradient-to-br from-forest via-deep to-forest text-cream relative overflow-hidden">
            <!-- Ambient Lighting -->
            <div class="absolute -top-24 -left-24 w-80 h-80 rounded-full bg-olive/20 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-80 h-80 rounded-full bg-moodles/20 blur-3xl pointer-events-none"></div>

            <div v-reveal class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-5">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-moodles/20 border border-moodles/40 text-moodles text-[11px] font-poppins font-bold uppercase tracking-widest">
                    <span>Mulai Nikmati Sekarang</span>
                </div>

                <h2 class="font-poppins text-2xl sm:text-3xl lg:text-4xl font-bold text-cream leading-[1.3] tracking-tight">
                    Hadirkan Kelezatan Mi Sehat<br />
                    Asli Banjarnegara di Meja Makan Anda
                </h2>

                <p class="font-sans text-cream/85 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
                    Pesan langsung secara praktis melalui layanan WhatsApp resmi kami. Konsultasikan jumlah pesanan, bundling paket reseller, maupun pengiriman kilat ke alamat Anda.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-3">
                    <Link
                        href="/produk"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-3 px-9 py-4 rounded-full bg-moodles hover:bg-moodles-hover text-white font-poppins font-bold text-sm tracking-wide shadow-warm hover:shadow-hover transition-all transform hover:-translate-y-0.5 active:scale-95"
                    >
                        <span>Pilih Varian di Katalog</span>
                        <ArrowRight class="w-4 h-4 text-white" />
                    </Link>

                    <WhatsAppButton
                        label="Pesan Cepat via WhatsApp"
                        size="lg"
                        variant="secondary"
                    />
                </div>
            </div>
        </section>

        <!-- ============================================================== -->
        <!-- MODAL 1: KISAH SENTRA & RISET BANJARNEGARA                      -->
        <!-- ============================================================== -->
        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="showStoryModal"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-sm"
                @click.self="closeStoryModal"
            >
                <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-cream">
                <div class="relative h-56 sm:h-64 bg-forest">
                    <img
                        src="/assets/activities/riset_lppm_unsoed_tim.jpg"
                        alt="Program Hilirisasi Riset LPPM Unsoed"
                        class="w-full h-full object-cover object-top"
                    />
                    <div class="absolute inset-0 bg-gradient-to-t from-deep via-deep/40 to-transparent"></div>
                    <button
                        type="button"
                        @click="closeStoryModal"
                        class="absolute top-4 right-4 w-9 h-9 rounded-full bg-black/50 hover:bg-black/75 text-white flex items-center justify-center transition-colors"
                        aria-label="Tutup modal"
                    >
                        <X class="w-5 h-5" />
                    </button>
                    <div class="absolute bottom-4 left-6 right-6 text-white">
                        <span class="px-3 py-1 rounded-full bg-moodles text-white text-[11px] font-poppins font-bold uppercase tracking-wider">
                            Riset Hilirisasi Prioritas
                        </span>
                        <h3 class="font-poppins text-xl sm:text-2xl font-bold mt-1 text-cream">
                            Kisah Dapur Sentra & Riset Banjarnegara
                        </h3>
                    </div>
                </div>

                <div class="p-6 sm:p-8 space-y-4 max-h-[60vh] overflow-y-auto">
                    <p class="text-darkbrown/85 text-sm sm:text-base leading-relaxed">
                        Moodles lahir dari kolaborasi nyata <strong>Program Hilirisasi Riset Prioritas (PHRP) LPPM Universitas Jenderal Soedirman</strong> bersama mitra perajin lokal <strong>UD. Usaha Mandiri Banjarnegara</strong>.
                    </p>
                    <p class="text-darkbrown/85 text-sm sm:text-base leading-relaxed">
                        Melalui pelatihan produksi mi basah dan mi kering berbasis tepung MOCAF, para peneliti dan perajin menyempurnakan formulasi adonan agar menghasilkan mi yang kenyal alami, bernutrisi, dan berdaya simpan panjang tanpa memerlukan bahan pengawet sintetis.
                    </p>

                    <div class="grid grid-cols-2 gap-3 pt-2">
                        <div class="p-3.5 rounded-2xl bg-cream/40 border border-cream">
                            <span class="font-poppins font-bold text-forest text-xs block">Mitra Produksi</span>
                            <span class="text-darkbrown/70 text-xs">UD. Usaha Mandiri Banjarnegara</span>
                        </div>
                        <div class="p-3.5 rounded-2xl bg-cream/40 border border-cream">
                            <span class="font-poppins font-bold text-forest text-xs block">Dukungan Riset</span>
                            <span class="text-darkbrown/70 text-xs">LPPM Universitas Jenderal Soedirman</span>
                        </div>
                    </div>
                </div>

                <div class="p-5 bg-cream/30 border-t border-cream flex items-center justify-between">
                    <span class="text-xs text-darkbrown/60">Banjarnegara, Jawa Tengah</span>
                    <button
                        type="button"
                        @click="closeStoryModal"
                        class="px-5 py-2 rounded-full bg-forest text-cream font-poppins text-xs font-bold hover:bg-deep transition-colors"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
        </transition>

        <!-- ============================================================== -->
        <!-- MODAL 2: DETAIL FOTO GALERI (LIGHTBOX)                         -->
        <!-- ============================================================== -->
        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="activeGalleryItem"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
                @click.self="closeGalleryModal"
            >
                <div class="relative w-full max-w-2xl bg-white rounded-3xl shadow-2xl overflow-hidden border border-cream">
                    <div class="relative aspect-video bg-black">
                        <img
                            :src="activeGalleryItem.image"
                            :alt="activeGalleryItem.title"
                            class="w-full h-full object-contain"
                        />
                        <button
                            type="button"
                            @click="closeGalleryModal"
                            class="absolute top-4 right-4 w-9 h-9 rounded-full bg-black/60 hover:bg-black text-white flex items-center justify-center transition-colors"
                            aria-label="Tutup foto"
                        >
                            <X class="w-5 h-5" />
                        </button>
                    </div>

                    <div class="p-6 space-y-2">
                        <span class="px-3 py-1 rounded-full bg-softgreen text-forest text-[11px] font-poppins font-bold uppercase tracking-wider">
                            {{ activeGalleryItem.badge || 'Dokumentasi Asli' }}
                        </span>
                        <h3 class="font-poppins text-xl font-bold text-forest">
                            {{ activeGalleryItem.title }}
                        </h3>
                        <p class="text-darkbrown/80 text-sm leading-relaxed">
                            {{ activeGalleryItem.description }}
                        </p>
                    </div>

                    <div class="px-6 py-4 bg-cream/30 border-t border-cream flex justify-end">
                        <button
                            type="button"
                            @click="closeGalleryModal"
                            class="px-5 py-2 rounded-full bg-forest text-cream font-poppins text-xs font-bold hover:bg-deep transition-colors"
                        >
                            Selesai
                        </button>
                    </div>
                </div>
            </div>
        </transition>
    </PublicLayout>
</template>
