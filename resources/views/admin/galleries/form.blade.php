@extends('layouts.admin')
@section('title', 'Unggah Foto')
@section('page-title', 'Unggah Foto')

@section('content')
<form method="POST" action="{{ route('admin.galleries.store') }}" enctype="multipart/form-data" class="rounded-3xl bg-white border border-nf-blue/15 p-6 grid gap-5 max-w-2xl">
    @csrf
    <label class="grid gap-1.5 text-sm font-bold">Judul (otomatis bernomor bila banyak foto)
        <input name="title" required value="{{ old('title') }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue" placeholder="cth: Market Day 2026">
    </label>
    <div class="grid sm:grid-cols-2 gap-5">
        <label class="grid gap-1.5 text-sm font-bold">Kategori
            <select name="category" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
                @foreach($categories as $c)<option value="{{ $c }}">{{ $c }}</option>@endforeach
            </select>
        </label>
        <label class="grid gap-1.5 text-sm font-bold">Unit
            <select name="unit" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
                <option value="">Umum</option>
                @foreach($units as $u)<option value="{{ $u }}">{{ $u }}</option>@endforeach
            </select>
        </label>
    </div>
    <label class="grid gap-1.5 text-sm font-bold">Foto (bisa banyak, maks 20, tiap 5MB)
        <input type="file" name="photos[]" required multiple accept="image/*" class="font-normal text-sm file:mr-3 file:rounded-full file:border-0 file:bg-nf-blue-soft file:text-nf-blue-dark file:font-bold file:px-4 file:py-2">
    </label>
    <p class="text-xs text-nf-ink/55">Foto otomatis diubah ke WebP + thumbnail agar ringan dibuka.</p>
    <div class="flex gap-3">
        <button class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-6 py-3 rounded-full transition">Unggah</button>
        <a href="{{ route('admin.galleries.index') }}" class="font-heading font-bold text-sm px-6 py-3 rounded-full border border-nf-blue/25 hover:bg-nf-blue-soft transition">Batal</a>
    </div>
</form>
@endsection
