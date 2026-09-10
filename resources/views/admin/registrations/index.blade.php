@extends('layouts.admin')
@section('title', 'Pendaftar')
@section('page-title', 'Pendaftar PPDB')

@section('content')
<form method="GET" class="rounded-3xl bg-white border border-nf-blue/15 p-4 mb-4 grid sm:grid-cols-5 gap-3">
    <input name="q" value="{{ request('q') }}" placeholder="Cari nama / no. registrasi" class="text-sm rounded-xl border border-nf-blue/25 px-4 py-2.5">
    <select name="period_id" class="text-sm rounded-xl border border-nf-blue/25 px-3 py-2.5">
        <option value="">Semua periode</option>
        @foreach($periods as $p)<option value="{{ $p->id }}" @selected(request('period_id') == $p->id)>{{ $p->name }} ({{ strtoupper($p->jenjang) }})</option>@endforeach
    </select>
    <select name="jenjang" class="text-sm rounded-xl border border-nf-blue/25 px-3 py-2.5">
        <option value="">Semua jenjang</option>
        @foreach($jenjangs as $k => $v)<option value="{{ $k }}" @selected(request('jenjang') === $k)>{{ $v }}</option>@endforeach
    </select>
    <select name="status" class="text-sm rounded-xl border border-nf-blue/25 px-3 py-2.5">
        <option value="">Semua status</option>
        @foreach($statuses as $k => $v)<option value="{{ $k }}" @selected(request('status') === $k)>{{ $v }}</option>@endforeach
    </select>
    <div class="flex gap-2">
        <button class="flex-1 bg-nf-blue text-white text-sm font-bold px-4 py-2.5 rounded-xl">Filter</button>
        @can('ppdb.export')
        <a href="{{ route('admin.registrations.export', request()->only(['period_id', 'jenjang', 'status'])) }}" class="bg-nf-green-soft text-nf-ink text-sm font-bold px-4 py-2.5 rounded-xl hover:bg-nf-green transition">Excel</a>
        @endcan
    </div>
</form>
<div class="rounded-3xl bg-white border border-nf-blue/15 overflow-hidden">
    <div class="divide-y divide-nf-blue/10">
        @forelse($regs as $r)
        <a href="{{ route('admin.registrations.show', $r) }}" class="flex items-center gap-4 px-5 py-4 hover:bg-nf-blue-soft/40 transition">
            <div class="flex-1 min-w-0">
                <p class="font-heading font-bold truncate">{{ $r->child_name }} <span class="text-nf-ink/45 font-normal text-sm">{{ $r->registration_no }}</span></p>
                <p class="text-xs text-nf-ink/50 mt-0.5">{{ strtoupper($r->jenjang) }} · {{ $r->period?->name }} · {{ $r->created_at->format('d M Y') }}</p>
            </div>
            <span class="text-xs font-bold bg-nf-blue-soft text-nf-blue-dark rounded-full px-3 py-1.5 shrink-0">{{ $r->statusLabel() }}</span>
        </a>
        @empty
        <p class="px-5 py-10 text-center text-sm text-nf-ink/50">Belum ada pendaftar.</p>
        @endforelse
    </div>
</div>
<div class="mt-4">{{ $regs->links() }}</div>
@endsection
