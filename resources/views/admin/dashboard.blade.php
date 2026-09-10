@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
    @if(isset($regTotal))
    <div class="rounded-3xl bg-white border border-nf-blue/15 p-6">
        <p class="font-heading font-extrabold text-3xl text-nf-blue-dark">{{ $regTotal }}</p>
        <p class="mt-1 text-sm font-bold text-nf-ink/55">Total pendaftar</p>
    </div>
    <div class="rounded-3xl bg-white border border-nf-blue/15 p-6">
        <p class="font-heading font-extrabold text-3xl text-nf-green-dark">{{ ($regByStatus['lolos'] ?? 0) + ($regByStatus['daftar_ulang'] ?? 0) + ($regByStatus['aktif'] ?? 0) }}</p>
        <p class="mt-1 text-sm font-bold text-nf-ink/55">Lolos / daftar ulang</p>
    </div>
    @endif
    @if(isset($newsCount))
    <div class="rounded-3xl bg-white border border-nf-blue/15 p-6">
        <p class="font-heading font-extrabold text-3xl text-nf-blue-dark">{{ $newsCount }}</p>
        <p class="mt-1 text-sm font-bold text-nf-ink/55">Berita terbit</p>
    </div>
    @endif
    @if(isset($unreadMessages))
    <div class="rounded-3xl bg-white border border-nf-blue/15 p-6">
        <p class="font-heading font-extrabold text-3xl text-nf-blue-dark">{{ $unreadMessages }}</p>
        <p class="mt-1 text-sm font-bold text-nf-ink/55">Pesan belum dibaca</p>
    </div>
    @endif
</div>

<div class="mt-6 grid lg:grid-cols-2 gap-4">
    @if(!empty($regByStatus))
    <div class="rounded-3xl bg-white border border-nf-blue/15 p-6">
        <h2 class="font-heading font-extrabold">Pendaftar per status</h2>
        <div class="mt-4 space-y-2.5">
            @foreach(\App\Models\PpdbRegistration::STATUSES as $key => $label)
            @php $total = $regByStatus[$key] ?? 0; $pct = $regTotal > 0 ? round($total / $regTotal * 100) : 0; @endphp
            <div>
                <div class="flex justify-between text-sm font-bold"><span>{{ $label }}</span><span class="text-nf-ink/55">{{ $total }}</span></div>
                <div class="mt-1 h-2 rounded-full bg-nf-blue-soft overflow-hidden"><div class="h-full rounded-full bg-nf-blue" style="width: {{ $pct }}%"></div></div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(!empty($recentRegs) && count($recentRegs))
    <div class="rounded-3xl bg-white border border-nf-blue/15 p-6">
        <h2 class="font-heading font-extrabold">Pendaftar terbaru</h2>
        <div class="mt-4 divide-y divide-nf-blue/10">
            @foreach($recentRegs as $r)
            <a href="{{ route('admin.registrations.show', $r) }}" class="flex items-center justify-between py-2.5 hover:text-nf-blue-dark transition">
                <span class="font-bold text-sm">{{ $r->child_name }} <span class="text-nf-ink/45 font-normal">· {{ strtoupper($r->jenjang) }}</span></span>
                <span class="text-xs font-bold bg-nf-blue-soft text-nf-blue-dark rounded-full px-3 py-1">{{ $r->statusLabel() }}</span>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    @if(!empty($recentNews) && count($recentNews))
    <div class="rounded-3xl bg-white border border-nf-blue/15 p-6">
        <h2 class="font-heading font-extrabold">Berita terbaru</h2>
        <div class="mt-4 divide-y divide-nf-blue/10">
            @foreach($recentNews as $n)
            <div class="py-2.5 text-sm"><span class="font-bold">{{ $n->title }}</span><span class="block text-nf-ink/50 text-xs mt-0.5">{{ $n->created_at->format('d M Y') }} · {{ $n->published_at ? 'Terbit' : 'Draf' }}</span></div>
            @endforeach
        </div>
    </div>
    @endif

    @if(count($upcomingAgendas))
    <div class="rounded-3xl bg-white border border-nf-blue/15 p-6">
        <h2 class="font-heading font-extrabold">Agenda terdekat</h2>
        <div class="mt-4 space-y-2.5">
            @foreach($upcomingAgendas as $a)
            <div class="flex gap-3 items-center text-sm">
                <span class="shrink-0 w-12 text-center rounded-xl bg-nf-blue-soft py-1.5 font-heading font-extrabold text-nf-blue-dark">{{ $a->date->format('d') }}<span class="block text-[10px] font-bold">{{ $a->date->format('M') }}</span></span>
                <span class="font-bold">{{ $a->title }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
