@extends('layouts.admin')
@section('title', 'Agenda')
@section('page-title', 'Agenda')

@section('content')
<div class="flex items-center justify-between mb-4">
    <p class="text-sm text-nf-ink/60">{{ $agendas->total() }} agenda</p>
    @can('create', App\Models\Agenda::class)
    <a href="{{ route('admin.agendas.create') }}" class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-5 py-2.5 rounded-full transition">+ Tambah Agenda</a>
    @endcan
</div>
<div class="rounded-3xl bg-white border border-nf-blue/15 overflow-hidden">
    <div class="divide-y divide-nf-blue/10">
        @forelse($agendas as $a)
        <div class="flex items-center gap-4 px-5 py-4">
            <span class="shrink-0 w-14 text-center rounded-xl bg-nf-blue-soft py-1.5 font-heading font-extrabold text-nf-blue-dark">{{ $a->date->format('d') }}<span class="block text-[10px]">{{ $a->date->format('M Y') }}</span></span>
            <div class="flex-1 min-w-0">
                <p class="font-heading font-bold truncate">{{ $a->title }}</p>
                <p class="text-xs text-nf-ink/50 mt-0.5">{{ $a->time_label }} · {{ $a->place }} · {{ $a->is_published ? 'Tampil' : 'Sembunyi' }}</p>
            </div>
            @can('update', $a)<a href="{{ route('admin.agendas.edit', $a) }}" class="text-sm font-bold text-nf-blue-dark hover:underline shrink-0">Ubah</a>@endcan
            @can('delete', $a)
            <form method="POST" action="{{ route('admin.agendas.destroy', $a) }}" onsubmit="return confirm('Hapus agenda ini?')" class="shrink-0">
                @csrf @method('DELETE')
                <button class="text-sm font-bold text-red-600 hover:underline">Hapus</button>
            </form>
            @endcan
        </div>
        @empty
        <p class="px-5 py-10 text-center text-sm text-nf-ink/50">Belum ada agenda.</p>
        @endforelse
    </div>
</div>
<div class="mt-4">{{ $agendas->links() }}</div>
@endsection
