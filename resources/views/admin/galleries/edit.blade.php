@extends('layouts.admin')
@section('title', 'Ubah Foto')
@section('page-title', 'Ubah Foto')

@section('content')
<form method="POST" action="{{ route('admin.galleries.update', $item) }}" class="rounded-3xl bg-white border border-nf-blue/15 p-6 grid gap-5 max-w-2xl">
    @csrf @method('PUT')
    <img src="{{ $item->thumbUrl() }}" alt="{{ $item->title }}" class="w-full max-w-sm rounded-2xl object-cover">
    <label class="grid gap-1.5 text-sm font-bold">Judul
        <input name="title" required value="{{ old('title', $item->title) }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue">
    </label>
    <div class="grid sm:grid-cols-2 gap-5">
        <label class="grid gap-1.5 text-sm font-bold">Kategori
            <select name="category" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
                @foreach($categories as $c)<option value="{{ $c }}" @selected(old('category', $item->category) === $c)>{{ $c }}</option>@endforeach
            </select>
        </label>
        <label class="grid gap-1.5 text-sm font-bold">Unit
            <select name="unit" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
                <option value="">Umum</option>
                @foreach($units as $u)<option value="{{ $u }}" @selected(old('unit', $item->unit) === $u)>{{ $u }}</option>@endforeach
            </select>
        </label>
    </div>
    <div class="flex gap-3">
        <button class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-6 py-3 rounded-full transition">Simpan</button>
        <a href="{{ route('admin.galleries.index') }}" class="font-heading font-bold text-sm px-6 py-3 rounded-full border border-nf-blue/25 hover:bg-nf-blue-soft transition">Batal</a>
    </div>
</form>
@endsection
