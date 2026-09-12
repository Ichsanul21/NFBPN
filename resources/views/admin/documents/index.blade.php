@extends('layouts.admin')
@section('title', 'Dokumen Wajib')
@section('page-title', 'Dokumen Wajib')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-4">
    <div class="flex flex-wrap gap-2">
        @foreach($jenjangs as $k => $v)
        <a href="{{ route('admin.documents.index', ['jenjang' => $k]) }}" class="text-xs font-bold px-4 py-2 rounded-full {{ $jenjang === $k ? 'bg-nf-blue text-white' : 'bg-white border border-nf-blue/20' }}">{{ $v }}</a>
        @endforeach
    </div>
    @can('create', App\Models\PpdbDocument::class)
    <a href="{{ route('admin.documents.create', ['jenjang' => $jenjang]) }}" class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-5 py-2.5 rounded-full transition">+ Tambah Dokumen</a>
    @endcan
</div>
<div class="rounded-3xl bg-white border border-nf-blue/15 overflow-hidden">
    <div class="divide-y divide-nf-blue/10">
        @forelse($docs as $d)
        <div class="flex items-center gap-4 px-5 py-4">
            <div class="flex-1 min-w-0">
                <p class="font-heading font-bold">{{ $d->label }}
                    @if($d->wajib)<span class="ml-1 text-[10px] font-bold uppercase bg-red-100 text-red-700 rounded-full px-2 py-0.5">wajib</span>@endif
                    @if(!$d->aktif)<span class="ml-1 text-[10px] font-bold uppercase bg-nf-ink/10 text-nf-ink/60 rounded-full px-2 py-0.5">arsip</span>@endif
                </p>
                <p class="text-xs text-nf-ink/50 mt-0.5">{{ implode(', ', array_map('strtoupper', (array) ($d->allowed ?: []))) }} · maks {{ number_format($d->max_kb) }} KB{{ $d->compress ? ' · kompresi gambar' : '' }} · urutan {{ $d->urut }}</p>
                @if($d->hasCondition())
                <p class="text-xs text-nf-ink/55 mt-1">Muncul jika <strong>{{ $d->visible_if_field }}</strong> {{ \App\Models\PpdbFormField::OPERATORS[$d->visible_if_operator] ?? '' }} <strong>{{ is_array($d->visible_if_value) ? implode(', ', $d->visible_if_value) : ($d->visible_if_value ?: '-') }}</strong></p>
                @endif
            </div>
            @can('update', $d)<a href="{{ route('admin.documents.edit', $d) }}" class="text-sm font-bold text-nf-blue-dark hover:underline shrink-0">Ubah</a>@endcan
            @can('delete', $d)
            <form method="POST" action="{{ route('admin.documents.destroy', $d) }}" onsubmit="return confirm('Hapus dokumen ini?')" class="shrink-0">
                @csrf @method('DELETE')
                <button class="text-sm font-bold text-red-600 hover:underline">Hapus</button>
            </form>
            @endcan
        </div>
        @empty
        <p class="px-5 py-10 text-center text-sm text-nf-ink/50">Belum ada dokumen untuk jenjang ini.</p>
        @endforelse
    </div>
</div>
@endsection
