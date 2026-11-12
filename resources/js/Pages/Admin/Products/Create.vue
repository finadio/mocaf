<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import type { Category } from '@/types';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ArrowLeft, Upload, Plus, Trash2 } from 'lucide-vue-next';

defineProps<{
    categories: Category[];
}>();

const mainImagePreview = ref<string | null>(null);
const galleryPreviews = ref<string[]>([]);

const form = useForm({
    category_id: '' as string | number,
    name: '',
    slug: '',
    variant: 'Goreng',
    short_description: '',
    description: '',
    price: '' as string | number,
    weight: 80,
    main_image: null as File | null,
    gallery: [] as File[],
    ingredients: '',
    nutrition_information: '',
    allergen_information: '',
    preparation_instructions: '',
    storage_instructions: '',
    is_active: true,
    is_featured: false,
});

function handleMainImageChange(e: Event) {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.main_image = target.files[0];
        mainImagePreview.value = URL.createObjectURL(target.files[0]);
    }
}

function handleGalleryChange(e: Event) {
    const target = e.target as HTMLInputElement;
    if (target.files) {
        const files = Array.from(target.files);
        form.gallery = files;
        galleryPreviews.value = files.map((file) => URL.createObjectURL(file));
    }
}

function submit() {
    form.post('/admin/products', {
        forceFormData: true,
    });
}
</script>

