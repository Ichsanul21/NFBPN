@extends('layouts.app')
@section('title', 'Cek Status Pendaftaran')

@section('content')
<section class="mx-auto max-w-2xl px-4 sm:px-6 py-14">
    <div class="text-center">
        <p class="text-xs font-bold tracking-[0.25em] uppercase text-nf-blue">PPDB 2026/2027</p>
        <h1 class="mt-3 font-heading font-extrabold text-3xl md:text-4xl">Cek status pendaftaran.</h1>
        <p class="mt-3 text-nf-ink/60">Masukkan nomor registrasi & tanggal lahir anak.</p>
    </div>
    <form class="mt-8 rounded-3xl border border-nf-blue/25 bg-white shadow-xl p-7 grid gap-4" onsubmit="event.preventDefault(); document.getElementById('statusResult').classList.remove('hidden');">
        <label class="grid gap-1.5 text-sm font-bold">Nomor registrasi<input required class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue" placeholder="cth: NF-2026-000123"></label>
        <label class="grid gap-1.5 text-sm font-bold">Tanggal lahir anak<input required type="date" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue"></label>
        <button class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-extrabold px-6 py-3 rounded-full transition">Cek Status</button>
    </form>
    <div id="statusResult" class="hidden mt-6 rounded-3xl bg-nf-cream border border-nf-blue/25 p-7">
        <p class="text-sm text-nf-ink/55">Contoh hasil (simulasi front-end):</p>
        <div class="mt-3 flex items-center gap-3">
            <span class="w-3 h-3 rounded-full bg-nf-yellow-dark animate-pulse"></span>
            <p class="font-heading font-bold">Menunggu jadwal observasi — tim admisi akan menghubungi via WhatsApp.</p>
        </div>
        <div class="mt-4 flex gap-2 text-xs font-bold">
            <span class="rounded-full bg-nf-blue text-white px-3 py-1.5">Formulir diterima</span>
            <span class="rounded-full bg-nf-yellow text-nf-ink px-3 py-1.5">Observasi</span>
            <span class="rounded-full bg-nf-ink/10 text-nf-ink/50 px-3 py-1.5">Daftar ulang</span>
        </div>
    </div>
</section>
@endsection
