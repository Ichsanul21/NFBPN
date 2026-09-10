@extends('layouts.app')
@section('title', $unit['full'])

@section('content')
<section class="relative overflow-hidden {{ $unit['color'] === 'green' ? 'bg-gradient-to-br from-nf-blue-dark via-nf-blue to-nf-blue-dark' : 'bg-gradient-to-br from-nf-blue-dark via-nf-blue to-nf-blue-dark' }} text-white">
    <div class="absolute inset-0 islamic-pattern opacity-40"></div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 py-14 md:py-20">
        <a href="{{ route('home') }}" class="text-xs font-bold text-white/70 hover:text-nf-yellow transition">← Kembali ke Beranda</a>
        <div class="mt-4 flex flex-wrap items-center gap-3">
            <span class="font-heading font-extrabold text-5xl md:text-6xl bg-white/15 border border-white/25 rounded-3xl px-6 py-3">{{ $unit['initial'] }}</span>
            <div>
                <h1 class="font-heading font-extrabold text-3xl md:text-5xl">{{ $unit['full'] }}</h1>
                <p class="mt-2 text-white/85 text-lg">{{ $unit['tagline'] }}</p>
            </div>
        </div>
        <div class="mt-6 flex flex-wrap gap-2 text-xs font-bold">
            <span class="bg-white/15 border border-white/20 rounded-full px-4 py-2">{{ $unit['ages'] }}</span>
            <span class="bg-white/15 border border-white/20 rounded-full px-4 py-2">{{ $unit['hours'] }}</span>
            <span class="bg-nf-yellow text-nf-ink rounded-full px-4 py-2">Rasio guru : siswa {{ $unit['ratio'] }}</span>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 sm:px-6 py-12 grid lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <h2 class="font-heading font-extrabold text-2xl">Tentang {{ $unit['name'] }}</h2>
        <p class="mt-3 text-nf-ink/75 leading-relaxed text-lg">{{ $unit['desc'] }}</p>
        <h3 class="mt-8 font-heading font-extrabold text-xl">Keunggulan Program</h3>
        <div class="mt-4 grid sm:grid-cols-2 gap-4">
            @foreach($unit['features'] as $f)
            <div class="flex gap-3 rounded-2xl border border-nf-blue/20 bg-nf-cream p-4">
                <svg class="w-5 h-5 shrink-0 text-nf-blue-dark mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.7-9.3a1 1 0 0 0-1.4-1.4L9 10.6 7.7 9.3a1 1 0 0 0-1.4 1.4l2 2a1 1 0 0 0 1.4 0l4-4Z" clip-rule="evenodd"/></svg>
                <span class="text-sm font-semibold text-nf-ink/85">{{ $f }}</span>
            </div>
            @endforeach
        </div>
    </div>
    <aside class="grid gap-5 content-start">
        <div class="rounded-3xl bg-nf-ink text-white p-7 relative overflow-hidden">
            <div class="absolute inset-0 islamic-pattern opacity-50"></div>
            <div class="relative">
                <h3 class="font-heading font-extrabold text-xl">Tertarik dengan {{ $unit['name'] }}?</h3>
                <p class="mt-2 text-sm text-white/70">Isi formulir PPDB online dan pilih jenjang {{ $unit['full'] }}.</p>
                <a href="{{ route('ppdb') }}" class="mt-5 block text-center bg-nf-yellow text-nf-ink font-heading font-extrabold px-5 py-3 rounded-full hover:bg-white transition">Daftar {{ $unit['name'] }}</a>
                <a href="{{ route('kontak') }}" class="mt-2 block text-center border border-white/25 font-heading font-bold text-sm px-5 py-3 rounded-full hover:bg-white/10 transition">Jadwalkan Tur Sekolah</a>
            </div>
        </div>
        <div class="rounded-3xl border border-nf-blue/20 p-7">
            <h3 class="font-heading font-bold">Jenjang Lainnya</h3>
            <div class="mt-3 grid gap-2">
                @foreach($units as $u)
                @if($u['slug'] !== $unit['slug'])
                <a href="{{ route('unit', $u['slug']) }}" class="flex items-center justify-between rounded-xl bg-nf-cream hover:bg-nf-blue-soft px-4 py-3 font-bold text-sm transition">{{ $u['full'] }} <span>→</span></a>
                @endif
                @endforeach
            </div>
        </div>
    </aside>
</section>
@endsection
