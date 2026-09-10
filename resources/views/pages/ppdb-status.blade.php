@extends('layouts.site')
@section('title', 'Cek Status Pendaftaran')

@section('content')
<section class="mx-auto max-w-2xl px-4 sm:px-6 py-14">
    <div class="text-center">
        <p class="text-xs font-bold tracking-[0.25em] uppercase text-nf-blue">PPDB 2026/2027</p>
        <h1 class="mt-3 font-heading font-extrabold text-3xl md:text-4xl">Cek status pendaftaran.</h1>
        <p class="mt-3 text-nf-ink/60">Masukkan nomor registrasi dan tanggal lahir anak.</p>
    </div>
    <form method="GET" action="{{ route('ppdb.status') }}" class="mt-8 rounded-3xl border border-nf-blue/25 bg-white shadow-xl p-7 grid gap-4">
        <label class="grid gap-1.5 text-sm font-bold">Nomor registrasi<input name="no" required value="{{ request('no') }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue" placeholder="cth: NF-2026-000123"></label>
        <label class="grid gap-1.5 text-sm font-bold">Tanggal lahir anak<input name="tgl" required type="date" value="{{ request('tgl') }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue"></label>
        <button class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-extrabold px-6 py-3 rounded-full transition">Cek Status</button>
    </form>

    @if(request()->filled(['no', 'tgl']))
        @if($result)
        <div class="mt-6 rounded-3xl bg-nf-cream border border-nf-blue/25 p-7">
            <p class="font-heading font-extrabold text-xl">{{ $result->child_name }}</p>
            <p class="text-sm text-nf-ink/55">{{ $result->registration_no }} · {{ strtoupper($result->jenjang) }}</p>
            <div class="mt-4 flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-nf-blue animate-pulse"></span>
                <p class="font-heading font-bold">Status: {{ $result->statusLabel() }}</p>
            </div>
            @if($result->admin_note)
            <p class="mt-3 text-sm rounded-2xl bg-nf-yellow/30 border border-nf-yellow-dark/40 px-4 py-3">{{ $result->admin_note }}</p>
            @endif
            <ol class="mt-4 space-y-2 text-sm">
                <li class="flex gap-2"><span class="text-nf-ink/45">{{ $result->created_at->format('d M Y') }}</span><span>Pendaftaran terkirim</span></li>
                @foreach($result->histories as $h)
                <li class="flex gap-2 flex-wrap"><span class="text-nf-ink/45">{{ $h->created_at->format('d M Y') }}</span><span><strong>{{ \App\Models\PpdbRegistration::STATUSES[$h->to_status] ?? $h->to_status }}</strong>@if($h->note) — {{ $h->note }}@endif</span></li>
                @endforeach
            </ol>
            <p class="mt-4 text-xs text-nf-ink/50">Punya akun orang tua? Pantau lebih lengkap di <a href="{{ route('portal.index') }}" class="font-bold text-nf-blue-dark underline">portal</a>.</p>
        </div>
        @else
        <div class="mt-6 rounded-3xl bg-red-50 border border-red-200 p-7 text-center">
            <p class="font-heading font-bold text-red-800">Data tidak ditemukan.</p>
            <p class="mt-1 text-sm text-red-700/80">Periksa kembali nomor registrasi dan tanggal lahir.</p>
        </div>
        @endif
    @endif
</section>
@endsection
