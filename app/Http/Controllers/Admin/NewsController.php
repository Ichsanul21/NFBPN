<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsCategory;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class NewsController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', News::class);

        $news = News::with('category')->latest()->paginate(15);

        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        $this->authorize('create', News::class);

        return view('admin.news.form', [
            'item' => new News(),
            'categories' => NewsCategory::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, ImageService $images)
    {
        $this->authorize('create', News::class);

        $data = $this->validated($request);

        if ($request->hasFile('cover')) {
            $stored = $images->storePhoto($request->file('cover'), 'berita');
            $data['cover_path'] = $stored['path'];
        }
        $data['user_id'] = auth()->id();

        News::create($data);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diterbitkan.');
    }

    public function edit(News $news)
    {
        $this->authorize('update', $news);

        return view('admin.news.form', [
            'item' => $news,
            'categories' => NewsCategory::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, News $news, ImageService $images)
    {
        $this->authorize('update', $news);

        $data = $this->validated($request, $news->id);

        if ($request->hasFile('cover')) {
            $images->delete($news->cover_path);
            $stored = $images->storePhoto($request->file('cover'), 'berita');
            $data['cover_path'] = $stored['path'];
        }

        $news->update($data);

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(News $news, ImageService $images)
    {
        $this->authorize('delete', $news);

        $images->delete($news->cover_path);
        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'Berita berhasil dihapus.');
    }

    protected function validated(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:news,slug'.($ignoreId ? ','.$ignoreId : ''),
            'category_id' => 'nullable|exists:news_categories,id',
            'excerpt' => 'nullable|string|max:500',
            'body' => 'required|string',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = $validated['slug'] ?: $this->uniqueSlug($validated['title'], $ignoreId);
        $validated['body'] = $this->purify($validated['body']);
        unset($validated['cover']);

        return $validated;
    }

    protected function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'berita';
        $slug = $base;
        $i = 2;
        while (News::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public static function purify(string $html): string
    {
        return Purifier::clean($html, [
            'HTML.Allowed' => 'p,b,strong,i,em,u,a[href|target|rel],ul,ol,li,h2,h3,h4,blockquote,table,thead,tbody,tr,th,td,img[src|alt|width|height],br,hr,span,figure,figcaption',
            'AutoFormat.AutoParagraph' => true,
            'HTML.TargetBlank' => true,
        ]);
    }
}
