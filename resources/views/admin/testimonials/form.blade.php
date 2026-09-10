@extends('layouts.admin')
@section('title', ($item->exists ? 'Ubah' : 'Tambah').' Testimoni')
@section('page-title', ($item->exists ? 'Ubah' : 'Tambah').' Testimoni')

@section('content')
<form method="POST" action="{{ $item->exists ? route('admin.testimonials.update', $item) : route('admin.testimonials.store') }}" class="rounded-3xl bg-white border border-nf-blue/15 p-6 grid gap-5 max-w-2xl">
    @csrf
    @if($item->exists) @method('PUT') @endif
    <label class="grid gap-1.5 text-sm font-bold">Kutipan
        <textarea name="quote" required rows="3" maxlength="1000" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue">{{ old('quote', $item->quote) }}</textarea>
    </label>
    <div class="grid sm:grid-cols-3 gap-5">
        <label class="grid gap-1.5 text-sm font-bold">Nama
            <input name="name" required value="{{ old('name', $item->name) }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
        </label>
        <label class="grid gap-1.5 text-sm font-bold">Peran
            <input name="role" value="{{ old('role', $item->role) }}" placeholder="Wali murid SDIT" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
        </label>
        <label class="grid gap-1.5 text-sm font-bold">Urutan
            <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $item->sort_order ?? 0) }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
        </label>
    </div>
    <label class="flex items-center gap-2 text-sm font-bold">
        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $item->is_published ?? true)) class="rounded text-nf-blue">
        Tampilkan di website
    </label>
    <div class="flex gap-3">
        <button class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-6 py-3 rounded-full transition">Simpan</button>
        <a href="{{ route('admin.testimonials.index') }}" class="font-heading font-bold text-sm px-6 py-3 rounded-full border border-nf-blue/25 hover:bg-nf-blue-soft transition">Batal</a>
    </div>
</form>
@endsection
