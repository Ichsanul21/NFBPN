<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Testimonial::class);

        $items = Testimonial::orderBy('sort_order')->orderBy('id')->paginate(15);

        return view('admin.testimonials.index', compact('items'));
    }

    public function create()
    {
        $this->authorize('create', Testimonial::class);

        return view('admin.testimonials.form', ['item' => new Testimonial()]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Testimonial::class);

        Testimonial::create($this->validated($request));

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimoni berhasil ditambahkan.');
    }

    public function edit(Testimonial $testimonial)
    {
        $this->authorize('update', $testimonial);

        return view('admin.testimonials.form', ['item' => $testimonial]);
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $this->authorize('update', $testimonial);

        $testimonial->update($this->validated($request));

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimoni berhasil diperbarui.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $this->authorize('delete', $testimonial);

        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimoni berhasil dihapus.');
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'quote' => 'required|string|max:1000',
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_published' => 'nullable|boolean',
        ]);
        $data['quote'] = \App\Support\Sanitize::text($data['quote'], 1000);
        $data['name'] = \App\Support\Sanitize::name($data['name']);
        $data['role'] = \App\Support\Sanitize::name($data['role'] ?? null);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }
}
