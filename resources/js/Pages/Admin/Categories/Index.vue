<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import type { Category } from '@/types';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Plus, Edit, Trash2, FolderTree, Check, X } from 'lucide-vue-next';

const props = defineProps<{
    categories: Category[];
}>();

const createForm = useForm({
    name: '',
    slug: '',
    description: '',
});

const editingCategory = ref<Category | null>(null);
const editForm = useForm({
    name: '',
    slug: '',
    description: '',
});

function submitCreate() {
    createForm.post('/admin/categories', {
        onSuccess: () => createForm.reset(),
    });
}

function startEdit(cat: Category) {
    editingCategory.value = cat;
    editForm.name = cat.name;
    editForm.slug = cat.slug;
    editForm.description = cat.description || '';
}

function cancelEdit() {
    editingCategory.value = null;
    editForm.reset();
}

function submitEdit() {
    if (!editingCategory.value) return;
    editForm.put(`/admin/categories/${editingCategory.value.id}`, {
        onSuccess: () => cancelEdit(),
    });
}

function deleteCategory(cat: Category) {
    if (confirm(`Hapus kategori "${cat.name}"? Produk yang terkait akan dipindahkan ke tanpa kategori.`)) {
        router.delete(`/admin/categories/${cat.id}`);
    }
}
</script>

<template>
    <AdminLayout title="Kelola Kategori Produk">
        <Head title="Kelola Kategori - Moodles Admin" />

        <div class="space-y-6">
            <div>
                <h2 class="font-serif font-bold text-2xl text-forest">Kategori Produk</h2>
                <p class="text-xs text-darkbrown/60">Kelola pengelompokan produk mi instan dan bundling</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Left: Category List -->
                <div class="lg:col-span-7 bg-white rounded-3xl border border-cream shadow-sm p-6 sm:p-8 space-y-4">
                    <h3 class="font-serif font-bold text-lg text-forest border-b border-cream pb-3 flex items-center gap-2">
                        <FolderTree class="w-5 h-5 text-moodles" />
                        <span>Daftar Kategori Terdaftar</span>
                    </h3>

                    <div class="divide-y divide-cream">
                        <div v-if="categories.length === 0" class="py-8 text-center text-xs text-darkbrown/60">
                            Belum ada kategori yang dibuat.
                        </div>

                        <div
                            v-for="cat in categories"
                            :key="cat.id"
                            class="py-4 first:pt-0 last:pb-0 flex items-start justify-between gap-4"
                        >
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-serif font-bold text-base text-forest">{{ cat.name }}</h4>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-poppins font-semibold bg-softgreen text-forest">
                                        {{ cat.products_count ?? 0 }} produk
                                    </span>
                                </div>
                                <p class="text-xs font-poppins text-darkbrown/50">Slug: {{ cat.slug }}</p>
                                <p v-if="cat.description" class="text-xs text-darkbrown/70 leading-relaxed">
                                    {{ cat.description }}
                                </p>
                            </div>

                            <div class="flex items-center gap-1.5 shrink-0">
                                <button
                                    type="button"
                                    @click="startEdit(cat)"
                                    class="p-2 rounded-xl text-forest hover:bg-cream/60 transition-colors"
                                    title="Edit Kategori"
                                >
                                    <Edit class="w-4 h-4" />
                                </button>
                                <button
                                    type="button"
                                    @click="deleteCategory(cat)"
                                    class="p-2 rounded-xl text-red-500 hover:text-red-700 hover:bg-red-50 transition-colors"
                                    title="Hapus Kategori"
                                >
                                    <Trash2 class="w-4 h-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Create / Edit Form -->
                <div class="lg:col-span-5 bg-white rounded-3xl border border-cream shadow-sm p-6 sm:p-8 space-y-4">
                    <h3 class="font-serif font-bold text-lg text-forest border-b border-cream pb-3 flex items-center justify-between">
                        <span>{{ editingCategory ? 'Edit Kategori' : 'Tambah Kategori Baru' }}</span>
                        <button
                            v-if="editingCategory"
                            type="button"
                            @click="cancelEdit"
                            class="text-xs font-poppins font-semibold text-darkbrown/50 hover:text-darkbrown"
                        >
                            Batal Edit
                        </button>
                    </h3>

                    <!-- Edit Form -->
                    <form v-if="editingCategory" @submit.prevent="submitEdit" class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                                Nama Kategori <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="editForm.name"
                                type="text"
                                required
                                class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                            />
                            <p v-if="editForm.errors.name" class="text-xs text-red-600">{{ editForm.errors.name }}</p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                                Slug
                            </label>
                            <input
                                v-model="editForm.slug"
                                type="text"
                                class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                            />
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                                Deskripsi
                            </label>
                            <textarea
                                v-model="editForm.description"
                                rows="3"
                                class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                            ></textarea>
                        </div>

                        <button
                            type="submit"
                            :disabled="editForm.processing"
                            class="w-full py-3 rounded-2xl bg-forest hover:bg-deep text-cream text-xs font-poppins font-bold shadow-card transition-all disabled:opacity-50"
                        >
                            Simpan Perubahan
                        </button>
                    </form>

                    <!-- Create Form -->
                    <form v-else @submit.prevent="submitCreate" class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                                Nama Kategori <span class="text-red-500">*</span>
                            </label>
                            <input
                                v-model="createForm.name"
                                type="text"
                                placeholder="Contoh: Mi Instan Cup"
                                required
                                class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                            />
                            <p v-if="createForm.errors.name" class="text-xs text-red-600">{{ createForm.errors.name }}</p>
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                                Slug (Opsional)
                            </label>
                            <input
                                v-model="createForm.slug"
                                type="text"
                                placeholder="Otomatis dari nama"
                                class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                            />
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/80">
                                Deskripsi Singkat
                            </label>
                            <textarea
                                v-model="createForm.description"
                                rows="3"
                                placeholder="Penjelasan singkat kategori..."
                                class="w-full px-4 py-2.5 rounded-2xl border-cream bg-cream/20 text-sm text-darkbrown focus:ring-forest focus:border-forest"
                            ></textarea>
                        </div>

                        <button
                            type="submit"
                            :disabled="createForm.processing"
                            class="w-full py-3 rounded-2xl bg-moodles hover:bg-moodles-hover text-white text-xs font-poppins font-bold shadow-card transition-all disabled:opacity-50"
                        >
                            + Tambah Kategori
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
