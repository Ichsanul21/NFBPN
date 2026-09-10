@extends('layouts.admin')
@section('title', 'Periode PPDB')
@section('page-title', 'Periode PPDB')

@section('content')
<div class="flex items-center justify-between mb-4">
    <p class="text-sm text-nf-ink/60">{{ $periods->total() }} periode</p>
    @can('create', App\Models\PpdbPeriod::class)
    <a href="{{ route('admin.periods.create') }}" class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-5 py-2.5 rounded-full transition">+ Tambah Periode</a>
    @endcan
</div>
<div class="rounded-3xl bg-white border border-nf-blue/15 overflow-hidden">
    <div class="divide-y divide-nf-blue/10">
        @forelse($periods as $p)
        <div class="flex items-center gap-4 px-5 py-4">
            <div class="flex-1 min-w-0">
                <p class="font-heading font-bold">{{ $p->name }} <span class="text-nf-ink/45 font-normal">· {{ strtoupper($p->jenjang) }}</span></p>
                <p class="text-xs text-nf-ink/50 mt-0.5">{{ $p->starts_on->format('d M Y') }} s.d. {{ $p->ends_on->format('d M Y') }} · {{ $p->registrations_count }} pendaftar · {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}</p>
            </div>
            @can('update', $p)<a href="{{ route('admin.periods.edit', $p) }}" class="text-sm font-bold text-nf-blue-dark hover:underline shrink-0">Ubah</a>@endcan
        </div>
        @empty
        <p class="px-5 py-10 text-center text-sm text-nf-ink/50">Belum ada periode.</p>
        @endforelse
    </div>
</div>
<div class="mt-4">{{ $periods->links() }}</div>
@endsection
