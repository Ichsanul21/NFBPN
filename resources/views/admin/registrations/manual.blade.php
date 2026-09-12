@extends('layouts.admin')
@section('title', 'Daftarkan Manual')
@section('page-title', 'Daftarkan Manual (Dibantu TU)')

@section('content')
<form method="GET" action="{{ route('admin.registrations.manual') }}" class="rounded-3xl bg-white border border-nf-blue/15 p-5 mb-4 flex flex-wrap items-end gap-3 max-w-3xl">
    <label class="grid gap-1.5 text-sm font-bold flex-1 min-w-52">Periode
        <select name="period_id" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5" onchange="this.form.submit()">
            <option value="">Pilih periode</option>
            @foreach($periods as $p)
            <option value="{{ $p->id }}" @selected($period?->id === $p->id)>{{ $p->name }} ({{ strtoupper($p->jenjang) }}){{ $p->is_active ? '' : ' [nonaktif]' }}</option>
            @endforeach
        </select>
    </label>
</form>

@if($period)
<div x-data="{ mode: '{{ old('ortu_mode', 'baru') }}' }">
    <form method="POST" action="{{ route('admin.registrations.manual.store') }}" enctype="multipart/form-data" class="grid gap-4 max-w-3xl">
        @csrf
        <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">
        <input type="hidden" name="period_id" value="{{ $period->id }}">

        <div class="rounded-3xl bg-white border border-nf-blue/15 p-5">
            <h2 class="font-heading font-extrabold">Akun orang tua — {{ $period->name }} ({{ strtoupper($period->jenjang) }})</h2>
            <div class="mt-3 flex gap-2 text-sm font-bold">
                <button type="button" @click="mode = 'baru'" class="px-4 py-2 rounded-full" :class="mode === 'baru' ? 'bg-nf-blue text-white' : 'bg-nf-ink/10'">Akun baru</button>
                <button type="button" @click="mode = 'lama'" class="px-4 py-2 rounded-full" :class="mode === 'lama' ? 'bg-nf-blue text-white' : 'bg-nf-ink/10'">Sudah punya akun</button>
            </div>
            <input type="hidden" name="ortu_mode" :value="mode">
            <div x-show="mode === 'lama'" class="mt-4 grid gap-1.5 text-sm font-bold">Email akun ortu
                <input name="ortu_email" type="email" value="{{ old('ortu_email') }}" placeholder="ortu@email.com" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
            </div>
            <div x-show="mode === 'baru'" class="mt-4 grid sm:grid-cols-2 gap-4">
                <label class="grid gap-1.5 text-sm font-bold">Nama<input name="new_name" value="{{ old('new_name') }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5"></label>
                <label class="grid gap-1.5 text-sm font-bold">Email<input name="new_email" type="email" value="{{ old('new_email') }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5"></label>
                <label class="grid gap-1.5 text-sm font-bold">WhatsApp<input name="new_whatsapp" value="{{ old('new_whatsapp') }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5"></label>
                <label class="grid gap-1.5 text-sm font-bold">Kata sandi sementara<input name="new_password" type="text" value="{{ old('new_password') }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5" placeholder="Min. 8 + besar-kecil + angka + simbol"></label>
            </div>
        </div>

        <div class="rounded-3xl bg-white border border-nf-blue/15 p-5">
            <h2 class="font-heading font-extrabold">Data calon siswa</h2>
            <div class="mt-3 grid sm:grid-cols-2 gap-4">
                <label class="grid gap-1.5 text-sm font-bold">Nama lengkap<input name="child_name" required value="{{ old('child_name') }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5"></label>
                <label class="grid gap-1.5 text-sm font-bold">Tanggal lahir<input name="child_birthdate" required type="date" value="{{ old('child_birthdate') }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5"></label>
                <label class="grid gap-1.5 text-sm font-bold">Jenis kelamin
                    <select name="gender" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5"><option value="">Pilih</option><option @selected(old('gender')==='Laki-laki')>Laki-laki</option><option @selected(old('gender')==='Perempuan')>Perempuan</option></select>
                </label>
            </div>
            <div class="mt-4 grid sm:grid-cols-2 gap-4">
                <label class="grid gap-1.5 text-sm font-bold">Nama ayah/ibu<input name="parent_name" required value="{{ old('parent_name') }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5"></label>
                <label class="grid gap-1.5 text-sm font-bold">No. WhatsApp<input name="whatsapp" required value="{{ old('whatsapp') }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5"></label>
            </div>
        </div>

        <div class="rounded-3xl bg-white border border-nf-blue/15 p-5" x-data="ppdbForm(@js($conditions), @js(old('answers', [])))" data-jenjang-section="{{ $period->jenjang }}">
            <h2 class="font-heading font-extrabold">Formulir tambahan</h2>
            @include('pages.partials.ppdb-fields', ['j' => $period->jenjang, 'fields' => $fields, 'preview' => false])
        </div>

        @if($commitment?->teks)
        <label class="flex items-start gap-3 text-sm rounded-3xl bg-white border border-nf-blue/15 p-5 cursor-pointer">
            <input type="checkbox" name="komitmen" value="1" required class="mt-1 rounded text-nf-blue">
            <span>Orang tua <strong>menyetujui Pernyataan Komitmen</strong> (disampaikan lisan/tertulis saat dibantu). <span class="block mt-1 text-nf-ink/60 font-normal whitespace-pre-line">{{ $commitment->teks }}</span></span>
        </label>
        @endif

        <div>
            <button class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-6 py-3 rounded-full transition">Simpan Pendaftaran (Dibantu TU)</button>
        </div>
    </form>
</div>
@include('partials.ppdb-logic')
@endif
@endsection
