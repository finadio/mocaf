<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import type { Article } from '@/types';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ArrowLeft } from 'lucide-vue-next';

const props = defineProps<{
    article: Article;
    categories: string[];
}>();

const thumbnailPreview = ref<string | null>(props.article.thumbnail || null);

const form = ref({
    title: props.article.title,
    slug: props.article.slug,
    category: props.article.category,
    excerpt: props.article.excerpt || '',
    content: props.article.content,
    thumbnail: null as File | null,
    published_at: props.article.published_at ? props.article.published_at.substring(0, 10) : '',
    status: props.article.status,
});

const isProcessing = ref(false);
const errors = ref<Record<string, string>>({});

function handleThumbnailChange(e: Event) {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.value.thumbnail = target.files[0];
        thumbnailPreview.value = URL.createObjectURL(target.files[0]);
    }
}

function submit() {
    isProcessing.value = true;
    router.post(
        `/admin/articles/${props.article.id}`,
        {
            _method: 'PUT',
            ...form.value,
        },
        {
            forceFormData: true,
            onError: (err) => {
                errors.value = err;
                isProcessing.value = false;
            },
            onFinish: () => {
                isProcessing.value = false;
            },
        }
    );
}
</script>

<template>
    <AdminLayout :title="`Edit Artikel: ${article.title}`">
        <Head :title="`Edit ${article.title} - Moodles Admin`" />

        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-serif font-bold text-2xl text-forest">Edit Artikel Jurnal</h2>
                    <p class="text-xs text-darkbrown/60">Perbarui isi konten, gambar sampul, atau status publikasi</p>
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
                            required
                            class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                        />
                        <p v-if="errors.title" class="text-xs text-red-600">{{ errors.title }}</p>
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
                                Slug
                            </label>
                            <input
                                v-model="form.slug"
                                type="text"
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
                        <span class="text-[10px] text-darkbrown/50 block mt-1">Biarkan kosong jika tidak ingin mengubah thumbnail.</span>
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
                        required
                        class="w-full px-4 py-3 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest font-mono"
                    ></textarea>
                    <p v-if="errors.content" class="text-xs text-red-600">{{ errors.content }}</p>
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
                        :disabled="isProcessing"
                        class="px-8 py-3 rounded-2xl bg-forest hover:bg-deep text-cream text-xs font-poppins font-bold shadow-card transition-all disabled:opacity-50"
                    >
                        <span v-if="isProcessing">Menyimpan Perubahan...</span>
                        <span v-else>Perbarui Artikel</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
