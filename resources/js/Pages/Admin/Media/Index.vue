<script setup lang="ts">
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import {
    Image as ImageIcon,
    Upload,
    Trash2,
    Copy,
    Check,
    FolderOpen,
    Eye,
    X,
} from 'lucide-vue-next';

interface MediaItem {
    name: string;
    folder: string;
    folder_label: string;
    url: string;
    size_kb: number;
    modified_at: string;
}

const props = defineProps<{
    mediaItems: MediaItem[];
    folders: Record<string, string>;
    currentFolder?: string;
}>();

const copiedUrl = ref<string | null>(null);
const previewItem = ref<MediaItem | null>(null);

const uploadForm = useForm({
    file: null as File | null,
    folder: props.currentFolder || 'uploads/products',
});

function handleFileSelect(e: Event) {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        uploadForm.file = target.files[0];
    }
}

function submitUpload() {
    if (!uploadForm.file) return;
    uploadForm.post('/admin/media', {
        forceFormData: true,
        onSuccess: () => {
            uploadForm.reset('file');
            const input = document.getElementById('media-file-input') as HTMLInputElement | null;
            if (input) input.value = '';
        },
    });
}

function filterFolder(folder: string) {
    router.get(
        '/admin/media',
        { folder: folder || undefined },
        { preserveState: true, preserveScroll: true }
    );
}

function copyUrl(url: string) {
    navigator.clipboard.writeText(url);
    copiedUrl.value = url;
    setTimeout(() => {
        if (copiedUrl.value === url) {
            copiedUrl.value = null;
        }
    }, 2000);
}

function deleteMedia(item: MediaItem) {
    if (confirm(`Yakin ingin menghapus file media "${item.name}"?`)) {
        router.delete('/admin/media', {
            data: { url: item.url },
            preserveScroll: true,
        });
    }
}
</script>

