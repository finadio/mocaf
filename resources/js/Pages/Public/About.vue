<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { vReveal } from '@/Composables/useReveal';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import Breadcrumb from '@/Components/UI/Breadcrumb.vue';
import SectionHeading from '@/Components/Public/SectionHeading.vue';
import { ArrowRight, Heart, ShieldCheck, Users, Sprout, FlaskConical, Award, Rocket } from 'lucide-vue-next';

interface Milestone {
    year: string;
    title: string;
    description: string;
}

defineProps<{
    milestones: Milestone[];
}>();

const activeMilestone = ref(0);
const milestoneIcons = [Sprout, FlaskConical, Award, Rocket];
</script>

<template>
    <PublicLayout>
        <Head title="Tentang Kami - Kisah & Filosofi Moodles" />

        <!-- Header Hero -->
        <section class="bg-forest text-cream pt-24 sm:pt-28 pb-12 sm:pb-16 border-b-4 border-moodles relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-3.5 hero-enter">
                <Breadcrumb :items="[{ label: 'Tentang Kami' }]" class="text-cream/70" />
                <h1 class="font-poppins text-3xl sm:text-4xl font-bold text-cream leading-[1.25] tracking-tight">
                    Mengenal Moodles &amp;<br />
                    Kisah Dari Banjarnegara
                </h1>
                <p class="font-sans text-sm sm:text-base text-cream/85 max-w-2xl leading-relaxed">
                    Sebuah dedikasi untuk mengangkat derajat singkong lokal menjadi sajian mi modern bernilai tinggi, menyehatkan, dan membanggakan nusantara.
                </p>
            </div>
        </section>

        <!-- Main Narrative Section -->
        <section class="py-16 bg-offwhite border-b border-cream">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                    <div v-reveal class="lg:col-span-6">
                        <SectionHeading
                            tag="Asal Usul"
                            title="Mengubah Komoditas Rakyat Menjadi Mahakarya Kuliner"
                            subtitle="Dari bumi subur Banjarnegara, Jawa Tengah, kami melihat peluang besar yang belum tergarap maksimal."
                            :compact="true"
                        />

                        <div class="space-y-4 font-sans text-darkbrown/80 text-sm sm:text-[15px] leading-[1.7]">
                            <p>
                                Singkong telah lama menjadi tanaman andalan para petani di Banjarnegara. Namun selama berpuluh tahun, singkong kerap hanya dipandang sebagai komoditas sampingan atau diolah menjadi makanan tradisional dengan nilai tambah yang terbatas.
                            </p>
                            <p>
                                Melalui kehadiran teknologi <strong>MOCAF (Modified Cassava Flour)</strong>, kami melihat jalan terang untuk mewujudkan kemandirian pangan. Tepung singkong termodifikasi memiliki sifat fisik yang mendekati terigu: berwarna putih bersih, bebas gluten tinggi, serta bertekstur lembut dan elastis.
                            </p>
                            <p>
                                <strong>Moodles</strong> lahir dari semangat kolaborasi antara riset pangan terapan dan kegigihan petani lokal, menghadirkan mi instan lezat yang mengharumkan nama daerah di kancah nasional.
                            </p>
                        </div>
                    </div>

                    <div v-reveal="120" class="lg:col-span-6 grid grid-cols-2 gap-4 items-start">
                        <div class="space-y-3">
                            <img
                                src="/assets/activities/riset_lppm_unsoed_tim.jpg"
                                alt="Program Hilirisasi Riset LPPM Unsoed & UD Usaha Mandiri"
                                class="rounded-3xl shadow-card object-cover object-top aspect-square w-full border-4 border-white hover:scale-[1.02] transition-transform duration-500"
                            />
                            <div class="p-3.5 rounded-2xl bg-white border border-cream text-xs text-darkbrown/80 font-medium leading-relaxed">
                                Sinergi akademisi dan mitra UMKM sentra Banjarnegara.
                            </div>
                        </div>
                        <div class="space-y-3 pt-6">
                            <img
                                src="/assets/activities/tim_riset_dan_produk_kemasan.png"
                                alt="Tim Riset dan Kemasan Moodles Banjarnegara"
                                class="rounded-3xl shadow-card object-cover aspect-square w-full border-4 border-white hover:scale-[1.02] transition-transform duration-500"
                            />
                            <div class="p-3.5 rounded-2xl bg-white border border-cream text-xs text-darkbrown/80 font-medium leading-relaxed">
                                Dedikasi menghadirkan pangan sehat mandiri.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Milestones Section -->
        <section class="py-16 bg-gradient-to-b from-cream/50 via-cream/30 to-offwhite border-b border-cream relative overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div v-reveal>
                    <SectionHeading
                        tag="Lini Masa"
                        title="Perjalanan Riset &amp; Dedikasi Kami"
                        subtitle="Setiap langkah adalah komitmen untuk menghasilkan mi berbasis MOCAF dengan kualitas terbaik."
                        align="center"
                    />
                </div>

                <!-- Interactive Connected Timeline Wrapper -->
                <div class="relative mt-4">
                    <!-- Desktop Horizontal Connector Track -->
                    <div
                        class="hidden lg:block absolute top-6 left-[12.5%] right-[12.5%] h-1 rounded-full bg-forest/10 overflow-hidden z-0"
                        aria-hidden="true"
                    >
                        <div
                            class="h-full bg-gradient-to-r from-forest via-olive to-moodles rounded-full transition-all duration-500 ease-out"
                            :style="{
                                width: milestones.length > 1
                                    ? `${(activeMilestone / (milestones.length - 1)) * 100}%`
                                    : '100%'
                            }"
                        ></div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 relative z-10">
                        <button
                            v-for="(item, idx) in milestones"
                            :key="idx"
                            type="button"
                            v-reveal="idx * 80"
                            @mouseenter="activeMilestone = idx"
                            @click="activeMilestone = idx"
                            class="group text-left flex flex-col items-stretch focus:outline-none cursor-pointer"
                        >
                            <!-- Timeline Step Node -->
                            <div class="flex items-center lg:justify-center mb-4 gap-3 lg:gap-0">
                                <div
                                    :class="[
                                        'w-12 h-12 rounded-2xl border-2 flex items-center justify-center font-poppins font-bold text-sm transition-all duration-300 shadow-sm shrink-0',
                                        activeMilestone === idx
                                            ? 'bg-moodles border-white text-white scale-110 shadow-warm'
                                            : idx < activeMilestone
                                                ? 'bg-forest border-white text-cream'
                                                : 'bg-white border-cream text-forest group-hover:border-moodles/50 group-hover:scale-105'
                                    ]"
                                >
                                    0{{ idx + 1 }}
                                </div>
                                <span class="lg:hidden text-xs font-poppins font-bold uppercase tracking-wider text-olive/80">
                                    Langkah 0{{ idx + 1 }}
                                </span>
                            </div>

                            <!-- Milestone Card -->
                            <div
                                :class="[
                                    'relative flex-1 p-6 rounded-3xl bg-white border transition-all duration-300 flex flex-col justify-between overflow-hidden',
                                    activeMilestone === idx
                                        ? 'border-moodles/50 shadow-card -translate-y-1.5 ring-1 ring-moodles/15'
                                        : 'border-cream shadow-sm hover:shadow-card hover:-translate-y-1'
                                ]"
                            >
                                <!-- Top Subtle Accent Bar -->
                                <div
                                    :class="[
                                        'absolute top-0 inset-x-0 h-1 transition-all duration-300',
                                        activeMilestone === idx
                                            ? 'bg-gradient-to-r from-forest via-moodles to-olive opacity-100'
                                            : 'bg-forest/15 opacity-0 group-hover:opacity-100'
                                    ]"
                                ></div>

                                <!-- Watermark Number -->
                                <span
                                    aria-hidden="true"
                                    :class="[
                                        'absolute -top-2 right-4 font-poppins font-black text-5xl select-none transition-colors duration-300 pointer-events-none',
                                        activeMilestone === idx
                                            ? 'text-moodles/15'
                                            : 'text-forest/[0.05] group-hover:text-forest/[0.09]'
                                    ]"
                                >
                                    0{{ idx + 1 }}
                                </span>

                                <div class="space-y-3 relative z-10">
                                    <div class="flex items-center gap-2.5">
                                        <div
                                            :class="[
                                                'w-9 h-9 rounded-xl flex items-center justify-center transition-colors duration-300 shrink-0',
                                                activeMilestone === idx
                                                    ? 'bg-moodles/15 text-moodles-dark'
                                                    : 'bg-softgreen text-forest group-hover:bg-moodles/10 group-hover:text-moodles-dark'
                                            ]"
                                        >
                                            <component :is="milestoneIcons[idx % milestoneIcons.length]" class="w-4 h-4" />
                                        </div>
                                        <span
                                            :class="[
                                                'inline-block px-3 py-1 rounded-full text-[11px] font-poppins font-bold transition-colors duration-300',
                                                activeMilestone === idx
                                                    ? 'bg-forest text-cream'
                                                    : 'bg-softgreen text-forest'
                                            ]"
                                        >
                                            {{ item.year }}
                                        </span>
                                    </div>

                                    <h3 class="font-poppins font-bold text-base sm:text-lg text-forest leading-snug">
                                        {{ item.title }}
                                    </h3>
                                    <p class="font-sans text-sm text-darkbrown/75 leading-relaxed">
                                        {{ item.description }}
                                    </p>
                                </div>

                                <!-- Bottom Stage Footer -->
                                <div class="mt-5 pt-3 border-t border-cream/80 flex items-center justify-between text-[11px] font-poppins font-semibold">
                                    <span :class="activeMilestone === idx ? 'text-moodles-dark' : 'text-darkbrown/45'">
                                        Tahap 0{{ idx + 1 }} / 0{{ milestones.length }}
                                    </span>
                                    <span
                                        :class="[
                                            'w-2 h-2 rounded-full transition-all duration-300',
                                            activeMilestone === idx ? 'bg-moodles scale-125' : 'bg-cream'
                                        ]"
                                    ></span>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Values Section -->
        <section class="py-16 bg-offwhite border-b border-cream">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div v-reveal>
                    <SectionHeading
                        tag="Nilai Kami"
                        title="Prinsip yang Selalu Kami Pegang Teguh"
                        subtitle="Nilai dasar yang menjaga standar kualitas pangan lokal dari Banjarnegara untuk keluarga Indonesia."
                        align="center"
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                    <div
                        v-reveal="0"
                        class="group p-7 rounded-3xl bg-white border border-cream shadow-sm hover:shadow-card hover:-translate-y-1 transition-all duration-300 space-y-3.5"
                    >
                        <div class="w-11 h-11 rounded-2xl bg-forest text-moodles flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                            <ShieldCheck class="w-5 h-5" />
                        </div>
                        <h4 class="font-poppins font-bold text-lg text-forest leading-snug">Keaslian &amp; Transparansi</h4>
                        <p class="font-sans text-sm text-darkbrown/75 leading-relaxed">
                            Kami menyajikan produk dengan informasi bahan baku yang jujur tanpa klaim berlebihan yang belum teruji secara laboratorium.
                        </p>
                    </div>

                    <div
                        v-reveal="100"
                        class="group p-7 rounded-3xl bg-white border border-cream shadow-sm hover:shadow-card hover:-translate-y-1 transition-all duration-300 space-y-3.5"
                    >
                        <div class="w-11 h-11 rounded-2xl bg-olive text-white flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                            <Users class="w-5 h-5" />
                        </div>
                        <h4 class="font-poppins font-bold text-lg text-forest leading-snug">Pemberdayaan Petani</h4>
                        <p class="font-sans text-sm text-darkbrown/75 leading-relaxed">
                            Menjaga rantai pasok yang adil dengan menyerap hasil panen singkong petani Banjarnegara pada tingkat harga yang stabil dan menyejahterakan.
                        </p>
                    </div>

                    <div
                        v-reveal="200"
                        class="group p-7 rounded-3xl bg-white border border-cream shadow-sm hover:shadow-card hover:-translate-y-1 transition-all duration-300 space-y-3.5"
                    >
                        <div class="w-11 h-11 rounded-2xl bg-moodles text-white flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                            <Heart class="w-5 h-5" />
                        </div>
                        <h4 class="font-poppins font-bold text-lg text-forest leading-snug">Cita Rasa yang Dicintai</h4>
                        <p class="font-sans text-sm text-darkbrown/75 leading-relaxed">
                            Bagi kami, makanan sehat tetap harus memanjakan lidah. Kami meracik bumbu rempah nusantara agar setiap suapan mi menghadirkan kepuasan rasa.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-16 bg-cream text-center">
            <div v-reveal class="max-w-2xl mx-auto px-4 space-y-4">
                <h3 class="font-poppins text-2xl sm:text-3xl font-bold text-forest leading-[1.3]">
                    Coba Sensasi Mi MOCAF Banjarnegara Hari Ini
                </h3>
                <p class="font-sans text-darkbrown/80 text-sm sm:text-base leading-relaxed">
                    Nikmati sajian mi kenyal alami dalam dua varian pilihan kami: Mi Goreng dan Mi Kuah.
                </p>
                <div class="pt-3">
                    <Link
                        href="/produk"
                        class="group inline-flex items-center gap-2 px-7 py-3.5 rounded-full bg-forest text-cream hover:bg-deep hover:-translate-y-0.5 font-poppins font-bold text-xs sm:text-sm shadow-card transition-all duration-300"
                    >
                        <span>Lihat Katalog Produk Moodles</span>
                        <ArrowRight class="w-4 h-4 text-moodles group-hover:translate-x-1 transition-transform duration-300" />
                    </Link>
                </div>
            </div>
        </section>
    </PublicLayout>
</template>
