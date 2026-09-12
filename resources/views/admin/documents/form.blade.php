@extends('layouts.admin')
@section('title', ($item->exists ? 'Ubah' : 'Tambah').' Dokumen')
@section('page-title', ($item->exists ? 'Ubah' : 'Tambah').' Dokumen')

@section('content')
<form method="POST" action="{{ $item->exists ? route('admin.documents.update', $item) : route('admin.documents.store') }}" class="rounded-3xl bg-white border border-nf-blue/15 p-6 grid gap-5 max-w-2xl">
    @csrf
    @if($item->exists) @method('PUT') @endif
    <div class="grid sm:grid-cols-2 gap-5">
        <label class="grid gap-1.5 text-sm font-bold">Jenjang
            <select name="jenjang" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
                @foreach($jenjangs as $k => $v)<option value="{{ $k }}" @selected(old('jenjang', $item->jenjang) === $k)>{{ $v }}</option>@endforeach
            </select>
        </label>
        <label class="grid gap-1.5 text-sm font-bold">Urutan
            <input type="number" name="urut" min="0" value="{{ old('urut', $item->urut ?? 0) }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
        </label>
    </div>
    <label class="grid gap-1.5 text-sm font-bold">Nama dokumen
        <input name="label" required value="{{ old('label', $item->label) }}" placeholder="cth: Kartu Keluarga (KK)" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue">
    </label>
    <label class="grid gap-1.5 text-sm font-bold">Keterangan untuk ortu
        <textarea name="deskripsi" rows="2" placeholder="cth: Pindaian KK terbaru, terbaca jelas." class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">{{ old('deskripsi', $item->deskripsi) }}</textarea>
    </label>
    <div class="grid gap-1.5 text-sm font-bold">Tipe file yang diterima
        <div class="flex flex-wrap gap-3 font-normal">
            @foreach($types as $k => $v)
            <label class="flex items-center gap-1.5 rounded-xl border border-nf-blue/25 px-4 py-2.5 cursor-pointer">
                <input type="checkbox" name="allowed[]" value="{{ $k }}" @checked(in_array($k, old('allowed', $item->allowed ?? array_keys($types)))) class="rounded text-nf-blue"> {{ $v }}
            </label>
            @endforeach
        </div>
    </div>
    <div class="grid sm:grid-cols-2 gap-5">
        <label class="grid gap-1.5 text-sm font-bold">Ukuran maks (KB)
            <input type="number" name="max_kb" required min="100" max="20480" value="{{ old('max_kb', $item->max_kb ?? 2048) }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
        </label>
        <div class="grid gap-2 content-start pt-1">
            <label class="flex items-center gap-2 text-sm font-bold rounded-xl border border-nf-blue/25 px-4 py-2.5 cursor-pointer">
                <input type="checkbox" name="compress" value="1" @checked(old('compress', $item->compress ?? true)) class="rounded text-nf-blue">
                Kompresi gambar otomatis
            </label>
        </div>
    </div>
    <div class="grid sm:grid-cols-2 gap-5">
        <label class="flex items-center gap-2 text-sm font-bold rounded-xl border border-nf-blue/25 px-4 py-3 cursor-pointer">
            <input type="checkbox" name="wajib" value="1" @checked(old('wajib', $item->wajib ?? true)) class="rounded text-nf-blue">
            Wajib diunggah
        </label>
        <label class="flex items-center gap-2 text-sm font-bold rounded-xl border border-nf-blue/25 px-4 py-3 cursor-pointer">
            <input type="checkbox" name="aktif" value="1" @checked(old('aktif', $item->aktif ?? true)) class="rounded text-nf-blue">
            Aktif di portal
        </label>
    </div>
    <div class="rounded-2xl bg-nf-cream border border-nf-blue/20 p-4" x-data="{
        trigger: '{{ old('visible_if_field', $item->visible_if_field ?? '') }}',
        operator: '{{ old('visible_if_operator', $item->visible_if_operator ?? '') }}',
        triggers: @js($triggers->map(fn ($t) => ['key' => $t->key, 'label' => $t->label, 'options' => $t->options ?? []])->values()),
        get triggerOptions() {
            const t = this.triggers.find(x => x.key === this.trigger);
            return t ? t.options : [];
        },
        get needsValue() { return this.trigger !== '' && !['filled', 'empty'].includes(this.operator); },
        get isMulti() { return ['in', 'not_in'].includes(this.operator); }
    }">
        <h3 class="font-heading font-bold text-sm">Minta dokumen ini hanya jika… (opsional)</h3>
        <div class="mt-3 grid gap-3">
            <label class="grid gap-1.5 text-sm font-bold">…jawaban field ini
                <select name="visible_if_field" x-model="trigger" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 bg-white">
                    <option value="">Selalu diminta</option>
                    <template x-for="t in triggers" :key="t.key">
                        <option :value="t.key" x-text="t.label"></option>
                    </template>
                </select>
            </label>
            <div class="grid sm:grid-cols-2 gap-3" x-show="trigger !== ''">
                <label class="grid gap-1.5 text-sm font-bold">memenuhi
                    <select name="visible_if_operator" x-model="operator" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 bg-white">
                        <option value="">Pilih syarat</option>
                        @foreach($operators as $k => $v)<option value="{{ $k }}">{{ $v }}</option>@endforeach
                    </select>
                </label>
                <div class="grid gap-1.5 text-sm font-bold" x-show="needsValue">
                    <span>nilai ini</span>
                    <template x-if="!isMulti">
                        <span>
                            <template x-if="triggerOptions.length">
                                <select name="visible_if_value" class="w-full font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 bg-white">
                                    @php $curVal = old('visible_if_value', is_array($item->visible_if_value) ? ($item->visible_if_value[0] ?? '') : ($item->visible_if_value ?? '')); @endphp
                                    <template x-for="o in triggerOptions" :key="o">
                                        <option :value="o" :selected="o === '{{ $curVal }}'" x-text="o"></option>
                                    </template>
                                </select>
                            </template>
                            <template x-if="!triggerOptions.length">
                                <input name="visible_if_value" value="{{ $curVal }}" placeholder="cth: Ya" class="w-full font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
                            </template>
                        </span>
                    </template>
                    <template x-if="isMulti">
                        @php $curVals = (array) old('visible_if_values', $item->visible_if_value ?? []); @endphp
                        <span class="flex flex-col gap-1.5 font-normal max-h-32 overflow-y-auto rounded-xl border border-nf-blue/25 bg-white px-4 py-2.5" x-data="{ saved: @js($curVals) }">
                            <template x-if="!triggerOptions.length">
                                <input name="visible_if_values[]" value="{{ implode(', ', $curVals) }}" placeholder="Pisahkan koma: A, B" class="w-full font-normal rounded-xl border border-nf-blue/25 px-3 py-1.5">
                            </template>
                            <template x-for="o in triggerOptions" :key="o">
                                <label class="flex items-center gap-2"><input type="checkbox" name="visible_if_values[]" :value="o" :checked="saved.includes(o)" class="rounded text-nf-blue"> <span x-text="o"></span></label>
                            </template>
                        </span>
                    </template>
                </div>
            </div>
        </div>
    </div>
    <div class="flex gap-3">
        <button class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-6 py-3 rounded-full transition">Simpan</button>
        <a href="{{ route('admin.documents.index', ['jenjang' => $item->jenjang]) }}" class="font-heading font-bold text-sm px-6 py-3 rounded-full border border-nf-blue/25 hover:bg-nf-blue-soft transition">Batal</a>
    </div>
</form>
@endsection
