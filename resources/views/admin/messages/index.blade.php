@extends('layouts.admin')
@section('title', 'Pesan Masuk')
@section('page-title', 'Pesan Masuk')

@section('content')
<div class="rounded-3xl bg-white border border-nf-blue/15 overflow-hidden">
    <div class="divide-y divide-nf-blue/10">
        @forelse($messages as $m)
        <a href="{{ route('admin.messages.show', $m) }}" class="flex items-center gap-4 px-5 py-4 hover:bg-nf-blue-soft/40 transition {{ $m->is_read ? '' : 'bg-nf-yellow/20' }}">
            <div class="flex-1 min-w-0">
                <p class="font-heading font-bold truncate">{{ $m->subject ?: '(Tanpa subjek)' }} <span class="text-nf-ink/45 font-normal text-sm">· {{ $m->name }}</span></p>
                <p class="text-xs text-nf-ink/50 mt-0.5 truncate">{{ $m->message }} · {{ $m->created_at->format('d M Y H:i') }}</p>
            </div>
            @unless($m->is_read)<span class="w-2.5 h-2.5 rounded-full bg-nf-blue shrink-0"></span>@endunless
        </a>
        @empty
        <p class="px-5 py-10 text-center text-sm text-nf-ink/50">Belum ada pesan.</p>
        @endforelse
    </div>
</div>
<div class="mt-4">{{ $messages->links() }}</div>
@endsection
