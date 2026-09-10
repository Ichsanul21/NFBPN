@extends('layouts.site')
@section('title', 'Detail Pendaftaran')

@section('content')
<section class="mx-auto max-w-4xl px-4 sm:px-6 py-12">
    <a href="{{ route('portal.index') }}" class="text-sm font-bold text-nf-ink/55 hover:text-nf-blue-dark transition">← Portal saya</a>
    <div class="mt-4 rounded-3xl bg-nf-ink text-white p-7 relative overflow-hidden">
        <div class="absolute inset-0 islamic-pattern opacity-30"></div>
        <div class="relative flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="font-heading font-extrabold text-2xl">{{ $item->child_name }}</h1>
                <p class="text-sm text-white/70">{{ $item->registration_no }} · {{ strtoupper($item->jenjang) }} · {{ $item->period?->name }}</p>
            </div>
            <span class="text-xs font-bold bg-nf-yellow text-nf-ink rounded-full px-4 py-2">{{ $item->statusLabel() }}</span>
        </div>
    </div>

    @php
    $steps = ['terkirim', 'verifikasi', 'observasi', 'lolos', 'daftar_ulang', 'aktif'];
    $labels = \App\Models\PpdbRegistration::STATUSES;
    $pos = array_search($item->status, $steps);
    @endphp
    @if($pos !== false)
    <div class="mt-6 flex items-center gap-1.5 overflow-x-auto pb-1">
        @foreach($steps as $i => $s)
        <div class="flex items-center gap-1.5 shrink-0">
            <span class="text-[11px] font-bold rounded-full px-3 py-1.5 {{ $i <= $pos ? 'bg-nf-blue text-white' : 'bg-nf-ink/10 text-nf-ink/50' }}">{{ $labels[$s] }}</span>
            @if(!$loop->last)<span class="w-4 h-0.5 {{ $i < $pos ? 'bg-nf-blue' : 'bg-nf-ink/15' }}"></span>@endif
        </div>
        @endforeach
    </div>
    @endif

    @if($item->admin_note)
    <div class="mt-6 rounded-3xl bg-nf-yellow/30 border border-nf-yellow-dark/40 p-5 text-sm">
        <p class="font-bold text-xs uppercase text-nf-ink/60">Catatan dari sekolah</p>
        <p class="mt-1">{{ $item->admin_note }}</p>
    </div>
    @endif

    <div class="mt-6 rounded-3xl border border-nf-blue/20 bg-white p-6">
        <h2 class="font-heading font-extrabold">Riwayat</h2>
        <ol class="mt-3 space-y-2 text-sm">
            <li class="flex gap-2"><span class="text-nf-ink/45">{{ $item->created_at->format('d M Y H:i') }}</span><span>Pendaftaran terkirim</span></li>
            @foreach($item->histories as $h)
            <li class="flex gap-2 flex-wrap">
                <span class="text-nf-ink/45">{{ $h->created_at->format('d M Y H:i') }}</span>
                <span><strong>{{ $labels[$h->to_status] ?? $h->to_status }}</strong>@if($h->note) — {{ $h->note }}@endif</span>
            </li>
            @endforeach
        </ol>
    </div>

    <div class="mt-6 rounded-3xl border border-nf-blue/20 bg-white p-6">
        <h2 class="font-heading font-extrabold">Unggah berkas tambahan</h2>
        <p class="mt-1 text-sm text-nf-ink/60"> Contoh: rapor terbaru, sertifikat, atau dokumen susulan. Maksimal 5MB (PDF/JPG/PNG/WebP).</p>
        <form method="POST" action="{{ route('portal.berkas', $item) }}" enctype="multipart/form-data" class="mt-4 grid sm:grid-cols-[1fr_1fr_auto] gap-3">
            @csrf
            <input type="file" name="berkas" required accept=".pdf,.jpg,.jpeg,.png,.webp" class="text-sm file:mr-3 file:rounded-full file:border-0 file:bg-nf-blue-soft file:text-nf-blue-dark file:font-bold file:px-4 file:py-2">
            <input name="keterangan" placeholder="Keterangan (cth: Rapor semester 5)" class="text-sm rounded-xl border border-nf-blue/25 px-4 py-2.5">
            <button class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-6 py-2.5 rounded-full transition">Unggah</button>
        </form>
        @php $tambahan = ($item->answers ?? [])['berkas_tambahan'] ?? []; @endphp
        @if(count($tambahan))
        <ul class="mt-4 space-y-2 text-sm">
            @foreach($tambahan as $b)
            <li class="flex items-center justify-between gap-3 rounded-xl bg-nf-cream px-4 py-2.5">
                <span class="font-bold">{{ $b['keterangan'] ?: 'Berkas' }} <span class="font-normal text-nf-ink/50">· {{ $b['diunggah'] }}</span></span>
                <a href="{{ asset('storage/'.$b['path']) }}" target="_blank" class="font-bold text-nf-blue-dark hover:underline shrink-0">Lihat</a>
            </li>
            @endforeach
        </ul>
        @endif
    </div>
</section>
@endsection
