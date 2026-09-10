@extends('layouts.admin')
@section('title', 'Detail Pesan')
@section('page-title', 'Detail Pesan')

@section('content')
<div class="rounded-3xl bg-white border border-nf-blue/15 p-6 max-w-3xl">
    <p class="text-xs text-nf-ink/50">{{ $message->created_at->format('d M Y H:i') }}</p>
    <h2 class="mt-1 font-heading font-extrabold text-xl">{{ $message->subject ?: '(Tanpa subjek)' }}</h2>
    <p class="mt-1 text-sm text-nf-ink/60">Dari {{ $message->name }}{{ $message->whatsapp ? ' · '.$message->whatsapp : '' }}</p>
    <p class="mt-4 leading-relaxed">{{ $message->message }}</p>
    <div class="mt-6 flex flex-wrap gap-3">
        @if($message->whatsapp)
        <a href="https://wa.me/{{ '62'.ltrim(preg_replace('/[^0-9]/', '', $message->whatsapp), '0') }}" target="_blank" rel="noopener" class="text-sm font-bold border border-nf-green/40 rounded-full px-5 py-2.5 hover:bg-nf-green-soft transition">Balas via WhatsApp</a>
        @endif
        <form method="POST" action="{{ route('admin.messages.update', $message) }}">
            @csrf @method('PUT')
            <button class="text-sm font-bold border border-nf-blue/25 rounded-full px-5 py-2.5 hover:bg-nf-blue-soft transition">{{ $message->is_read ? 'Tandai belum dibaca' : 'Tandai sudah dibaca' }}</button>
        </form>
        <form method="POST" action="{{ route('admin.messages.destroy', $message) }}" onsubmit="return confirm('Hapus pesan ini?')">
            @csrf @method('DELETE')
            <button class="text-sm font-bold text-red-600 hover:underline px-2 py-2.5">Hapus</button>
        </form>
    </div>
</div>
@endsection
