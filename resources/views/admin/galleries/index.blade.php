@extends('layouts.admin')
@section('title', 'Galeri')
@section('page-title', 'Galeri')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-4">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.galleries.index') }}" class="text-xs font-bold px-4 py-2 rounded-full {{ !request('category') ? 'bg-nf-blue text-white' : 'bg-white border border-nf-blue/20' }}">Semua</a>
        @foreach($categories as $c)
        <a href="{{ route('admin.galleries.index', ['category' => $c]) }}" class="text-xs font-bold px-4 py-2 rounded-full {{ request('category') === $c ? 'bg-nf-blue text-white' : 'bg-white border border-nf-blue/20' }}">{{ $c }}</a>
        @endforeach
    </div>
    @can('create', App\Models\Gallery::class)
    <a href="{{ route('admin.galleries.create') }}" class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-5 py-2.5 rounded-full transition">+ Unggah Foto</a>
    @endcan
</div>
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    @forelse($galleries as $g)
    <div class="rounded-2xl bg-white border border-nf-blue/15 overflow-hidden">
        <img src="{{ $g->thumbUrl() }}" alt="{{ $g->title }}" class="w-full h-36 object-cover" loading="lazy">
        <div class="p-3">
            <p class="text-sm font-bold truncate">{{ $g->title }}</p>
            <p class="text-xs text-nf-ink/50">{{ $g->category }} · {{ $g->unit }}</p>
            <div class="mt-2 flex gap-3 text-xs font-bold">
                @can('update', $g)<a href="{{ route('admin.galleries.edit', $g) }}" class="text-nf-blue-dark hover:underline">Ubah</a>@endcan
                @can('delete', $g)
                <form method="POST" action="{{ route('admin.galleries.destroy', $g) }}" onsubmit="return confirm('Hapus foto ini?')">
                    @csrf @method('DELETE')
                    <button class="text-red-600 hover:underline">Hapus</button>
                </form>
                @endcan
            </div>
        </div>
    </div>
    @empty
    <p class="col-span-full text-center text-sm text-nf-ink/50 py-10">Belum ada foto.</p>
    @endforelse
</div>
<div class="mt-4">{{ $galleries->links() }}</div>
@endsection