<template>
    <AdminLayout title="Tambah Produk Baru">
        <Head title="Tambah Produk - Moodles Admin" />

        <div class="max-w-4xl mx-auto space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-serif font-bold text-2xl text-forest">Tambah Varian Produk</h2>
                    <p class="text-xs text-darkbrown/60">Lengkapi informasi produk mi instan MOCAF secara detail</p>
                </div>
                <Link
                    href="/admin/products"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-white border border-cream text-xs font-poppins font-semibold text-darkbrown hover:text-forest transition-colors"
                >
                    <ArrowLeft class="w-4 h-4 text-moodles" />
                    <span>Kembali</span>
                </Link>
            </div>

            <!-- Form Card -->
            <form @submit.prevent="submit" class="bg-white rounded-3xl border border-cream shadow-sm p-6 sm:p-8 space-y-6">
                <!-- Row: Nama, Varian, Kategori -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                            Nama Produk <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="Contoh: Moodles Mi Goreng Spesial"
                            required
                            class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                        />
                        <p v-if="form.errors.name" class="text-xs text-red-600">{{ form.errors.name }}</p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                            Varian <span class="text-red-500">*</span>
                        </label>
                        <select
                            v-model="form.variant"
                            required
                            class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                        >
                            <option value="Goreng">Goreng</option>
                            <option value="Kuah">Kuah</option>
                            <option value="Mix">Mix / Bundling</option>
                        </select>
                        <p v-if="form.errors.variant" class="text-xs text-red-600">{{ form.errors.variant }}</p>
                    </div>
                </div>

                <!-- Row: Kategori, Harga, Berat, Slug -->
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                            Kategori
                        </label>
                        <select
                            v-model="form.category_id"
                            class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                        >
                            <option value="">Pilih Kategori</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                {{ cat.name }}
                            </option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                            Harga (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.price"
                            type="number"
                            placeholder="12000"
                            required
                            min="0"
                            class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                        />
                        <p v-if="form.errors.price" class="text-xs text-red-600">{{ form.errors.price }}</p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                            Berat Bersih (gram) <span class="text-red-500">*</span>
                        </label>
                        <input
                            v-model="form.weight"
                            type="number"
                            required
                            min="1"
                            class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                        />
                        <p v-if="form.errors.weight" class="text-xs text-red-600">{{ form.errors.weight }}</p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                            Slug (Opsional)
                        </label>
                        <input
                            v-model="form.slug"
                            type="text"
                            placeholder="Otomatis jika kosong"
                            class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                        />
                    </div>
                </div>

                <!-- Foto Utama & Galeri -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-2 border-t border-cream">
                    <!-- Foto Utama -->
                    <div class="space-y-2">
                        <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                            Foto Utama Produk
                        </label>
                        <div class="border-2 border-dashed border-cream rounded-2xl p-4 text-center hover:border-forest/50 transition-colors">
                            <img
                                v-if="mainImagePreview"
                                :src="mainImagePreview"
                                alt="Preview Foto Utama"
                                class="w-32 h-32 object-cover rounded-xl mx-auto mb-2 border border-cream"
                            />
                            <input
                                type="file"
                                accept="image/*"
                                @change="handleMainImageChange"
                                class="block w-full text-xs text-darkbrown/60 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-forest file:text-cream hover:file:bg-deep cursor-pointer"
                            />
                        </div>
                    </div>

                    <!-- Galeri Tambahan -->
                    <div class="space-y-2">
                        <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                            Galeri Foto Pendukung (Multi)
                        </label>
                        <div class="border-2 border-dashed border-cream rounded-2xl p-4 text-center hover:border-forest/50 transition-colors">
                            <div v-if="galleryPreviews.length > 0" class="flex gap-2 justify-center mb-2 overflow-x-auto py-1">
                                <img
                                    v-for="(g, idx) in galleryPreviews"
                                    :key="idx"
                                    :src="g"
                                    class="w-16 h-16 object-cover rounded-xl border border-cream shrink-0"
                                />
                            </div>
                            <input
                                type="file"
                                accept="image/*"
                                multiple
                                @change="handleGalleryChange"
                                class="block w-full text-xs text-darkbrown/60 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-cream file:text-forest hover:file:bg-cream/80 cursor-pointer"
                            />
                        </div>
                    </div>
                </div>

                <!-- Deskripsi Singkat & Lengkap -->
                <div class="space-y-4 pt-2 border-t border-cream">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                            Deskripsi Singkat (Ringkasan pada Kartu)
                        </label>
                        <textarea
                            v-model="form.short_description"
                            rows="2"
                            placeholder="Ringkasan keunggulan rasa dan bahan baku..."
                            class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                        ></textarea>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                            Deskripsi Lengkap
                        </label>
                        <textarea
                            v-model="form.description"
                            rows="4"
                            placeholder="Cerita mendalam tentang produk, keistimewaan rasa, dan profil bahan..."
                            class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                        ></textarea>
                    </div>
                </div>

                <!-- Komposisi, Gizi, Alergen, Penyajian, Simpan -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-cream">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                            Komposisi Bahan
                        </label>
                        <textarea
                            v-model="form.ingredients"
                            rows="3"
                            placeholder="Tepung MOCAF, Tepung Terigu, Minyak Nabati, Garam..."
                            class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                        ></textarea>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                            Informasi Nilai Gizi
                        </label>
                        <textarea
                            v-model="form.nutrition_information"
                            rows="3"
                            placeholder="Isi hanya jika data uji lab resmi sudah tersedia..."
                            class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                        ></textarea>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                            Informasi Alergen
                        </label>
                        <input
                            v-model="form.allergen_information"
                            type="text"
                            placeholder="Contoh: Mengandung gluten dan kedelai."
                            class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                        />
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                            Cara Penyimpanan
                        </label>
                        <input
                            v-model="form.storage_instructions"
                            type="text"
                            placeholder="Simpan di tempat kering dan sejuk."
                            class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                        />
                    </div>

                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                            Petunjuk Cara Memasak / Penyajian
                        </label>
                        <textarea
                            v-model="form.preparation_instructions"
                            rows="3"
                            placeholder="Langkah 1, 2, 3 cara merebus dan mencampur bumbu..."
                            class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                        ></textarea>
                    </div>
                </div>

                <!-- Switches: is_active & is_featured -->
                <div class="flex items-center gap-8 pt-4 border-t border-cream">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input
                            v-model="form.is_active"
                            type="checkbox"
                            class="w-4 h-4 rounded text-forest focus:ring-forest border-cream"
                        />
                        <span class="text-xs font-poppins font-bold text-darkbrown">Tampilkan di Katalog (Aktif)</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input
                            v-model="form.is_featured"
                            type="checkbox"
                            class="w-4 h-4 rounded text-moodles focus:ring-moodles border-cream"
                        />
                        <span class="text-xs font-poppins font-bold text-darkbrown">Jadikan Produk Unggulan ⭐</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-cream flex justify-end gap-3">
                    <Link
                        href="/admin/products"
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
                        <span v-else>Simpan Produk</span>
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
