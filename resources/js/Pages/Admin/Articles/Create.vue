<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ArrowLeft } from 'lucide-vue-next';

defineProps<{
    categories: string[];
}>();

const thumbnailPreview = ref<string | null>(null);

const form = useForm({
    title: '',
    slug: '',
    category: 'Mengenal MOCAF',
    excerpt: '',
    content: '',
    thumbnail: null as File | null,
    published_at: '',
    status: 'published',
});

function handleThumbnailChange(e: Event) {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.thumbnail = target.files[0];
        thumbnailPreview.value = URL.createObjectURL(target.files[0]);
    }
}

function submit() {
    form.post('/admin/articles', {
        forceFormData: true,
    });
}
</script>

<template>
    <AdminLayout title="Tulis Artikel Baru">
        <Head title="Tulis Artikel - Moodles Admin" />

        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-serif font-bold text-2xl text-forest">Tulis Artikel Jurnal</h2>
                    <p class="text-xs text-darkbrown/60">Publikasikan konten edukasi seputar MOCAF dan inovasi singkong</p>
                </div>
                <Link
                    href="/admin/articles"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-cream text-xs font-poppins font-semibold text-darkbrown hover:text-forest transition-colors"
                >
                    <ArrowLeft class="w-4 h-4 text-moodles" />
                    <span>Kembali</span>
                </Link>
            </div>

            <!-- Form Card -->
            <form @submit.prevent="submit" class="bg-white rounded-3xl border border-cream shadow-sm p-6 sm:p-8 space-y-6">
                <!-- Judul & Slug -->
                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                            Judul Artikel <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.title"
                            type="text"
                            placeholder="Contoh: Mengapa Tepung MOCAF Lebih Sehat untuk Keluarga?"
                            required
                            class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                        />
                        <p v-if="form.errors.title" class="text-xs text-red-600">{{ form.errors.title }}</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select
                                v-model="form.category"
                                required
                                class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                            >
                                <option v-for="cat in categories" :key="cat" :value="cat">
                                    {{ cat }}
                                </option>
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                                Status Publikasi
                            </label>
                            <select
                                v-model="form.status"
                                class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                            >
                                <option value="published">Terbit (Published)</option>
                                <option value="draft">Draf (Draft)</option>
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                                Slug (Opsional)
                            </label>
                            <input
                                v-model="form.slug"
                                type="text"
                                placeholder="Otomatis dari judul"
                                class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                            />
                        </div>
                    </div>
                </div>

                <!-- Thumbnail Upload -->
                <div class="space-y-2 pt-2 border-t border-cream">
                    <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                        Gambar Sampul (Thumbnail)
                    </label>
                    <div class="border-2 border-dashed border-cream rounded-2xl p-4 text-center hover:border-forest/50 transition-colors">
                        <img
                            v-if="thumbnailPreview"
                            :src="thumbnailPreview"
                            alt="Preview Sampul"
                            class="w-48 h-28 object-cover rounded-xl mx-auto mb-2 border border-cream"
                        />
                        <input
                            type="file"
                            accept="image/*"
                            @change="handleThumbnailChange"
                            class="block w-full text-xs text-darkbrown/60 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-forest file:text-cream hover:file:bg-deep cursor-pointer"
                        />
                    </div>
                </div>

                <!-- Excerpt -->
                <div class="space-y-1.5 pt-2 border-t border-cream">
                    <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                        Ringkasan Singkat (Excerpt)
                    </label>
                    <textarea
                        v-model="form.excerpt"
                        rows="2"
                        placeholder="Ringkasan 1-2 kalimat pengantar artikel..."
                        class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                    ></textarea>
                </div>

                <!-- Content Body -->
                <div class="space-y-1.5 pt-2 border-t border-cream">
                    <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                        Isi Konten Artikel <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        v-model="form.content"
                        rows="12"
                        placeholder="Tuliskan isi artikel Anda di sini (Mendukung format HTML atau teks paragraf biasa)..."
                        required
                        class="w-full px-4 py-3 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest font-mono"
                    ></textarea>
                    <p v-if="form.errors.content" class="text-xs text-red-600">{{ form.errors.content }}</p>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-cream flex justify-end gap-3">
                    <Link
                        href="/admin/articles"
                        class="px-6 py-3 rounded-2xl border border-cream text-xs font-poppins font-semibold text-darkbrown hover:bg-cream/40 transition-colors"
                    >
                        Batal
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-8 py-3 rounded-2xl bg-forest hover:bg-deep text-cream text-xs font-poppins font-bold shadow-card transition-all disabled:opacity-50"
                    >
                        <span v-if="form.processing">Menyimpan...</span>
                        <span v-else>Terbitkan Artikel</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

