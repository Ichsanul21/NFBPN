@extends('layouts.site')
@section('title', 'Berita & Kegiatan')

@section('content')
<section class="relative overflow-hidden bg-nf-ink text-white">
    <div class="absolute inset-0 islamic-pattern"></div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 py-14 md:py-20">
        <p class="text-xs font-bold tracking-[0.25em] uppercase text-nf-yellow">Publikasi</p>
        <h1 class="mt-3 font-heading font-extrabold text-3xl md:text-5xl">Berita & Kegiatan</h1>
        <p class="mt-4 text-white/70 max-w-2xl">Ikuti kabar, prestasi, dan inspirasi terbaru dari seluruh unit sekolah kami.</p>
    </div>
</section>
<section class="mx-auto max-w-7xl px-4 sm:px-6 py-12">
    @if(!count($news))
    <div class="rounded-3xl bg-white border border-nf-blue/20 p-10 text-center text-nf-ink/60">Belum ada berita yang diterbitkan.</div>
    @else
    @php $first = $news[0]; $rest = array_slice($news, 1); @endphp
    <a href="{{ route('berita.detail', $first['slug']) }}" class="grid md:grid-cols-2 rounded-3xl overflow-hidden border border-nf-blue/20 bg-nf-cream hover:shadow-2xl transition group">
        <div class="min-h-56 bg-gradient-to-br from-nf-blue-dark to-nf-blue relative overflow-hidden">
            <div class="absolute inset-0 islamic-pattern opacity-40"></div>
            <span class="absolute top-4 left-4 text-[11px] font-bold uppercase tracking-widest bg-nf-yellow text-nf-ink rounded-full px-3 py-1.5">{{ $first['category'] }} · Sorotan</span>
            <span class="absolute bottom-4 left-4 font-heading font-extrabold text-white/90 text-5xl">NF</span>
        </div>
        <div class="p-8">
            <p class="text-xs font-bold text-nf-ink/45">{{ $first['date'] }}</p>
            <h2 class="mt-2 font-heading font-extrabold text-2xl group-hover:text-nf-blue-dark transition">{{ $first['title'] }}</h2>
            <p class="mt-3 text-nf-ink/65 leading-relaxed">{{ $first['excerpt'] }}</p>
            <span class="mt-5 inline-flex font-bold text-nf-blue">Baca selengkapnya →</span>
        </div>
    </a>
    <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($rest as $n)
        <a href="{{ route('berita.detail', $n['slug']) }}" class="rounded-3xl border border-nf-blue/20 bg-white overflow-hidden hover:shadow-2xl hover:-translate-y-1 transition group">
            <div class="h-36 bg-gradient-to-br from-nf-blue-soft to-white relative overflow-hidden">
                <div class="absolute inset-0 islamic-pattern-light"></div>
                <span class="absolute top-3 left-3 text-[11px] font-bold uppercase tracking-widest bg-white text-nf-blue-dark rounded-full px-3 py-1 shadow">{{ $n['category'] }}</span>
            </div>
            <div class="p-6">
                <p class="text-xs font-bold text-nf-ink/45">{{ $n['date'] }}</p>
                <h3 class="mt-1.5 font-heading font-bold text-lg leading-snug group-hover:text-nf-blue-dark transition">{{ $n['title'] }}</h3>
                <p class="mt-2 text-sm text-nf-ink/60 leading-relaxed">{{ $n['excerpt'] }}</p>
            </div>
        </a>
        @endforeach
    </div>
    @endif
</section>
@endsection
