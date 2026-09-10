@extends('layouts.admin')
@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan Situs')

@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}" class="grid gap-4 max-w-3xl">
    @csrf @method('PUT')
    @foreach($settings as $group => $pairs)
    <div class="rounded-3xl bg-white border border-nf-blue/15 p-6">
        <h2 class="font-heading font-extrabold capitalize">{{ $group }}</h2>
        <div class="mt-4 grid sm:grid-cols-2 gap-4">
            @foreach($pairs as $s)
            <label class="grid gap-1.5 text-sm font-bold capitalize">{{ str_replace('_', ' ', $s->key) }}
                <input name="{{ $s->key }}" value="{{ old($s->key, $s->value) }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue">
            </label>
            @endforeach
        </div>
    </div>
    @endforeach
    <div>
        <button class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-6 py-3 rounded-full transition">Simpan Pengaturan</button>
    </div>
</form>
@endsection
