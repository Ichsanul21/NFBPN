@extends('layouts.admin')
@section('title', 'Detail Pendaftar')
@section('page-title', 'Detail Pendaftar')

@section('content')
<div class="grid lg:grid-cols-3 gap-4">
    <div class="lg:col-span-2 rounded-3xl bg-white border border-nf-blue/15 p-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="font-heading font-extrabold text-xl">{{ $item->child_name }}</h2>
                <p class="text-sm text-nf-ink/55">{{ $item->registration_no }} · {{ strtoupper($item->jenjang) }} · {{ $item->period?->name }}</p>
            </div>
            <span class="flex flex-wrap gap-2">
                <span class="text-xs font-bold bg-nf-blue-soft text-nf-blue-dark rounded-full px-4 py-2">{{ $item->statusLabel() }}</span>
                @if($item->dibantu_tu)<span class="text-xs font-bold bg-nf-yellow text-nf-ink rounded-full px-4 py-2">Dibantu TU{{ $item->assistant ? ' · '.$item->assistant->name : '' }}</span>@endif
            </span>
        </div>
        <dl class="mt-5 grid sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
            <div><dt class="text-nf-ink/50 font-bold text-xs uppercase">Tanggal lahir</dt><dd class="font-bold">{{ $item->child_birthdate?->format('d M Y') }}</dd></div>
            <div><dt class="text-nf-ink/50 font-bold text-xs uppercase">Jenis kelamin</dt><dd class="font-bold">{{ $item->gender ?? '-' }}</dd></div>
            <div><dt class="text-nf-ink/50 font-bold text-xs uppercase">Orang tua</dt><dd class="font-bold">{{ $item->parent_name }}</dd></div>
            <div><dt class="text-nf-ink/50 font-bold text-xs uppercase">WhatsApp</dt><dd class="font-bold">{{ $item->whatsapp }}</dd></div>
            @foreach($fields as $f)
            @php $val = ($item->answers ?? [])[$f->key] ?? null; @endphp
            <div><dt class="text-nf-ink/50 font-bold text-xs uppercase">{{ $f->label }}</dt>
                <dd class="font-bold">
                    @if($f->type === 'file' && $val)
                    <a href="{{ asset('storage/'.$val) }}" target="_blank" class="text-nf-blue-dark underline">Lihat berkas</a>
                    @else
                    {{ is_array($val) ? implode(', ', $val) : ($val ?: '-') }}
                    @endif
                </dd>
            </div>
            @endforeach
        </dl>
        @if($item->admin_note)
        <div class="mt-5 rounded-2xl bg-nf-yellow/30 border border-nf-yellow-dark/40 p-4 text-sm">
            <p class="font-bold text-xs uppercase text-nf-ink/60">Catatan admin</p>
            <p class="mt-1">{{ $item->admin_note }}</p>
        </div>
        @endif
        @if(count($documents))
        <div class="mt-6">
            <h3 class="font-heading font-bold">Checklist dokumen</h3>
            <ul class="mt-3 space-y-2 text-sm">
                @foreach($documents as $d)
                @php $up = ($item->answers['dokumen'][$d->docKey()] ?? null); @endphp
                <li class="flex items-center gap-3 rounded-2xl border border-nf-blue/15 px-4 py-2.5">
                    <span class="grid place-items-center w-6 h-6 rounded-full shrink-0 {{ $up ? 'bg-nf-green text-white' : 'bg-nf-ink/10 text-nf-ink/40' }}">
                        @if($up)<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>@else<span class="text-xs font-bold">!</span>@endif
                    </span>
                    <span class="flex-1"><strong>{{ $d->label }}</strong>@if(!$d->wajib)<span class="text-nf-ink/50"> (opsional)</span>@endif</span>
                    @if($up)<a href="{{ asset('storage/'.$up['path']) }}" target="_blank" class="font-bold text-nf-blue-dark hover:underline shrink-0">Lihat</a>@endif
                </li>
                @endforeach
            </ul>
        </div>
        @endif
        @if($item->komitmen_teks)
        <div class="mt-6 rounded-2xl bg-nf-cream border border-nf-blue/20 p-4 text-sm">
            <p class="font-bold text-xs uppercase text-nf-ink/60">Komitmen disetujui {{ $item->komitmen_at?->format('d M Y H:i') }}</p>
            <p class="mt-2 whitespace-pre-line text-nf-ink/80">{{ $item->komitmen_teks }}</p>
        </div>
        @endif
        <div class="mt-6">
            <h3 class="font-heading font-bold">Riwayat status</h3>
            <ol class="mt-3 space-y-2 text-sm">
                <li class="flex gap-2"><span class="text-nf-ink/45">{{ $item->created_at->format('d M Y H:i') }}</span><span>Pendaftaran terkirim</span></li>
                @foreach($item->histories as $h)
                <li class="flex gap-2 flex-wrap">
                    <span class="text-nf-ink/45">{{ $h->created_at->format('d M Y H:i') }}</span>
                    <span><strong>{{ \App\Models\PpdbRegistration::STATUSES[$h->to_status] ?? $h->to_status }}</strong>
                    @if($h->note) — {{ $h->note }}@endif
                    <span class="text-nf-ink/45">({{ $h->changer?->name ?? 'sistem' }})</span></span>
                </li>
                @endforeach
            </ol>
        </div>
    </div>

    <div class="rounded-3xl bg-white border border-nf-blue/15 p-6 h-fit">
        @can('update', $item)
        <h3 class="font-heading font-bold">Ubah status</h3>
        <form method="POST" action="{{ route('admin.registrations.update', $item) }}" class="mt-3 grid gap-3">
            @csrf @method('PUT')
            <select name="status" class="text-sm rounded-xl border border-nf-blue/25 px-4 py-2.5">
                @foreach($statuses as $k => $v)<option value="{{ $k }}" @selected($item->status === $k)>{{ $v }}</option>@endforeach
            </select>
            <textarea name="admin_note" rows="2" placeholder="Catatan admin (terlihat ortu)" class="text-sm rounded-xl border border-nf-blue/25 px-4 py-2.5">{{ old('admin_note', $item->admin_note) }}</textarea>
            <input name="history_note" placeholder="Catatan riwayat (internal)" class="text-sm rounded-xl border border-nf-blue/25 px-4 py-2.5">
            <button class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-5 py-2.5 rounded-full transition">Simpan Perubahan</button>
        </form>
        @else
        <p class="text-sm text-nf-ink/55">Mode lihat saja (akses Kepala Sekolah).</p>
        @endcan
        @php $wa = '62'.ltrim(preg_replace('/[^0-9]/', '', $item->whatsapp ?? ''), '0'); @endphp
        @if(strlen($wa) > 2)
        <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener" class="mt-3 block text-center text-sm font-bold border border-nf-green/40 rounded-full px-4 py-2.5 hover:bg-nf-green-soft transition">Chat WhatsApp Ortu</a>
        @endif
    </div>
</div>
@endsection
