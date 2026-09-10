@extends('layouts.app')
@section('title', $item['title'])

@section('content')
<section class="bg-nf-cream border-b border-nf-blue/15">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 py-12">
        <a href="{{ route('berita') }}" class="text-sm font-bold text-nf-ink/55 hover:text-nf-blue-dark transition">← Semua berita</a>
        <div class="mt-4 flex items-center gap-2 text-xs font-bold">
            <span class="uppercase tracking-widest bg-nf-blue text-white rounded-full px-3 py-1.5">{{ $item['category'] }}</span>
            <span class="text-nf-ink/45">{{ $item['date'] }}</span>
        </div>
        <h1 class="mt-4 font-heading font-extrabold text-3xl md:text-4xl leading-tight">{{ $item['title'] }}</h1>
        <p class="mt-3 text-lg text-nf-ink/60">{{ $item['excerpt'] }}</p>
    </div>
</section>
<section class="mx-auto max-w-3xl px-4 sm:px-6 py-10">
    <div class="h-64 rounded-3xl bg-gradient-to-br from-nf-blue to-nf-blue relative overflow-hidden">
        <div class="absolute inset-0 islamic-pattern opacity-40"></div>
        <span class="absolute bottom-4 left-5 text-xs font-bold text-white/80 bg-black/25 rounded-full px-3 py-1.5">Foto ilustrasi. Akan diganti dokumentasi asli</span>
    </div>
    <article class="mt-8 space-y-5 text-lg leading-relaxed text-nf-ink/85">
        @foreach($item['body'] as $p)<p>{{ $p }}</p>@endforeach
    </article>
    <div class="islamic-divider my-10"></div>
    <h2 class="font-heading font-extrabold text-xl">Berita lainnya</h2>
    <div class="mt-4 grid gap-3">
        @foreach($others as $o)
        <a href="{{ route('berita.detail', $o['slug']) }}" class="flex items-center gap-4 rounded-2xl border border-nf-blue/20 px-5 py-4 hover:bg-nf-blue-soft/50 transition group">
            <span class="flex-1 font-heading font-bold group-hover:text-nf-blue-dark transition">{{ $o['title'] }}</span>
            <span class="text-xs font-bold text-nf-ink/45 shrink-0">{{ $o['date'] }}</span>
        </a>
        @endforeach
    </div>
</section>
@endsection
