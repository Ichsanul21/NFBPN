@extends('layouts.admin')
@section('title', 'Testimoni')
@section('page-title', 'Testimoni')

@section('content')
<div class="flex items-center justify-between mb-4">
    <p class="text-sm text-nf-ink/60">{{ $items->total() }} testimoni</p>
    @can('create', App\Models\Testimonial::class)
    <a href="{{ route('admin.testimonials.create') }}" class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-5 py-2.5 rounded-full transition">+ Tambah Testimoni</a>
    @endcan
</div>
<div class="rounded-3xl bg-white border border-nf-blue/15 overflow-hidden">
    <div class="divide-y divide-nf-blue/10">
        @forelse($items as $t)
        <div class="flex items-center gap-4 px-5 py-4">
            <div class="flex-1 min-w-0">
                <p class="font-bold text-sm truncate">"{{ $t->quote }}"</p>
                <p class="text-xs text-nf-ink/50 mt-0.5">{{ $t->name }} · {{ $t->role }} · Urutan {{ $t->sort_order }} · {{ $t->is_published ? 'Tampil' : 'Sembunyi' }}</p>
            </div>
            @can('update', $t)<a href="{{ route('admin.testimonials.edit', $t) }}" class="text-sm font-bold text-nf-blue-dark hover:underline shrink-0">Ubah</a>@endcan
            @can('delete', $t)
            <form method="POST" action="{{ route('admin.testimonials.destroy', $t) }}" onsubmit="return confirm('Hapus testimoni ini?')" class="shrink-0">
                @csrf @method('DELETE')
                <button class="text-sm font-bold text-red-600 hover:underline">Hapus</button>
            </form>
            @endcan
        </div>
        @empty
        <p class="px-5 py-10 text-center text-sm text-nf-ink/50">Belum ada testimoni.</p>
        @endforelse
    </div>
</div>
<div class="mt-4">{{ $items->links() }}</div>
@endsection
