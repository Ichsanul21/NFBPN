@extends('layouts.admin')
@section('title', 'Komitmen Ortu')
@section('page-title', 'Pernyataan Komitmen Orang Tua')

@section('content')
<p class="text-sm text-nf-ink/60 mb-4 max-w-3xl">Teks per jenjang. Calon orang tua wajib mencentang persetujuan di langkah terakhir formulir. Salinan teks saat mendaftar tersimpan di tiap pendaftar sebagai arsip.</p>
<form method="POST" action="{{ route('admin.commitments.update') }}" class="grid gap-4 max-w-4xl">
    @csrf @method('PUT')
    @foreach($jenjangs as $slug => $label)
    <div class="rounded-3xl bg-white border border-nf-blue/15 p-6">
        <h2 class="font-heading font-extrabold">{{ $label }}</h2>
        <textarea name="teks[{{ $slug }}]" rows="7" placeholder="Kosongkan bila jenjang ini tanpa pernyataan komitmen." class="mt-3 w-full font-normal text-sm rounded-xl border border-nf-blue/25 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-nf-blue">{{ old('teks.'.$slug, $items[$slug]->teks ?? '') }}</textarea>
    </div>
    @endforeach
    <div>
        <button class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-6 py-3 rounded-full transition">Simpan Semua</button>
    </div>
</form>
@endsection
