<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Services\ImageService;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public const CATEGORIES = ['Kegiatan', 'Tahfidz', 'Sains', 'Olahraga', 'Seni', 'Kunjungan'];
    public const UNITS = ['Daycare', 'KBIT', 'SDIT', 'SMPIT'];

    public function index(Request $request)
    {
        $this->authorize('viewAny', Gallery::class);

        $galleries = Gallery::when($request->category, fn ($q, $c) => $q->where('category', $c))
            ->latest()->paginate(24)->withQueryString();

        return view('admin.galleries.index', [
            'galleries' => $galleries,
            'categories' => self::CATEGORIES,
        ]);
    }

    public function create()
    {
        $this->authorize('create', Gallery::class);

        return view('admin.galleries.form', [
            'categories' => self::CATEGORIES,
            'units' => self::UNITS,
        ]);
    }

    public function store(Request $request, ImageService $images)
    {
        $this->authorize('create', Gallery::class);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:'.implode(',', self::CATEGORIES),
            'unit' => 'nullable|in:'.implode(',', self::UNITS),
            'photos' => 'required|array|max:20',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $title = \App\Support\Sanitize::name($data['title']);
        foreach ($data['photos'] as $i => $photo) {
            $stored = $images->storePhoto($photo, 'galeri');
            Gallery::create([
                'title' => $title.(count($data['photos']) > 1 ? ' '.($i + 1) : ''),
                'category' => $data['category'],
                'unit' => $data['unit'] ?? null,
                'path' => $stored['path'],
                'thumb_path' => $stored['thumb_path'],
            ]);
        }

        return redirect()->route('admin.galleries.index')->with('success', 'Foto berhasil diunggah.');
    }

    public function edit(Gallery $gallery)
    {
        $this->authorize('update', $gallery);

        return view('admin.galleries.edit', [
            'item' => $gallery,
            'categories' => self::CATEGORIES,
            'units' => self::UNITS,
        ]);
    }

    public function update(Request $request, Gallery $gallery)
    {
        $this->authorize('update', $gallery);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:'.implode(',', self::CATEGORIES),
            'unit' => 'nullable|in:'.implode(',', self::UNITS),
        ]);

        $data['title'] = \App\Support\Sanitize::name($data['title']);
        $gallery->update($data);

        return redirect()->route('admin.galleries.index')->with('success', 'Data foto berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery, ImageService $images)
    {
        $this->authorize('delete', $gallery);

        $images->delete($gallery->path, $gallery->thumb_path);
        $gallery->delete();

        return redirect()->route('admin.galleries.index')->with('success', 'Foto berhasil dihapus.');
    }
}
