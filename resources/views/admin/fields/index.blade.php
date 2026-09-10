@extends('layouts.admin')
@section('title', 'Form Pendaftaran')
@section('page-title', 'Form Pendaftaran')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-4">
    <div class="flex flex-wrap gap-2">
        @foreach($jenjangs as $k => $v)
        <a href="{{ route('admin.fields.index', ['jenjang' => $k]) }}" class="text-xs font-bold px-4 py-2 rounded-full {{ $jenjang === $k ? 'bg-nf-blue text-white' : 'bg-white border border-nf-blue/20' }}">{{ $v }}</a>
        @endforeach
    </div>
    @can('create', App\Models\PpdbFormField::class)
    <a href="{{ route('admin.fields.create', ['jenjang' => $jenjang]) }}" class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-5 py-2.5 rounded-full transition">+ Tambah Field</a>
    @endcan
</div>
<div class="rounded-3xl bg-white border border-nf-blue/15 overflow-hidden">
    <div class="divide-y divide-nf-blue/10">
        @forelse($fields as $f)
        <div class="flex items-center gap-4 px-5 py-4">
            <div class="flex-1 min-w-0">
                <p class="font-heading font-bold">{{ $f->label }}
                    @if($f->is_core)<span class="ml-1 text-[10px] font-bold uppercase bg-nf-yellow text-nf-ink rounded-full px-2 py-0.5">inti</span>@endif
                    @if($f->is_required)<span class="ml-1 text-[10px] font-bold uppercase bg-red-100 text-red-700 rounded-full px-2 py-0.5">wajib</span>@endif
                    @if($f->section)<span class="ml-1 text-[10px] font-bold uppercase bg-nf-blue-soft text-nf-blue-dark rounded-full px-2 py-0.5">{{ $f->section }}</span>@endif
                </p>
                <p class="text-xs text-nf-ink/50 mt-0.5">{{ $types[$f->type] ?? $f->type }} · key: {{ $f->key }} · urutan {{ $f->sort_order }}</p>
                @if($f->hasCondition())
                <p class="text-xs text-nf-ink/55 mt-1">Muncul jika <strong>{{ $f->visible_if_field }}</strong> {{ \App\Models\PpdbFormField::OPERATORS[$f->visible_if_operator] ?? $f->visible_if_operator }} <strong>{{ is_array($f->visible_if_value) ? implode(', ', $f->visible_if_value) : ($f->visible_if_value ?: '-') }}</strong></p>
                @endif
            </div>
            @can('update', $f)<a href="{{ route('admin.fields.edit', $f) }}" class="text-sm font-bold text-nf-blue-dark hover:underline shrink-0">Ubah</a>@endcan
            @can('delete', $f)
            @if(!$f->is_core)
            <form method="POST" action="{{ route('admin.fields.destroy', $f) }}" onsubmit="return confirm('Hapus field ini?')" class="shrink-0">
                @csrf @method('DELETE')
                <button class="text-sm font-bold text-red-600 hover:underline">Hapus</button>
            </form>
            @endif
            @endcan
        </div>
        @empty
        <p class="px-5 py-10 text-center text-sm text-nf-ink/50">Belum ada field custom untuk jenjang ini.</p>
        @endforelse
    </div>
</div>
<p class="mt-3 text-xs text-nf-ink/50">Field inti (nama, tanggal lahir, dsb) terkunci dan tidak tampil di sini kecuali diubah tipenya. Jawaban tersimpan otomatis mengikuti definisi field.</p>
@endsection
