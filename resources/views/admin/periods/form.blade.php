@extends('layouts.admin')
@section('title', ($item->exists ? 'Ubah' : 'Tambah').' Periode')
@section('page-title', ($item->exists ? 'Ubah' : 'Tambah').' Periode')

@section('content')
<form method="POST" action="{{ $item->exists ? route('admin.periods.update', $item) : route('admin.periods.store') }}" class="rounded-3xl bg-white border border-nf-blue/15 p-6 grid gap-5 max-w-2xl">
    @csrf
    @if($item->exists) @method('PUT') @endif
    <div class="grid sm:grid-cols-2 gap-5">
        <label class="grid gap-1.5 text-sm font-bold">Nama periode
            <input name="name" required value="{{ old('name', $item->name) }}" placeholder="Gelombang 1" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue">
        </label>
        <label class="grid gap-1.5 text-sm font-bold">Jenjang
            <select name="jenjang" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
                @foreach($jenjangs as $k => $v)<option value="{{ $k }}" @selected(old('jenjang', $item->jenjang) === $k)>{{ $v }}</option>@endforeach
            </select>
        </label>
    </div>
    <div class="grid sm:grid-cols-3 gap-5">
        <label class="grid gap-1.5 text-sm font-bold">Mulai
            <input type="date" name="starts_on" required value="{{ old('starts_on', $item->starts_on?->format('Y-m-d')) }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
        </label>
        <label class="grid gap-1.5 text-sm font-bold">Selesai
            <input type="date" name="ends_on" required value="{{ old('ends_on', $item->ends_on?->format('Y-m-d')) }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
        </label>
        <label class="grid gap-1.5 text-sm font-bold">Kuota
            <input type="number" name="quota" min="1" value="{{ old('quota', $item->quota) }}" placeholder="60" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
        </label>
    </div>
    <label class="grid gap-1.5 text-sm font-bold">Catatan
        <textarea name="note" rows="2" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">{{ old('note', $item->note) }}</textarea>
    </label>
    <label class="flex items-center gap-2 text-sm font-bold">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->is_active ?? true)) class="rounded text-nf-blue">
        Periode aktif
    </label>
    <div class="flex gap-3">
        <button class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-6 py-3 rounded-full transition">Simpan</button>
        <a href="{{ route('admin.periods.index') }}" class="font-heading font-bold text-sm px-6 py-3 rounded-full border border-nf-blue/25 hover:bg-nf-blue-soft transition">Batal</a>
    </div>
</form>
@if($item->exists)
@can('delete', $item)
<form method="POST" action="{{ route('admin.periods.destroy', $item) }}" onsubmit="return confirm('PERINGATAN: menghapus periode juga menghapus seluruh pendaftarnya. Lanjut?')" class="mt-4 max-w-2xl">
    @csrf @method('DELETE')
    <button class="text-sm font-bold text-red-600 hover:underline">Hapus periode ini (super admin)</button>
</form>
@endcan
@endif
@endsection
