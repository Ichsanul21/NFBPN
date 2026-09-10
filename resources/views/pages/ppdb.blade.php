@extends('layouts.site')
@section('title', 'PPDB Online 2026/2027')

@section('content')
<section class="relative overflow-hidden bg-nf-ink text-white">
    <div class="absolute inset-0 islamic-pattern opacity-40"></div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 py-14 md:py-20 grid lg:grid-cols-2 gap-10 items-center">
        <div>
            <p class="text-xs font-bold tracking-[0.25em] uppercase text-nf-yellow">PPDB 2026/2027</p>
            <h1 class="mt-3 font-heading font-extrabold text-3xl md:text-5xl leading-tight">Pendaftaran siswa baru, cukup 10 menit dari rumah.</h1>
            <p class="mt-4 text-white/70">Buat akun atau masuk, isi formulir online, dan tim admisi menghubungi maksimal 2 hari kerja untuk jadwal observasi/tes pemetaan.</p>
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
            <h2 class="font-heading font-extrabold text-xl">Periode yang sedang dibuka</h2>
            @if(count($periods))
            <ul class="mt-4 space-y-3 text-sm">
                @foreach($periods as $p)
                <li class="flex gap-3 rounded-2xl bg-nf-cream border border-nf-blue/20 p-4">
                    <span class="shrink-0 font-heading font-extrabold text-nf-blue-dark">{{ strtoupper($p->jenjang) }}</span>
                    <span class="text-nf-ink/70">{{ $p->name }} · {{ $p->starts_on->format('d M Y') }} s.d. {{ $p->ends_on->format('d M Y') }}{{ $p->quota ? ' · kuota '.$p->quota : '' }}</span>
                </li>
                @endforeach
            </ul>
            @else
            <p class="mt-4 text-sm text-nf-ink/60 rounded-2xl bg-nf-cream border border-nf-blue/20 p-4">Belum ada periode pendaftaran yang dibuka. Silakan hubungi kami via WhatsApp untuk info gelombang berikutnya.</p>
            @endif
            <div class="mt-4 rounded-2xl bg-nf-cream border border-nf-blue/20 p-4 text-sm text-nf-ink/70">
                Syarat umum: fotokopi KK dan akta lahir, pas foto anak, rapor terakhir (SDIT/SMPIT). Berkas asli dibawa saat daftar ulang.
            </div>
            <a href="#formulir" class="mt-5 block text-center bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-extrabold px-5 py-3.5 rounded-full transition">Isi Formulir di Bawah</a>
        </div>
    </div>
</section>

<section id="formulir" class="mx-auto max-w-4xl px-4 sm:px-6 py-12">
    @guest
    <div class="rounded-3xl border border-nf-blue/25 bg-white shadow-xl p-10 text-center">
        <h2 class="font-heading font-extrabold text-2xl">Masuk untuk mengisi formulir.</h2>
        <p class="mt-2 text-nf-ink/60">Buat akun orang tua gratis agar status pendaftaran terpantau di portal.</p>
        <div class="mt-6 flex flex-wrap justify-center gap-3">
            <a href="{{ route('login') }}" class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-extrabold px-7 py-3 rounded-full transition">Masuk</a>
            <a href="{{ route('register') }}" class="border border-nf-blue/30 hover:bg-nf-blue-soft font-heading font-bold px-7 py-3 rounded-full transition">Buat Akun</a>
        </div>
    </div>
    @else
    <div class="rounded-3xl border border-nf-blue/25 bg-white shadow-xl p-7 md:p-10">
        <h2 class="font-heading font-extrabold text-2xl">Formulir Pendaftaran</h2>
        <p class="mt-1 text-sm text-nf-ink/55">Data tersimpan aman dan hanya terlihat oleh tim admisi.</p>
        @if(session('error'))<p class="mt-4 text-sm font-bold text-red-700 bg-red-50 rounded-xl px-4 py-3">{{ session('error') }}</p>@endif
        <form method="POST" action="{{ route('ppdb.store') }}" enctype="multipart/form-data" class="mt-6 grid gap-6" x-data="ppdbForm(@js($conditionsByJenjang), @js(old('answers', [])))">
            @csrf
            <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">
            <div>
                <h3 class="font-heading font-bold text-nf-blue-dark">A. Periode dan Jenjang</h3>
                <div class="mt-3 grid sm:grid-cols-2 gap-4">
                    <label class="grid gap-1.5 text-sm font-bold">Periode
                        <select name="period_id" x-model="periodId" x-on:change="jenjang = $event.target.selectedOptions[0].dataset.jenjang || 'sdit'" required class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
                            <option value="">Pilih periode</option>
                            @foreach($periods as $p)
                            <option value="{{ $p->id }}" data-jenjang="{{ $p->jenjang }}" @selected(old('period_id') == $p->id)>{{ $p->name }} ({{ strtoupper($p->jenjang) }})</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="grid gap-1.5 text-sm font-bold">Jenjang (otomatis dari periode)
                        <select name="jenjang_display" disabled class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 bg-nf-cream" x-text="jenjang.toUpperCase()"></select>
                    </label>
                </div>
            </div>
            <div>
                <h3 class="font-heading font-bold text-nf-blue-dark">B. Data Calon Siswa</h3>
                <div class="mt-3 grid sm:grid-cols-2 gap-4">
                    <label class="grid gap-1.5 text-sm font-bold">Nama lengkap<input name="child_name" required value="{{ old('child_name') }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue" placeholder="Nama anak"></label>
                    <label class="grid gap-1.5 text-sm font-bold">Tanggal lahir<input name="child_birthdate" required type="date" value="{{ old('child_birthdate') }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue"></label>
                    <label class="grid gap-1.5 text-sm font-bold">Jenis kelamin
                        <select name="gender" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5"><option value="">Pilih</option><option @selected(old('gender')==='Laki-laki')>Laki-laki</option><option @selected(old('gender')==='Perempuan')>Perempuan</option></select>
                    </label>
                </div>
            </div>
            <div>
                <h3 class="font-heading font-bold text-nf-blue-dark">C. Data Orang Tua</h3>
                <div class="mt-3 grid sm:grid-cols-2 gap-4">
                    <label class="grid gap-1.5 text-sm font-bold">Nama ayah/ibu<input name="parent_name" required value="{{ old('parent_name', auth()->user()->name) }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue"></label>
                    <label class="grid gap-1.5 text-sm font-bold">No. WhatsApp<input name="whatsapp" required value="{{ old('whatsapp') }}" placeholder="08xx-xxxx-xxxx" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue"></label>
                </div>
            </div>
            @foreach($fieldsByJenjang as $j => $fields)
            <template x-if="jenjang === '{{ $j }}'">
                <div data-jenjang-section="{{ $j }}">
                    <h3 class="font-heading font-bold text-nf-blue-dark">D. Informasi Tambahan ({{ strtoupper($j) }})</h3>
                    @include('pages.partials.ppdb-fields', ['j' => $j, 'fields' => $fields, 'preview' => false])
                </div>
            </template>
            @endforeach
            <button class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-extrabold px-6 py-3.5 rounded-full transition shadow-lg shadow-nf-blue/30">Kirim Pendaftaran</button>
        </form>
    </div>
    @endguest
</section>
@endsection
