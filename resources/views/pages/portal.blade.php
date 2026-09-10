@extends('layouts.site')
@section('title', 'Portal Orang Tua')

@section('content')
<section class="mx-auto max-w-4xl px-4 sm:px-6 py-12">
    <p class="text-xs font-bold tracking-[0.25em] uppercase text-nf-blue">Portal Orang Tua</p>
    <h1 class="mt-3 font-heading font-extrabold text-3xl">Halo, {{ auth()->user()->name }}.</h1>
    <p class="mt-2 text-nf-ink/60">Pantau status pendaftaran putra-putri Anda di sini.</p>

    <div class="mt-8 grid gap-4">
        @forelse($regs as $r)
        <a href="{{ route('portal.show', $r) }}" class="flex items-center gap-4 rounded-3xl border border-nf-blue/20 bg-white p-5 hover:shadow-xl transition">
            <div class="flex-1">
                <p class="font-heading font-extrabold text-lg">{{ $r->child_name }}</p>
                <p class="text-sm text-nf-ink/55">{{ $r->registration_no }} · {{ strtoupper($r->jenjang) }} · {{ $r->period?->name }}</p>
            </div>
            <span class="text-xs font-bold bg-nf-blue-soft text-nf-blue-dark rounded-full px-4 py-2 shrink-0">{{ $r->statusLabel() }}</span>
        </a>
        @empty
        <div class="rounded-3xl bg-nf-cream border border-nf-blue/20 p-10 text-center">
            <p class="font-heading font-bold text-lg">Belum ada pendaftaran.</p>
            <p class="mt-1 text-sm text-nf-ink/60">Mulai pendaftaran baru melalui halaman PPDB.</p>
            <a href="{{ route('ppdb') }}" class="mt-5 inline-block bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-6 py-3 rounded-full transition">Ke Halaman PPDB</a>
        </div>
        @endforelse
    </div>
</section>
@endsection
