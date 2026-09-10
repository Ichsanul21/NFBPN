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
    <div class="rounded-3xl border border-nf-blue/25 bg-white shadow-xl p-7 md:p-10">
        <h2 class="font-heading font-extrabold text-2xl text-center">Cara mendaftar: 3 langkah mudah.</h2>
        <ol class="mt-6 grid gap-3 sm:grid-cols-3 text-sm">
            <li class="rounded-2xl bg-nf-cream border border-nf-blue/20 p-4"><span class="font-heading font-extrabold text-nf-blue-dark">1. Pilih jenjang</span><span class="block mt-1 text-nf-ink/60">Daycare, KBIT, SDIT, atau SMPIT sesuai usia anak.</span></li>
            <li class="rounded-2xl bg-nf-cream border border-nf-blue/20 p-4"><span class="font-heading font-extrabold text-nf-blue-dark">2. Isi data diri</span><span class="block mt-1 text-nf-ink/60">Data calon siswa dan orang tua.</span></li>
            <li class="rounded-2xl bg-nf-cream border border-nf-blue/20 p-4"><span class="font-heading font-extrabold text-nf-blue-dark">3. Isi formulir</span><span class="block mt-1 text-nf-ink/60">Pertanyaan tambahan sesuai jenjang, lalu kirim.</span></li>
        </ol>
        <div class="mt-6 grid grid-cols-2 lg:grid-cols-4 gap-3">
            @foreach($units as $u)
            @php $openCount = count($periodsByJenjang[$u['slug']] ?? []); @endphp
            <div class="rounded-2xl border border-nf-blue/15 bg-white p-4">
                <span class="grid place-items-center w-10 h-10 rounded-xl bg-gradient-to-br from-nf-blue to-nf-blue-dark text-white font-heading font-extrabold">{{ $u['initial'] }}</span>
                <span class="mt-2 block font-heading font-bold text-sm">{{ $u['full'] }}</span>
                <span class="block text-xs text-nf-ink/55 mt-0.5">{{ $u['ages'] }}</span>
                <span class="mt-2 inline-block text-[11px] font-bold rounded-full px-2.5 py-1 {{ $openCount ? 'bg-nf-green-soft text-nf-green-dark' : 'bg-nf-ink/10 text-nf-ink/50' }}">{{ $openCount ? 'Pendaftaran dibuka' : 'Belum dibuka' }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endguest
    <div class="rounded-3xl border border-nf-blue/25 bg-white shadow-xl p-7 md:p-10">
        <h2 class="font-heading font-extrabold text-2xl">Formulir Pendaftaran</h2>
        <p class="mt-1 text-sm text-nf-ink/55">Data tersimpan aman dan hanya terlihat oleh tim admisi.</p>
        <div id="js-fail" class="hidden mt-4 rounded-2xl bg-red-50 border border-red-200 px-5 py-4 text-sm font-bold text-red-800">
            Formulir tidak dapat dimuat karena JavaScript gagal berjalan. Matikan pemblokir skrip/adblock untuk halaman ini lalu muat ulang. Bila berlanjut, hubungi kami via WhatsApp.
        </div>
        @if(session('error'))<p class="mt-4 text-sm font-bold text-red-700 bg-red-50 rounded-xl px-4 py-3">{{ session('error') }}</p>@endif
        <form method="POST" action="{{ route('ppdb.store') }}" enctype="multipart/form-data" class="mt-6" x-data="ppdbWizard(@js($conditionsByJenjang), @js(old('answers', [])), @js($periodsByJenjang), '{{ old('period_id') }}')">
            @csrf
            <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">
            <input type="hidden" name="period_id" :value="periodId">
            @if($errors->any())
            <div class="mb-5 rounded-2xl bg-red-50 border border-red-200 px-5 py-4 text-sm font-bold text-red-800">
                <p>Periksa kembali isian berikut:</p>
                <ul class="mt-1 list-disc list-inside font-normal">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif
            <ol class="flex items-center gap-1.5 text-[11px] sm:text-xs font-bold">
                <template x-for="(label, i) in ['Akun', 'Jenjang', 'Formulir']" :key="label">
                    <li class="flex items-center gap-1.5" :class="i < 2 ? 'flex-1' : ''">
                        <span class="flex items-center gap-1.5">
                            <span class="grid place-items-center w-6 h-6 rounded-full" :class="step > i + 1 ? 'bg-nf-green text-white' : (step === i + 1 ? 'bg-nf-blue text-white' : 'bg-nf-ink/10 text-nf-ink/50')" x-text="i + 1"></span>
                            <span :class="step === i + 1 ? 'text-nf-ink' : 'text-nf-ink/50'" x-text="label"></span>
                        </span>
                        <span x-show="i < 2" class="flex-1 h-0.5 rounded" :class="step > i + 1 ? 'bg-nf-green' : 'bg-nf-ink/10'"></span>
                    </li>
                </template>
            </ol>

            {{-- Langkah 1: akun orang tua --}}
            <div data-step="1" x-show="step === 1" class="mt-6">
                @guest
                <h3 class="font-heading font-bold text-nf-blue-dark">Langkah 1. Data dan akun orang tua</h3>
                <p class="mt-1 text-sm text-nf-ink/60">Akun dibuat otomatis saat formulir dikirim. Sudah punya akun? <a href="{{ route('login') }}" class="font-bold text-nf-blue-dark underline">Masuk dulu</a>.</p>
                <div class="mt-3 grid sm:grid-cols-2 gap-4">
                    <label class="grid gap-1.5 text-sm font-bold">Nama ayah/ibu<input name="parent_name" required value="{{ old('parent_name') }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue" placeholder="Nama lengkap Anda"></label>
                    <label class="grid gap-1.5 text-sm font-bold">Email (untuk masuk portal)<input name="email" required type="email" value="{{ old('email') }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue" placeholder="nama@email.com"></label>
                    <label class="grid gap-1.5 text-sm font-bold">No. WhatsApp<input name="whatsapp" required value="{{ old('whatsapp') }}" placeholder="08xx-xxxx-xxxx" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue"></label>
                    <label class="grid gap-1.5 text-sm font-bold">Kata sandi (min. 8 karakter)<input name="password" required type="password" minlength="8" autocomplete="new-password" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue"></label>
                    <label class="grid gap-1.5 text-sm font-bold sm:col-span-2">Ulangi kata sandi<input name="password_confirmation" required type="password" minlength="8" autocomplete="new-password" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue"></label>
                </div>
                @else
                <h3 class="font-heading font-bold text-nf-blue-dark">Langkah 1. Akun orang tua</h3>
                <div class="mt-3 flex flex-wrap items-center gap-3 rounded-2xl bg-nf-green-soft border border-nf-green/30 px-5 py-4">
                    <span class="grid place-items-center w-10 h-10 rounded-full bg-nf-green text-white font-heading font-bold">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                    <p class="text-sm"><span class="font-bold">Masuk sebagai {{ auth()->user()->name }}</span><span class="block text-nf-ink/60">{{ auth()->user()->email }}</span></p>
                    <a href="{{ route('portal.index') }}" class="ml-auto text-xs font-bold text-nf-blue-dark hover:underline">Lihat portal saya →</a>
                </div>
                @endguest
                <div class="mt-6 flex justify-end">
                    <button type="button" @click="nextStep()" class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-7 py-3 rounded-full transition">Lanjut →</button>
                </div>
            </div>

            {{-- Langkah 2: pilih jenjang --}}
            <div data-step="2" x-show="step === 2" class="mt-6" x-cloak>
                <h3 class="font-heading font-bold text-nf-blue-dark">Langkah 2. Pilih jenjang</h3>
                <p class="mt-1 text-sm text-nf-ink/60">Gelombang pendaftaran mengikuti jenjang yang dipilih.</p>
                <div class="mt-3 grid grid-cols-2 lg:grid-cols-4 gap-3">
                    @foreach($units as $u)
                    <button type="button" @click="chooseJenjang('{{ $u['slug'] }}')" class="rounded-2xl border-2 p-4 text-left transition" :class="jenjang === '{{ $u['slug'] }}' ? 'border-nf-blue bg-nf-blue-soft/60 shadow-lg' : 'border-nf-blue/15 bg-white hover:border-nf-blue/50'">
                        <span class="grid place-items-center w-10 h-10 rounded-xl bg-gradient-to-br from-nf-blue to-nf-blue-dark text-white font-heading font-extrabold">{{ $u['initial'] }}</span>
                        <span class="mt-2 block font-heading font-bold">{{ $u['full'] }}</span>
                        <span class="block text-xs text-nf-ink/55 mt-0.5">{{ $u['ages'] }}</span>
                        <span class="mt-2 inline-block text-[11px] font-bold rounded-full px-2.5 py-1" :class="(periodsByJenjang['{{ $u['slug'] }}'] || []).length ? 'bg-nf-green-soft text-nf-green-dark' : 'bg-nf-ink/10 text-nf-ink/50'" x-text="(periodsByJenjang['{{ $u['slug'] }}'] || []).length ? 'Pendaftaran dibuka' : 'Belum dibuka'"></span>
                    </button>
                    @endforeach
                </div>
                <div class="mt-4" x-show="jenjang !== ''">
                    <template x-if="(periodsByJenjang[jenjang] || []).length > 1">
                        <label class="grid gap-1.5 text-sm font-bold">Pilih gelombang
                            <select x-model="periodId" required class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 bg-white">
                                <option value="">Pilih gelombang</option>
                                <template x-for="p in (periodsByJenjang[jenjang] || [])" :key="p.id">
                                    <option :value="String(p.id)" x-text="p.name"></option>
                                </template>
                            </select>
                        </label>
                    </template>
                    <template x-if="(periodsByJenjang[jenjang] || []).length === 1">
                        <p class="text-sm rounded-2xl bg-nf-green-soft border border-nf-green/30 px-4 py-3 font-bold text-nf-ink">Gelombang <span x-text="(periodsByJenjang[jenjang][0] || {}).name"></span> — otomatis terpilih.</p>
                    </template>
                    <template x-if="(periodsByJenjang[jenjang] || []).length === 0">
                        <p class="text-sm rounded-2xl bg-nf-ink/5 border border-nf-ink/10 px-4 py-3 font-bold text-nf-ink/60">Pendaftaran jenjang ini belum dibuka. Silakan hubungi kami via WhatsApp.</p>
                    </template>
                </div>
                <div class="mt-6 flex justify-between">
                    <button type="button" @click="step = 1" class="font-heading font-bold text-sm px-6 py-3 rounded-full border border-nf-blue/25 hover:bg-nf-blue-soft transition">← Kembali</button>
                    <button type="button" @click="nextStep()" class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-7 py-3 rounded-full transition">Lanjut →</button>
                </div>
            </div>

            {{-- Langkah 3: data anak + formulir lengkap --}}
            <div data-step="3" x-show="step === 3" class="mt-6 grid gap-6" x-cloak>
                <div>
                    <h3 class="font-heading font-bold text-nf-blue-dark">Langkah 3. Data calon siswa <span class="text-nf-ink/45 font-normal" x-text="'(' + jenjang.toUpperCase() + ')'"></span></h3>
                    <div class="mt-3 grid sm:grid-cols-2 gap-4">
                        <label class="grid gap-1.5 text-sm font-bold">Nama lengkap<input name="child_name" required value="{{ old('child_name') }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue" placeholder="Nama anak"></label>
                        <label class="grid gap-1.5 text-sm font-bold">Tanggal lahir<input name="child_birthdate" required type="date" value="{{ old('child_birthdate') }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue"></label>
                        <label class="grid gap-1.5 text-sm font-bold">Jenis kelamin
                            <select name="gender" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5"><option value="">Pilih</option><option @selected(old('gender')==='Laki-laki')>Laki-laki</option><option @selected(old('gender')==='Perempuan')>Perempuan</option></select>
                        </label>
                    </div>
                </div>
                @auth
                <div>
                    <h3 class="font-heading font-bold text-nf-blue-dark">Kontak orang tua</h3>
                    <div class="mt-3 grid sm:grid-cols-2 gap-4">
                        <label class="grid gap-1.5 text-sm font-bold">Nama ayah/ibu<input name="parent_name" required value="{{ old('parent_name', auth()->user()->name) }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue"></label>
                        <label class="grid gap-1.5 text-sm font-bold">No. WhatsApp<input name="whatsapp" required value="{{ old('whatsapp') }}" placeholder="08xx-xxxx-xxxx" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue"></label>
                    </div>
                </div>
                @endauth
                @foreach($fieldsByJenjang as $j => $fields)
                <template x-if="jenjang === '{{ $j }}'">
                    <div data-jenjang-section="{{ $j }}">
                        <h3 class="font-heading font-bold text-nf-blue-dark">Formulir tambahan ({{ strtoupper($j) }})</h3>
                        @include('pages.partials.ppdb-fields', ['j' => $j, 'fields' => $fields, 'preview' => false])
                    </div>
                </template>
                @endforeach
                <div class="mt-6 flex justify-between">
                    <button type="button" @click="step = 2" class="font-heading font-bold text-sm px-6 py-3 rounded-full border border-nf-blue/25 hover:bg-nf-blue-soft transition">← Kembali</button>
                    <button class="bg-nf-green hover:bg-nf-green-dark text-white font-heading font-extrabold px-7 py-3 rounded-full transition shadow-lg shadow-nf-green/30">Kirim Pendaftaran</button>
                </div>
            </div>
        </form>
    </div>
</section>

@include('partials.ppdb-logic')
<script>
window.addEventListener('load', function () {
    setTimeout(function () {
        if (typeof window.ppdbWizard === 'undefined' || typeof window.Alpine === 'undefined') {
            var b = document.getElementById('js-fail');
            if (b) b.classList.remove('hidden');
        }
    }, 2500);
});
</script>
@endsection
