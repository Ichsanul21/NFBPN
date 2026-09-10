@extends('layouts.admin')
@section('title', 'Form Builder')
@section('page-title', 'Form Builder')

@section('content')
{{-- Tab jenjang --}}
<div class="flex flex-wrap items-center gap-2 mb-4">
    @foreach($jenjangs as $k => $v)
    <a href="{{ route('admin.fields.index', ['jenjang' => $k]) }}" class="text-xs font-bold px-4 py-2 rounded-full {{ $jenjang === $k ? 'bg-nf-blue text-white' : 'bg-white border border-nf-blue/20' }}">{{ $v }}</a>
    @endforeach
    <a href="{{ route('ppdb') }}" target="_blank" class="ml-auto text-xs font-bold text-nf-blue-dark hover:underline">Buka form asli →</a>
</div>

{{-- Tambah pertanyaan --}}
<div x-data="{ open: false }" class="mb-4">
    <button @click="open = !open" class="inline-flex items-center gap-2 bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-5 py-2.5 rounded-full transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 5v14M5 12h14"/></svg>
        Tambah pertanyaan
    </button>
    <div x-show="open" x-cloak class="mt-3 grid grid-cols-2 sm:grid-cols-4 gap-3">
        @foreach($types as $k => $v)
        <form method="POST" action="{{ route('admin.fields.quick') }}">
            @csrf
            <input type="hidden" name="jenjang" value="{{ $jenjang }}">
            <input type="hidden" name="type" value="{{ $k }}">
            <button class="w-full text-left rounded-2xl bg-white border border-nf-blue/20 p-4 hover:border-nf-blue hover:shadow-lg transition">
                <span class="block font-heading font-bold text-sm">{{ $v }}</span>
                <span class="block text-xs text-nf-ink/50 mt-0.5">Klik untuk menambah</span>
            </button>
        </form>
        @endforeach
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-4 items-start">
    {{-- Daftar field --}}
    <div>
        <p class="text-xs font-bold text-nf-ink/55 mb-2">Seret untuk menyusun ulang. Klik kartu untuk mengedit.</p>
        <div id="field-list" class="grid gap-3" data-reorder-url="{{ route('admin.fields.reorder') }}" data-jenjang="{{ $jenjang }}" data-token="{{ csrf_token() }}">
            @forelse($fields as $f)
            <div class="field-card rounded-2xl bg-white border border-nf-blue/15 p-4 {{ $f->is_active ? '' : 'opacity-60' }}" data-id="{{ $f->id }}">
                <div class="flex items-center gap-3">
                    <span class="drag-handle cursor-grab active:cursor-grabbing text-nf-ink/35 hover:text-nf-blue-dark shrink-0" title="Seret untuk pindah">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M7 4h2v2H7V4zm4 0h2v2h-2V4zM7 8h2v2H7V8zm4 0h2v2h-2V8zm-4 4h2v2H7v-2zm4 0h2v2h-2v-2zm-4 4h2v2H7v-2zm4 0h2v2h-2v-2z"/></svg>
                    </span>
                    <a href="{{ route('admin.fields.index', ['jenjang' => $jenjang, 'edit' => $f->id]) }}" class="flex-1 min-w-0 text-left">
                        <p class="font-heading font-bold truncate">{{ $f->label }}</p>
                        <p class="mt-1 flex flex-wrap gap-1.5">
                            <span class="text-[10px] font-bold uppercase bg-nf-blue-soft text-nf-blue-dark rounded-full px-2 py-0.5">{{ $types[$f->type] ?? $f->type }}</span>
                            @if($f->is_core)<span class="text-[10px] font-bold uppercase bg-nf-yellow text-nf-ink rounded-full px-2 py-0.5">inti</span>@endif
                            @if($f->is_required)<span class="text-[10px] font-bold uppercase bg-red-100 text-red-700 rounded-full px-2 py-0.5">wajib</span>@endif
                            @if(!$f->is_active)<span class="text-[10px] font-bold uppercase bg-nf-ink/10 text-nf-ink/60 rounded-full px-2 py-0.5">arsip</span>@endif
                            @if($f->section)<span class="text-[10px] font-bold uppercase bg-nf-ink/5 text-nf-ink/60 rounded-full px-2 py-0.5">{{ $f->section }}</span>@endif
                        </p>
                        @if($f->hasCondition())
                        <p class="mt-1 text-xs text-nf-ink/60">Jika <strong>{{ $f->visible_if_field }}</strong> {{ $operators[$f->visible_if_operator] ?? '' }} <strong>{{ is_array($f->visible_if_value) ? implode(', ', $f->visible_if_value) : ($f->visible_if_value ?: '-') }}</strong></p>
                        @endif
                        @if(($answerCounts[$f->key] ?? 0) > 0)
                        <p class="mt-1 text-xs text-nf-ink/45">{{ $answerCounts[$f->key] }} jawaban masuk</p>
                        @endif
                    </a>
                    <svg class="w-4 h-4 text-nf-ink/30 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
            @empty
            <div class="rounded-2xl bg-white border border-dashed border-nf-blue/30 p-10 text-center text-sm text-nf-ink/55">
                Belum ada field. Klik "Tambah pertanyaan" untuk mulai menyusun form {{ strtoupper($jenjang) }}.
            </div>
            @endforelse
        </div>
        <p id="reorder-status" class="mt-2 text-xs font-bold text-nf-ink/50"></p>
    </div>

    {{-- Pratinjau live --}}
    <div class="lg:sticky lg:top-20">
        <div class="rounded-3xl border border-nf-blue/20 overflow-hidden bg-white shadow-sm">
            <div class="flex items-center gap-1.5 bg-nf-ink px-4 py-2.5">
                <span class="w-2.5 h-2.5 rounded-full bg-red-400"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-nf-yellow"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-nf-green"></span>
                <span class="ml-2 text-[11px] font-bold text-white/60">Pratinjau persis form ortu — bisa diklik</span>
            </div>
            <div class="p-5" x-data="ppdbForm(@js($conditions), @js($sample))" data-jenjang-section="{{ $jenjang }}">
                <p class="mb-3 text-xs rounded-xl bg-nf-blue-soft text-nf-blue-dark font-bold px-3 py-2">Coba ubah jawaban untuk melihat field kondisional muncul dan sembunyi.</p>
                @include('pages.partials.ppdb-fields', ['j' => $jenjang, 'fields' => $fields, 'preview' => true])
            </div>
        </div>
    </div>
