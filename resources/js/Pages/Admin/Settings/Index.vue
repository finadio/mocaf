<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Settings, Phone, MapPin, Mail, Sparkles, Instagram, Facebook } from 'lucide-vue-next';

const props = defineProps<{
    settings: Record<string, string>;
}>();

const form = useForm({
    settings: {
        whatsapp_number: props.settings.whatsapp_number || '6281234567890',
        store_name: props.settings.store_name || 'Moodles Banjarnegara',
        store_address: props.settings.store_address || 'Jl. Pemuda No. 45, Banjarnegara, Jawa Tengah',
        store_email: props.settings.store_email || 'halo@moodles.id',
        announcement_text: props.settings.announcement_text || '🌾 Mi Instan MOCAF Banjarnegara — Rasa Lezat, Tekstur Kenyal Alami!',
        instagram_url: props.settings.instagram_url || 'https://instagram.com/moodles.id',
        facebook_url: props.settings.facebook_url || 'https://facebook.com/moodles.id',
    },
});

function submit() {
    form.post('/admin/settings', {
        preserveScroll: true,
    });
}
</script>

<template>
    <AdminLayout title="Pengaturan Website Moodles">
        <Head title="Pengaturan Website - Moodles Admin" />

        <div class="max-w-4xl mx-auto space-y-6">
            <div>
                <h2 class="font-serif font-bold text-2xl text-forest">Pengaturan Toko &amp; Kontak</h2>
                <p class="text-xs text-darkbrown/60">Kelola nomor WhatsApp bisnis resmi, teks pengumuman, dan alamat toko</p>
            </div>

            <form @submit.prevent="submit" class="bg-white rounded-3xl border border-cream shadow-sm p-6 sm:p-8 space-y-6">
                <!-- Kontak Utama -->
                <div class="space-y-4">
                    <h3 class="font-serif font-bold text-lg text-forest border-b border-cream pb-3 flex items-center gap-2">
                        <Phone class="w-5 h-5 text-moodles" />
                        <span>Kontak WhatsApp &amp; Identitas Toko</span>
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                                Nomor WhatsApp Bisnis Moodles <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="form.settings.whatsapp_number"
                                type="text"
                                placeholder="6281234567890"
                                required
                                class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest font-poppins font-semibold"
                            />
                            <span class="text-[11px] text-darkbrown/60 block">
                                Format internasional tanpa tanda plus (+), misal: 6281234567890. Nomor ini digunakan untuk direct WhatsApp checkout.
                            </span>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                                Nama Toko / Brand
                            </label>
                            <input
                                v-model="form.settings.store_name"
                                type="text"
                                placeholder="Moodles Banjarnegara"
                                class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                            />
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                                Email Layanan Pelanggan
                            </label>
                            <input
                                v-model="form.settings.store_email"
                                type="email"
                                placeholder="halo@moodles.id"
                                class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                            />
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                                Alamat Fisik di Banjarnegara
                            </label>
                            <input
                                v-model="form.settings.store_address"
                                type="text"
                                placeholder="Banjarnegara, Jawa Tengah, Indonesia"
                                class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                            />
                        </div>
                    </div>
                </div>

                <!-- Announcement Bar & Social -->
                <div class="space-y-4 pt-4 border-t border-cream">
                    <h3 class="font-serif font-bold text-lg text-forest border-b border-cream pb-3 flex items-center gap-2">
                        <Sparkles class="w-5 h-5 text-olive" />
                        <span>Pengumuman &amp; Media Sosial</span>
                    </h3>

                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                                Teks Bar Pengumuman (Paling Atas)
                            </label>
                            <input
                                v-model="form.settings.announcement_text"
                                type="text"
                                class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                            />
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                                    Tautan Akun Instagram
                                </label>
                                <input
                                    v-model="form.settings.instagram_url"
                                    type="url"
                                    placeholder="https://instagram.com/moodles.id"
                                    class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                                />
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                                    Tautan Akun Facebook
                                </label>
                                <input
                                    v-model="form.settings.facebook_url"
                                    type="url"
                                    placeholder="https://facebook.com/moodles.id"
                                    class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-cream flex justify-end">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-8 py-3.5 rounded-2xl bg-forest hover:bg-deep text-cream text-xs font-poppins font-bold shadow-card transition-all disabled:opacity-50"
                    >
                        <span v-if="form.processing">Menyimpan...</span>
                        <span v-else>Simpan Perubahan Pengaturan</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

