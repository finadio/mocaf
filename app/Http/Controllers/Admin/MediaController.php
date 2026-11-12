<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class MediaController extends Controller
{
    /**
     * Daftar folder media yang diizinkan di dalam public/assets.
     *
     * @var array<string, string>
     */
    private const ALLOWED_FOLDERS = [
        'products' => 'Produk',
        'food' => 'Sajian Makanan',
        'ingredients' => 'Bahan Baku',
        'activities' => 'Dokumentasi Kegiatan',
        'banners' => 'Banner & Ilustrasi',
        'brand' => 'Identitas Brand',
        'uploads/products' => 'Upload Produk',
        'uploads/articles' => 'Upload Artikel',
    ];

    /**
     * Menampilkan galeri dan daftar seluruh file media website.
     */
    public function index(Request $request): Response
    {
        $selectedFolder = $request->string('folder')->toString();
        $assetsRoot = public_path('assets');
        $mediaItems = [];

        foreach (self::ALLOWED_FOLDERS as $folder => $label) {
            if ($selectedFolder !== '' && $selectedFolder !== $folder) {
                continue;
            }

            $dirPath = $assetsRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $folder);
            if (! File::isDirectory($dirPath)) {
                continue;
            }

            foreach (File::files($dirPath) as $file) {
                $ext = strtolower($file->getExtension());
                if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'], true)) {
                    continue;
                }

                $filename = $file->getFilename();
                $mediaItems[] = [
                    'name' => $filename,
                    'folder' => $folder,
                    'folder_label' => $label,
                    'url' => '/assets/' . $folder . '/' . $filename,
                    'size_kb' => (int) ceil($file->getSize() / 1024),
                    'modified_at' => date('d M Y H:i', $file->getMTime()),
                ];
            }
        }

        usort($mediaItems, static function (array $a, array $b): int {
            return strcmp($a['folder'] . $a['name'], $b['folder'] . $b['name']);
        });

        return Inertia::render('Admin/Media/Index', [
            'mediaItems' => $mediaItems,
            'folders' => self::ALLOWED_FOLDERS,
            'currentFolder' => $selectedFolder,
        ]);
    }

    /**
     * Mengunggah file media baru ke folder aset pilihan admin.
     */
    public function store(Request $request): RedirectResponse
    {
        $folderKeys = implode(',', array_keys(self::ALLOWED_FOLDERS));

        $validated = $request->validate([
            'file' => ['required', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:4096'],
            'folder' => ['required', 'string', "in:{$folderKeys}"],
        ], [
            'file.required' => 'Pilih file gambar terlebih dahulu.',
            'file.image' => 'File yang diunggah harus berupa gambar.',
            'file.max' => 'Ukuran gambar maksimal 4 MB.',
            'folder.in' => 'Folder tujuan tidak valid.',
        ]);

        $file = $request->file('file');
        $folder = $validated['folder'];
        $targetDir = public_path('assets/' . $folder);

        if (! File::isDirectory($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        $baseName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        if ($baseName === '') {
            $baseName = 'media';
        }

        $filename = $baseName . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($targetDir, $filename);

        return back()->with('success', 'Media "' . $filename . '" berhasil diunggah ke folder ' . self::ALLOWED_FOLDERS[$folder] . '.');
    }

    /**
     * Menghapus file media dari direktori public/assets secara aman.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'url' => ['required', 'string'],
        ]);

        $url = $validated['url'];
        if (! str_starts_with($url, '/assets/')) {
            return back()->with('error', 'Jalur file media tidak valid.');
        }

        $relativePath = ltrim(substr($url, strlen('/assets/')), '/');
        if (str_contains($relativePath, '..')) {
            return back()->with('error', 'Jalur file tidak diizinkan.');
        }

        $assetsRoot = realpath(public_path('assets'));
        $targetPath = realpath(public_path('assets/' . $relativePath));

        if ($assetsRoot === false || $targetPath === false || ! str_starts_with($targetPath, $assetsRoot)) {
            return back()->with('error', 'File media tidak ditemukan.');
        }

        if ($url === '/assets/brand/moodles-logo.png') {
            return back()->with('error', 'Logo utama website tidak dapat dihapus.');
        }

        File::delete($targetPath);

        return back()->with('success', 'File media berhasil dihapus.');
    }
}

