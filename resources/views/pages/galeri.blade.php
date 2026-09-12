@extends('layouts.site')
@section('title', 'Galeri')

@section('content')
<section class="relative overflow-hidden bg-nf-ink text-white">
    <div class="absolute inset-0 islamic-pattern opacity-40"></div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 py-14 md:py-20">
        <p class="text-xs font-bold tracking-[0.25em] uppercase text-nf-yellow">Dokumentasi</p>
        <h1 class="mt-3 font-heading font-extrabold text-3xl md:text-5xl">Galeri Kegiatan</h1>
        <p class="mt-4 text-white/70 max-w-2xl">Momen belajar, bermain, dan berprestasi. Klik foto untuk melihat lebih besar.</p>
    </div>
</section>
<section class="mx-auto max-w-7xl px-4 sm:px-6 py-12">
    <div class="flex flex-wrap gap-2">
        @foreach($cats as $c)
        <button data-filter-btn="{{ $c }}" class="text-sm font-bold px-5 py-2.5 rounded-full border border-nf-blue/30 transition {{ $c==='semua' ? 'bg-nf-blue text-white' : 'bg-white text-nf-ink/70 hover:border-nf-blue' }}">{{ ucfirst($c) }}</button>
        @endforeach
    </div>
    @if(count($gallery))
    <div class="mt-8 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($gallery as $g)
        @php $tall = in_array($g['id'] % 12, [1, 4, 7, 10]); @endphp
        <button data-gal-cat="{{ $g['category'] }}" data-gal-title="{{ $g['title'] }} ({{ $g['unit'] }})" data-gal-unit="{{ $g['unit'] }}" data-gal-src="{{ $g['src'] }}"
            class="group relative rounded-3xl overflow-hidden text-left bg-nf-blue-soft {{ $tall ? 'row-span-2 min-h-72' : 'min-h-52' }} border border-nf-blue/15 hover:shadow-2xl transition">
            <img src="{{ $g['thumb'] }}" alt="{{ $g['title'] }}" loading="lazy" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500">
            <span class="absolute top-3 left-3 text-[11px] font-bold bg-white/90 rounded-full px-3 py-1 shadow">{{ $g['category'] }}</span>
            <span class="absolute bottom-0 inset-x-0 p-4 bg-gradient-to-t from-nf-ink/70 to-transparent text-white">
                <span class="block text-sm font-bold">{{ $g['title'] }}</span>
                <span class="block text-xs text-white/70">{{ $g['unit'] }}</span>
            </span>
        </button>
        @endforeach
    </div>
    @else
    <div class="mt-8 rounded-3xl bg-nf-cream border border-nf-blue/20 p-10 text-center">
        <p class="font-heading font-bold text-lg">Belum ada foto.</p>
        <p class="mt-1 text-sm text-nf-ink/60">Dokumentasi kegiatan akan tampil di sini setelah diunggah tim sekolah.</p>
    </div>
    @endif
</section>

<div id="lightbox" class="hidden fixed inset-0 z-[60] bg-nf-ink/90 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="mx-auto max-w-3xl mt-8 md:mt-16 rounded-3xl overflow-hidden bg-white">
        <img id="lbImg" src="" alt="Foto kegiatan" class="w-full max-h-[70vh] object-contain bg-nf-ink">
        <div class="p-6 flex items-start justify-between gap-4">
            <div>
                <h3 id="lbTitle" class="font-heading font-extrabold text-xl">Dokumentasi</h3>
                <p id="lbMeta" class="text-sm text-nf-ink/55 mt-1"></p>
            </div>
            <button data-lb-close class="shrink-0 grid place-items-center w-11 h-11 rounded-full bg-nf-ink text-white hover:bg-nf-blue-dark transition" aria-label="Tutup">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
            </button>
        </div>
    </div>
</div>
@endsection