</div>

{{-- Inspector drawer --}}
@if($editField)
<div class="fixed inset-0 z-50" role="dialog" aria-modal="true">
    <a href="{{ route('admin.fields.index', ['jenjang' => $jenjang]) }}" class="absolute inset-0 bg-black/50" aria-label="Tutup"></a>
    <div class="absolute right-0 top-0 h-full w-full sm:max-w-lg bg-nf-cream overflow-y-auto p-5 sm:p-6 shadow-2xl">
        <div class="flex items-center justify-between">
            <h2 class="font-heading font-extrabold text-lg">Edit pertanyaan</h2>
            <a href="{{ route('admin.fields.index', ['jenjang' => $jenjang]) }}" class="grid place-items-center w-10 h-10 rounded-full bg-white border border-nf-blue/20 hover:bg-nf-blue-soft transition" aria-label="Tutup">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
            </a>
        </div>
        <form method="POST" action="{{ route('admin.fields.update', $editField) }}" class="mt-4 grid gap-4 rounded-3xl bg-white border border-nf-blue/15 p-5">
            @csrf @method('PUT')
            <input type="hidden" name="jenjang" value="{{ $jenjang }}">
            <input type="hidden" name="type" value="{{ $editField->type }}">
            <input type="hidden" name="sort_order" value="{{ $editField->sort_order }}">
            <label class="grid gap-1.5 text-sm font-bold">Label pertanyaan
                <input name="label" required value="{{ old('label', $editField->label) }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue">
            </label>
            <div class="grid sm:grid-cols-2 gap-4">
                <label class="grid gap-1.5 text-sm font-bold">Kelompok/section
                    <input name="section" value="{{ old('section', $editField->section) }}" placeholder="cth: Kesehatan" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
                </label>
                <p class="grid gap-1.5 text-sm font-bold">Tipe
                    <span class="font-normal rounded-xl bg-nf-cream border border-nf-blue/15 px-4 py-2.5 text-nf-ink/60">{{ $types[$editField->type] }} (terkunci)</span>
                </p>
            </div>
            @if(in_array($editField->type, ['select', 'radio', 'checkbox'], true))
            <div class="grid gap-1.5 text-sm font-bold">Pilihan jawaban
                <div id="opt-list" class="grid gap-2">
                    @foreach(old('options', $editField->options ?? ['']) as $o)
                    <div class="flex gap-2">
                        <input name="options[]" value="{{ $o }}" placeholder="Tulis opsi..." class="flex-1 font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
                        <button type="button" onclick="this.parentElement.remove()" class="shrink-0 grid place-items-center w-11 rounded-xl border border-red-200 text-red-600 hover:bg-red-50" aria-label="Hapus opsi">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
                        </button>
                    </div>
                    @endforeach
                </div>
                <button type="button" onclick="addOptRow()" class="justify-self-start text-xs font-bold text-nf-blue-dark hover:underline">+ Tambah opsi</button>
            </div>
            @endif
            <div class="grid sm:grid-cols-2 gap-4">
                <label class="flex items-center gap-2 text-sm font-bold rounded-xl border border-nf-blue/25 px-4 py-3 cursor-pointer">
                    <input type="checkbox" name="is_required" value="1" @checked(old('is_required', $editField->is_required)) class="rounded text-nf-blue">
                    Wajib diisi
                </label>
                <label class="flex items-center gap-2 text-sm font-bold rounded-xl border border-nf-blue/25 px-4 py-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $editField->is_active ?? true)) class="rounded text-nf-blue">
                    Aktif di form
                </label>
            </div>
            <div class="rounded-2xl bg-nf-cream border border-nf-blue/20 p-4" x-data="{
                trigger: '{{ old('visible_if_field', $editField->visible_if_field ?? '') }}',
                operator: '{{ old('visible_if_operator', $editField->visible_if_operator ?? '') }}',
                triggers: @js($triggers->map(fn ($t) => ['key' => $t->key, 'label' => $t->label, 'options' => $t->options ?? []])->values()),
                get triggerOptions() {
                    const t = this.triggers.find(x => x.key === this.trigger);
                    return t ? t.options : [];
                },
                get needsValue() { return this.trigger !== '' && !['filled', 'empty'].includes(this.operator); },
                get isMulti() { return ['in', 'not_in'].includes(this.operator); }
            }">
                <h3 class="font-heading font-bold text-sm">Tampilkan field ini hanya jika…</h3>
                <div class="mt-3 grid gap-3">
                    <label class="grid gap-1.5 text-sm font-bold">…jawaban field ini
                        <select name="visible_if_field" x-model="trigger" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 bg-white">
                            <option value="">Selalu tampil (tanpa syarat)</option>
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
                                            @php $curVal = old('visible_if_value', is_array($editField->visible_if_value) ? ($editField->visible_if_value[0] ?? '') : ($editField->visible_if_value ?? '')); @endphp
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
                                @php $curVals = (array) old('visible_if_values', $editField->visible_if_value ?? []); @endphp
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
            <button class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-6 py-3 rounded-full transition">Simpan Perubahan</button>
        </form>
        @can('delete', $editField)
        @if(!$editField->is_core)
        <form method="POST" action="{{ route('admin.fields.destroy', $editField) }}" onsubmit="return confirm('Hapus pertanyaan ini dari form?')" class="mt-3 rounded-3xl bg-white border border-red-200 p-5">
            @csrf @method('DELETE')
            @if(($answerCounts[$editField->key] ?? 0) > 0)
            <p class="text-xs font-bold text-red-700">Sudah ada {{ $answerCounts[$editField->key] }} jawaban masuk. Arsipkan (nonaktifkan) saja bila ragu.</p>
            @endif
            <button class="mt-2 text-sm font-bold text-red-600 hover:underline">Hapus pertanyaan ini</button>
        </form>
        @endif
        @endcan
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
function addOptRow() {
    const list = document.getElementById('opt-list');
    if (!list) return;
    const row = document.createElement('div');
    row.className = 'flex gap-2';
    row.innerHTML = '<input name="options[]" placeholder="Tulis opsi..." class="flex-1 font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">'
        + '<button type="button" onclick="this.parentElement.remove()" class="shrink-0 grid place-items-center w-11 rounded-xl border border-red-200 text-red-600 hover:bg-red-50" aria-label="Hapus opsi">'
        + '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg></button>';
    list.appendChild(row);
    row.querySelector('input').focus();
}
(function () {
    const list = document.getElementById('field-list');
    if (!list || typeof Sortable === 'undefined') return;
    const status = document.getElementById('reorder-status');
    Sortable.create(list, {
        handle: '.drag-handle',
        animation: 150,
        onEnd() {
            const order = [...list.querySelectorAll('.field-card')].map(el => parseInt(el.dataset.id, 10));
            status.textContent = 'Menyimpan urutan…';
            fetch(list.dataset.reorderUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': list.dataset.token
                },
                body: JSON.stringify({ jenjang: list.dataset.jenjang, order: order })
            }).then(r => {
                status.textContent = r.ok ? 'Urutan tersimpan.' : 'Gagal menyimpan urutan.';
                setTimeout(() => { status.textContent = ''; }, 2500);
            }).catch(() => { status.textContent = 'Gagal menyimpan urutan.'; });
        }
    });
})();
</script>
@endpush
