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
    <div class="grid sm:grid-cols-2 gap-5">
        <label class="grid gap-1.5 text-sm font-bold">Label pertanyaan
            <input name="label" required value="{{ old('label', $item->label) }}" placeholder="cth: Hobi anak" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue">
        </label>
        <label class="grid gap-1.5 text-sm font-bold">Kelompok/section (opsional)
            <input name="section" value="{{ old('section', $item->section) }}" placeholder="cth: Kesehatan" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue">
        </label>
    </div>
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
    <div class="rounded-2xl bg-nf-cream border border-nf-blue/20 p-5" x-data="{
        trigger: '{{ old('visible_if_field', $item->visible_if_field ?? '') }}',
        operator: '{{ old('visible_if_operator', $item->visible_if_operator ?? '') }}',
        triggers: @js($triggers->map(fn ($t) => ['key' => $t->key, 'label' => $t->label, 'type' => $t->type, 'options' => $t->options ?? []])->values()),
        get triggerOptions() {
            const t = this.triggers.find(x => x.key === this.trigger);
            return t ? t.options : [];
        },
        get needsValue() { return this.trigger !== '' && !['filled', 'empty'].includes(this.operator); },
        get isMulti() { return ['in', 'not_in'].includes(this.operator); }
    }">
        <h3 class="font-heading font-bold">Syarat tampil (opsional)</h3>
        <p class="mt-1 text-xs text-nf-ink/60">Field ini hanya tampil bila syarat terpenuhi. Bila wajib diisi, maka wajibnya hanya berlaku saat tampil. Pemicu hanya field sejenjang yang tidak berkondisi (maksimal 1 level).</p>
        <div class="mt-4 grid sm:grid-cols-3 gap-4">
            <label class="grid gap-1.5 text-sm font-bold">Jika field
                <select name="visible_if_field" x-model="trigger" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 bg-white">
                    <option value="">Selalu tampil</option>
                    <template x-for="t in triggers" :key="t.key">
                        <option :value="t.key" x-text="t.label"></option>
                    </template>
                </select>
            </label>
            <label class="grid gap-1.5 text-sm font-bold">Operator
                <select name="visible_if_operator" x-model="operator" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 bg-white">
                    <option value="">Pilih</option>
                    @foreach($operators as $k => $v)<option value="{{ $k }}">{{ $v }}</option>@endforeach
                </select>
            </label>
            <div class="grid gap-1.5 text-sm font-bold" x-show="needsValue">
                <span>Nilai pembanding</span>
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
    <div class="flex gap-3">
        <button class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-6 py-3 rounded-full transition">Simpan</button>
        <a href="{{ route('admin.fields.index', ['jenjang' => $item->jenjang]) }}" class="font-heading font-bold text-sm px-6 py-3 rounded-full border border-nf-blue/25 hover:bg-nf-blue-soft transition">Batal</a>
    </div>
</form>
@endsection