<template>
    <AdminLayout title="Pengelolaan Media">
        <Head title="Pengelolaan Media - Moodles Admin" />

        <div class="space-y-6">
            <!-- Header & Upload Form -->
            <div class="bg-white rounded-3xl border border-cream p-6 shadow-sm space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-cream pb-4">
                    <div>
                        <h2 class="font-poppins font-bold text-xl text-forest">Galeri &amp; Aset Media Website</h2>
                        <p class="text-xs text-darkbrown/65 mt-0.5">
                            Unggah, kelola, dan salin jalur gambar untuk produk, artikel jurnal, maupun halaman utama.
                        </p>
                    </div>
                    <span class="px-3.5 py-1.5 rounded-full bg-softgreen text-forest font-poppins text-xs font-bold self-start sm:self-auto">
                        {{ mediaItems.length }} File Tersedia
                    </span>
                </div>

                <form @submit.prevent="submitUpload" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <div class="md:col-span-5 space-y-1.5">
                        <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/70">
                            Pilih File Gambar
                        </label>
                        <input
                            id="media-file-input"
                            type="file"
                            accept="image/*"
                            @change="handleFileSelect"
                            required
                            class="w-full text-xs text-darkbrown file:mr-3 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-poppins file:font-bold file:bg-softgreen file:text-forest hover:file:bg-forest hover:file:text-white transition-all cursor-pointer border border-cream rounded-2xl p-1.5 bg-cream/20"
                        />
                        <p v-if="uploadForm.errors.file" class="text-xs text-red-600">{{ uploadForm.errors.file }}</p>
                    </div>

                    <div class="md:col-span-4 space-y-1.5">
                        <label class="block text-xs font-poppins font-bold uppercase tracking-wider text-darkbrown/70">
                            Folder Tujuan
                        </label>
                        <select
                            v-model="uploadForm.folder"
                            class="w-full px-4 py-2.5 rounded-2xl border border-cream bg-cream/20 text-xs sm:text-sm text-darkbrown focus:ring-forest focus:border-forest"
                        >
                            <option v-for="(label, key) in folders" :key="key" :value="key">
                                {{ label }} (/assets/{{ key }})
                            </option>
                        </select>
                        <p v-if="uploadForm.errors.folder" class="text-xs text-red-600">{{ uploadForm.errors.folder }}</p>
                    </div>

                    <div class="md:col-span-3">
                        <button
                            type="submit"
                            :disabled="uploadForm.processing || !uploadForm.file"
                            class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-forest hover:bg-deep text-cream font-poppins font-bold text-xs shadow-sm transition-all disabled:opacity-50 cursor-pointer"
                        >
                            <Upload class="w-4 h-4 text-moodles" />
                            <span>{{ uploadForm.processing ? 'Mengunggah...' : 'Unggah Media' }}</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Folder Filter Tabs -->
            <div class="flex flex-wrap items-center gap-2">
                <button
                    type="button"
                    @click="filterFolder('')"
                    :class="[
                        'inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-xs font-poppins font-semibold transition-all cursor-pointer',
                        !currentFolder ? 'bg-forest text-cream shadow-sm' : 'bg-white text-darkbrown border border-cream hover:bg-cream/40'
                    ]"
                >
                    <FolderOpen class="w-3.5 h-3.5" />
                    <span>Semua Folder</span>
                </button>
                <button
                    v-for="(label, key) in folders"
                    :key="key"
                    type="button"
                    @click="filterFolder(key)"
                    :class="[
                        'px-4 py-2 rounded-full text-xs font-poppins font-semibold transition-all cursor-pointer',
                        currentFolder === key ? 'bg-forest text-cream shadow-sm' : 'bg-white text-darkbrown border border-cream hover:bg-cream/40'
                    ]"
                >
                    {{ label }}
                </button>
            </div>

            <!-- Media Grid -->
            <div v-if="mediaItems.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <div
                    v-for="item in mediaItems"
                    :key="item.url"
                    class="bg-white rounded-3xl border border-cream overflow-hidden shadow-sm hover:shadow-card transition-all flex flex-col justify-between group"
                >
                    <div class="relative aspect-square bg-cream/30 overflow-hidden">
                        <img
                            :src="item.url"
                            :alt="item.name"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            loading="lazy"
                        />
                        <div class="absolute top-3 left-3">
                            <span class="px-2.5 py-1 rounded-full bg-forest/85 text-cream text-[10px] font-poppins font-semibold backdrop-blur-sm">
                                {{ item.folder_label }}
                            </span>
                        </div>
                        <button
                            type="button"
                            @click="previewItem = item"
                            class="absolute bottom-3 right-3 w-8 h-8 rounded-full bg-white/90 hover:bg-white text-forest flex items-center justify-center shadow-md opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer"
                            title="Pratinjau Gambar"
                        >
                            <Eye class="w-4 h-4" />
                        </button>
                    </div>

                    <div class="p-4 space-y-3">
                        <div>
                            <p class="font-poppins font-semibold text-xs text-forest truncate" :title="item.name">
                                {{ item.name }}
                            </p>
                            <p class="text-[11px] text-darkbrown/60 mt-0.5">
                                {{ item.size_kb }} KB • {{ item.modified_at }}
                            </p>
                        </div>

                        <div class="flex items-center gap-2 pt-2 border-t border-cream">
                            <button
                                type="button"
                                @click="copyUrl(item.url)"
                                class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-softgreen/70 hover:bg-softgreen text-forest text-[11px] font-poppins font-bold transition-colors cursor-pointer"
                            >
                                <Check v-if="copiedUrl === item.url" class="w-3.5 h-3.5 text-olive" />
                                <Copy v-else class="w-3.5 h-3.5" />
                                <span>{{ copiedUrl === item.url ? 'Tersalin' : 'Salin URL' }}</span>
                            </button>

                            <button
                                v-if="item.url !== '/assets/brand/moodles-logo.png'"
                                type="button"
                                @click="deleteMedia(item)"
                                class="p-2 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 transition-colors cursor-pointer"
                                title="Hapus File"
                            >
                                <Trash2 class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="bg-white rounded-3xl border border-cream p-12 text-center space-y-2">
                <ImageIcon class="w-10 h-10 text-olive mx-auto opacity-70" />
                <h3 class="font-poppins font-bold text-base text-forest">Belum Ada Media di Folder Ini</h3>
                <p class="text-xs text-darkbrown/65">Unggah gambar baru menggunakan formulir di atas.</p>
            </div>
        </div>

        <!-- Lightbox Preview Modal -->
        <div
            v-if="previewItem"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
            @click.self="previewItem = null"
        >
            <div class="relative w-full max-w-xl bg-white rounded-3xl overflow-hidden shadow-2xl border border-cream">
                <div class="relative bg-black/95 aspect-video flex items-center justify-center">
                    <img :src="previewItem.url" :alt="previewItem.name" class="max-h-full max-w-full object-contain" />
                    <button
                        type="button"
                        @click="previewItem = null"
                        class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/60 hover:bg-black text-white flex items-center justify-center cursor-pointer"
                    >
                        <X class="w-4 h-4" />
                    </button>
                </div>
                <div class="p-5 flex items-center justify-between gap-4">
                    <div class="min-w-0">
                        <p class="font-poppins font-bold text-sm text-forest truncate">{{ previewItem.name }}</p>
                        <p class="text-xs text-darkbrown/65 truncate">{{ previewItem.url }}</p>
                    </div>
                    <button
                        type="button"
                        @click="copyUrl(previewItem.url)"
                        class="px-4 py-2 rounded-full bg-forest text-cream text-xs font-poppins font-bold shrink-0 cursor-pointer"
                    >
                        {{ copiedUrl === previewItem.url ? 'URL Tersalin!' : 'Salin URL' }}
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

