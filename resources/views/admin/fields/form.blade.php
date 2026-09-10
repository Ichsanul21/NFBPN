@extends('layouts.admin')
@section('title', ($item->exists ? 'Ubah' : 'Tambah').' Field')
@section('page-title', ($item->exists ? 'Ubah' : 'Tambah').' Field')

@section('content')
<form method="POST" action="{{ $item->exists ? route('admin.fields.update', $item) : route('admin.fields.store') }}" class="rounded-3xl bg-white border border-nf-blue/15 p-6 grid gap-5 max-w-2xl">
    @csrf
    @if($item->exists) @method('PUT') @endif
    <div class="grid sm:grid-cols-2 gap-5">
        <label class="grid gap-1.5 text-sm font-bold">Jenjang
            <select name="jenjang" @disabled($item->is_core) class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
                @foreach($jenjangs as $k => $v)<option value="{{ $k }}" @selected(old('jenjang', $item->jenjang) === $k)>{{ $v }}</option>@endforeach
            </select>
        </label>
        <label class="grid gap-1.5 text-sm font-bold">Tipe field
            <select name="type" @disabled($item->is_core) class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
                @foreach($types as $k => $v)<option value="{{ $k }}" @selected(old('type', $item->type ?? 'text') === $k)>{{ $v }}</option>@endforeach
            </select>
        </label>
    </div>
    <label class="grid gap-1.5 text-sm font-bold">Label pertanyaan
        <input name="label" required value="{{ old('label', $item->label) }}" placeholder="cth: Hobi anak" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue">
    </label>
    <label class="grid gap-1.5 text-sm font-bold">Pilihan (satu per baris, khusus dropdown/radio/centang)
        <textarea name="options_text" rows="4" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5" placeholder="Antar jemput keluarga&#10;Kendaraan umum&#10;Lainnya">{{ old('options_text', is_array($item->options) ? implode("\n", $item->options) : '') }}</textarea>
    </label>
    <div class="grid sm:grid-cols-2 gap-5">
        <label class="grid gap-1.5 text-sm font-bold">Urutan tampil
            <input type="number" name="sort_order" min="0" value="{{ old('sort_order', $item->sort_order ?? 0) }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
        </label>
        <label class="flex items-center gap-2 text-sm font-bold pt-7">
            <input type="checkbox" name="is_required" value="1" @checked(old('is_required', $item->is_required)) class="rounded text-nf-blue">
            Wajib diisi
        </label>
    </div>
    <div class="flex gap-3">
        <button class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-6 py-3 rounded-full transition">Simpan</button>
        <a href="{{ route('admin.fields.index', ['jenjang' => $item->jenjang]) }}" class="font-heading font-bold text-sm px-6 py-3 rounded-full border border-nf-blue/25 hover:bg-nf-blue-soft transition">Batal</a>
    </div>
</form>
@endsection
