@extends('layouts.admin')
@section('title', ($item->exists ? 'Ubah' : 'Tambah').' Agenda')
@section('page-title', ($item->exists ? 'Ubah' : 'Tambah').' Agenda')

@section('content')
<form method="POST" action="{{ $item->exists ? route('admin.agendas.update', $item) : route('admin.agendas.store') }}" class="rounded-3xl bg-white border border-nf-blue/15 p-6 grid gap-5 max-w-2xl">
    @csrf
    @if($item->exists) @method('PUT') @endif
    <label class="grid gap-1.5 text-sm font-bold">Judul
        <input name="title" required value="{{ old('title', $item->title) }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue">
    </label>
    <label class="grid gap-1.5 text-sm font-bold">Deskripsi
        <textarea name="description" rows="3" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue">{{ old('description', $item->description) }}</textarea>
    </label>
    <div class="grid sm:grid-cols-3 gap-5">
        <label class="grid gap-1.5 text-sm font-bold">Tanggal
            <input type="date" name="date" required value="{{ old('date', $item->date?->format('Y-m-d')) }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
        </label>
        <label class="grid gap-1.5 text-sm font-bold">Jam
            <input name="time_label" value="{{ old('time_label', $item->time_label) }}" placeholder="08.00 - 11.00 WITA" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
        </label>
        <label class="grid gap-1.5 text-sm font-bold">Tempat
            <input name="place" value="{{ old('place', $item->place) }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
        </label>
    </div>
    <label class="flex items-center gap-2 text-sm font-bold">
        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $item->is_published ?? true)) class="rounded text-nf-blue">
        Tampilkan di website
    </label>
    <div class="flex gap-3">
        <button class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-6 py-3 rounded-full transition">Simpan</button>
        <a href="{{ route('admin.agendas.index') }}" class="font-heading font-bold text-sm px-6 py-3 rounded-full border border-nf-blue/25 hover:bg-nf-blue-soft transition">Batal</a>
    </div>
</form>
@endsection
