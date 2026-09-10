@extends('layouts.admin')
@section('title', 'Berita')
@section('page-title', 'Berita')

@section('content')
<div class="flex items-center justify-between mb-4">
    <p class="text-sm text-nf-ink/60">{{ $news->total() }} berita</p>
    @can('create', App\Models\News::class)
    <a href="{{ route('admin.news.create') }}" class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-5 py-2.5 rounded-full transition">+ Tulis Berita</a>
    @endcan
</div>
<div class="rounded-3xl bg-white border border-nf-blue/15 overflow-hidden">
    <div class="divide-y divide-nf-blue/10">
        @forelse($news as $n)
        <div class="flex items-center gap-4 px-5 py-4">
            <div class="flex-1 min-w-0">
                <p class="font-heading font-bold truncate">{{ $n->title }}</p>
                <p class="text-xs text-nf-ink/50 mt-0.5">{{ $n->category?->name ?? 'Tanpa kategori' }} · {{ $n->created_at->format('d M Y') }} · {{ $n->published_at ? 'Terbit' : 'Draf' }}</p>
            </div>
            @can('update', $n)
            <a href="{{ route('admin.news.edit', $n) }}" class="text-sm font-bold text-nf-blue-dark hover:underline shrink-0">Ubah</a>
            @endcan
            @can('delete', $n)
            <form method="POST" action="{{ route('admin.news.destroy', $n) }}" onsubmit="return confirm('Hapus berita ini?')" class="shrink-0">
                @csrf @method('DELETE')
                <button class="text-sm font-bold text-red-600 hover:underline">Hapus</button>
            </form>
            @endcan
        </div>
        @empty
        <p class="px-5 py-10 text-center text-sm text-nf-ink/50">Belum ada berita.</p>
        @endforelse
    </div>
</div>
<div class="mt-4">{{ $news->links() }}</div>
@endsection
