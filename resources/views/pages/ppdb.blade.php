@extends('layouts.app')
@section('title', 'PPDB Online 2026/2027')

@section('content')
<section class="relative overflow-hidden bg-nf-ink text-white">
    <div class="absolute inset-0 islamic-pattern"></div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 py-14 md:py-20 grid lg:grid-cols-2 gap-10 items-center">
        <div>
            <p class="text-xs font-bold tracking-[0.25em] uppercase text-nf-yellow">PPDB 2026/2027</p>
            <h1 class="mt-3 font-heading font-extrabold text-3xl md:text-5xl leading-tight">Pendaftaran siswa baru, cukup 10 menit dari rumah.</h1>
            <p class="mt-4 text-white/70">Isi formulir online — tim admisi menghubungi maksimal 2 hari kerja untuk jadwal observasi/tes pemetaan.</p>
            <div class="mt-6 grid grid-cols-3 gap-3 text-center">
                @foreach([['1','Isi Formulir'],['2','Observasi / Tes'],['3','Daftar Ulang']] as [$n,$t])
                <div class="rounded-2xl bg-white/8 border border-white/15 p-4">
                    <p class="font-heading font-extrabold text-2xl text-nf-yellow">{{ $n }}</p>
                    <p class="text-xs font-bold text-white/75 mt-1">{{ $t }}</p>
                </div>
                @endforeach
            </div>
        </div>
        <div class="rounded-3xl bg-white text-nf-ink p-7">
            <h2 class="font-heading font-extrabold text-xl">Jadwal & Persyaratan</h2>
            <ul class="mt-4 space-y-3 text-sm">
                <li class="flex gap-3"><span class="shrink-0 w-24 font-bold text-nf-blue-dark">Gel. 1</span><span class="text-nf-ink/70">Sep – Nov 2026 · prioritas jadwal + potongan formulir</span></li>
                <li class="flex gap-3"><span class="shrink-0 w-24 font-bold text-nf-blue-dark">Gel. 2</span><span class="text-nf-ink/70">Des 2026 – Feb 2027 · reguler</span></li>
                <li class="flex gap-3"><span class="shrink-0 w-24 font-bold text-nf-blue-dark">Gel. 3</span><span class="text-nf-ink/70">Mar – Jun 2027 · selama kuota tersedia</span></li>
            </ul>
            <div class="mt-4 rounded-2xl bg-nf-cream border border-nf-blue/20 p-4 text-sm text-nf-ink/70">
                Syarat umum: fotokopi KK & akta lahir, pas foto anak, rapor terakhir (SDIT/SMPIT). Berkas asli dibawa saat daftar ulang.
            </div>
            <a href="#formulir" class="mt-5 block text-center bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-extrabold px-5 py-3.5 rounded-full transition">Isi Formulir di Bawah</a>
        </div>
    </div>
</section>

<section id="formulir" class="mx-auto max-w-4xl px-4 sm:px-6 py-12">
    <div id="ppdbFormWrap" class="rounded-3xl border border-nf-blue/25 bg-white shadow-xl p-7 md:p-10">
        <h2 class="font-heading font-extrabold text-2xl">Formulir Pendaftaran</h2>
        <p class="mt-1 text-sm text-nf-ink/55">Fase 1: front-end saja — data belum tersimpan ke database.</p>
        <form id="ppdbForm" class="mt-6 grid gap-5">
            <div>
                <h3 class="font-heading font-bold text-nf-blue-dark">A. Jenjang Tujuan</h3>
                <div class="mt-3 grid grid-cols-2 sm:grid-cols-4 gap-2">
                    @foreach($units as $u)
                    <label class="cursor-pointer">
                        <input type="radio" name="jenjang" value="{{ $u['slug'] }}" class="peer sr-only" {{ $u['slug']==='sdit' ? 'checked' : '' }}>
                        <span class="block text-center text-sm font-bold rounded-2xl border-2 border-nf-blue/20 px-3 py-3 peer-checked:border-nf-blue peer-checked:bg-nf-blue-soft transition">{{ $u['name'] }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div>
                <h3 class="font-heading font-bold text-nf-blue-dark">B. Data Calon Siswa</h3>
                <div class="mt-3 grid sm:grid-cols-2 gap-4">
                    <label class="grid gap-1.5 text-sm font-bold">Nama lengkap<input required class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue" placeholder="Nama anak"></label>
                    <label class="grid gap-1.5 text-sm font-bold">Tanggal lahir<input required type="date" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue"></label>
                    <label class="grid gap-1.5 text-sm font-bold">Jenis kelamin
                        <select class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5"><option>Laki-laki</option><option>Perempuan</option></select>
                    </label>
                    <label class="grid gap-1.5 text-sm font-bold">Asal sekolah (jika ada)<input class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue" placeholder="TK/SD asal"></label>
                </div>
            </div>
            <div>
                <h3 class="font-heading font-bold text-nf-blue-dark">C. Data Orang Tua</h3>
                <div class="mt-3 grid sm:grid-cols-2 gap-4">
                    <label class="grid gap-1.5 text-sm font-bold">Nama ayah/ibu<input required class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue" placeholder="Nama orang tua"></label>
                    <label class="grid gap-1.5 text-sm font-bold">No. WhatsApp<input required type="tel" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue" placeholder="08xx-xxxx-xxxx"></label>
                </div>
            </div>
            <button class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-extrabold px-6 py-3.5 rounded-full transition shadow-lg shadow-nf-blue/30">Kirim Pendaftaran</button>
        </form>
    </div>
    <div id="ppdbSuccess" class="hidden rounded-3xl bg-nf-blue-soft border border-nf-blue/40 p-10 text-center">
        <span class="mx-auto grid place-items-center w-16 h-16 rounded-full bg-nf-blue text-white">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
        </span>
        <h2 class="mt-4 font-heading font-extrabold text-2xl">Pendaftaran terkirim!</h2>
        <p class="mt-2 text-nf-ink/65">Simulasi front-end berhasil. Di Fase 2 (backend), data tersimpan & nomor registrasi terbit otomatis.</p>
        <a href="{{ route('ppdb.status') }}" class="mt-6 inline-block bg-nf-ink text-white font-heading font-bold px-6 py-3 rounded-full hover:bg-nf-blue-dark transition">Cek Status Pendaftaran</a>
    </div>
</section>
@endsection
