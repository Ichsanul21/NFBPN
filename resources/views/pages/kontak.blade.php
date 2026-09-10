@extends('layouts.site')
@section('title', 'Kontak')

@section('content')
<section class="relative overflow-hidden bg-nf-ink text-white">
    <div class="absolute inset-0 islamic-pattern"></div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 py-14 md:py-20">
        <p class="text-xs font-bold tracking-[0.25em] uppercase text-nf-yellow">Kontak</p>
        <h1 class="mt-3 font-heading font-extrabold text-3xl md:text-5xl">Mari terhubung dengan kami.</h1>
        <p class="mt-4 text-white/70 max-w-2xl">Tanya PPDB, jadwalkan tur sekolah, atau sekadar bersilaturahmi. Kami senang mendengar dari Anda.</p>
    </div>
</section>
<section class="mx-auto max-w-7xl px-4 sm:px-6 py-12 grid lg:grid-cols-2 gap-8">
    <div class="rounded-3xl border border-nf-blue/25 bg-white shadow-xl p-7 md:p-9">
        <h2 class="font-heading font-extrabold text-2xl">Kirim pesan</h2>
        <form method="POST" action="{{ route('kontak.store') }}" class="mt-5 grid gap-4">
            @csrf
            <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">
            @if(session('success'))<p class="text-sm font-bold text-nf-blue-dark bg-nf-blue-soft rounded-xl px-4 py-3">{{ session('success') }}</p>@endif
            <div class="grid sm:grid-cols-2 gap-4">
                <label class="grid gap-1.5 text-sm font-bold">Nama<input name="name" required value="{{ old('name') }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue" placeholder="Nama Anda"></label>
                <label class="grid gap-1.5 text-sm font-bold">No. WhatsApp<input name="whatsapp" required type="tel" value="{{ old('whatsapp') }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue" placeholder="08xx-xxxx-xxxx"></label>
            </div>
            <label class="grid gap-1.5 text-sm font-bold">Keperluan
                <select name="subject" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5"><option>Info PPDB</option><option>Tur sekolah</option><option>Kerja sama</option><option>Lainnya</option></select>
            </label>
            <label class="grid gap-1.5 text-sm font-bold">Pesan<textarea name="message" required rows="4" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue" placeholder="Tulis pesan Anda...">{{ old('message') }}</textarea></label>
            <button class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-extrabold px-6 py-3 rounded-full transition">Kirim Pesan</button>
        </form>
    </div>
    <div class="grid gap-5 content-start">
        <div class="rounded-3xl bg-nf-cream border border-nf-blue/20 p-7">
            <h2 class="font-heading font-extrabold text-xl">Kampus Nurul Fikri Balikpapan</h2>
            <ul class="mt-4 space-y-2.5 text-nf-ink/75 text-sm">
                <li>Jl. Pendidikan No. 1, Balikpapan Selatan, Kalimantan Timur</li>
                <li>Senin–Jumat, 07.00–16.00 WITA · Sabtu 08.00–12.00 (admisi)</li>
                <li>(0542) 123-456 · info@nurulfikri-balikpapan.sch.id</li>
            </ul>
            <div class="mt-5 rounded-2xl overflow-hidden border border-nf-blue/20 bg-white">
                <iframe title="Peta lokasi sekolah" src="https://www.openstreetmap.org/export/embed.html?bbox=116.80%2C-1.30%2C116.90%2C-1.22&layer=mapnik&marker=-1.26%2C116.85" class="w-full h-56" loading="lazy"></iframe>
            </div>
        </div>
        <div class="rounded-3xl bg-nf-blue text-white p-7">
            <h2 class="font-heading font-extrabold text-xl">Butuh respon cepat?</h2>
            <p class="mt-2 text-sm text-white/80">Chat WhatsApp admisi. Balasan tersedia di jam operasional.</p>
            <a href="https://wa.me/62542123456" target="_blank" rel="noopener" class="mt-4 inline-block bg-white text-nf-blue-dark font-heading font-extrabold text-sm px-6 py-3 rounded-full hover:bg-nf-yellow hover:text-nf-ink transition">Chat WhatsApp</a>
        </div>
    </div>
</section>

@if(!empty($agenda))
<section class="mx-auto max-w-7xl px-4 sm:px-6 pb-14">
    <h2 class="font-heading font-extrabold text-2xl">Agenda terdekat</h2>
    <div class="mt-5 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($agenda as $a)
        <div class="rounded-3xl border border-nf-blue/20 bg-white p-5">
            <p class="font-heading font-extrabold text-2xl text-nf-blue-dark">{{ $a['day'] }} <span class="text-sm text-nf-ink/50">{{ $a['month'] }}</span></p>
            <p class="mt-2 font-bold text-sm">{{ $a['title'] }}</p>
            <p class="mt-1 text-xs text-nf-ink/55">{{ $a['time'] }} · {{ $a['place'] }}</p>
        </div>
        @endforeach
    </div>
</section>
@endif
@endsection
