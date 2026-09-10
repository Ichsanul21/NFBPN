@extends('layouts.admin')
@section('title', ($item->exists ? 'Ubah' : 'Tambah').' Pengguna')
@section('page-title', ($item->exists ? 'Ubah' : 'Tambah').' Pengguna')

@section('content')
<form method="POST" action="{{ $item->exists ? route('admin.users.update', $item) : route('admin.users.store') }}" class="rounded-3xl bg-white border border-nf-blue/15 p-6 grid gap-5 max-w-2xl">
    @csrf
    @if($item->exists) @method('PUT') @endif
    <div class="grid sm:grid-cols-2 gap-5">
        <label class="grid gap-1.5 text-sm font-bold">Nama
            <input name="name" required value="{{ old('name', $item->name) }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue">
        </label>
        <label class="grid gap-1.5 text-sm font-bold">Email
            <input type="email" name="email" required value="{{ old('email', $item->email) }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue">
        </label>
    </div>
    <div class="grid sm:grid-cols-2 gap-5">
        <label class="grid gap-1.5 text-sm font-bold">Kata sandi {{ $item->exists ? '(kosongkan bila tidak diubah)' : '' }}
            <input type="password" name="password" {{ $item->exists ? '' : 'required' }} minlength="8" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5" autocomplete="new-password">
        </label>
        <label class="grid gap-1.5 text-sm font-bold">Role
            <select name="role" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
                @foreach($roles as $r)<option value="{{ $r->name }}" @selected(old('role', $item->getRoleNames()->first()) === $r->name)>{{ $r->name }}</option>@endforeach
            </select>
        </label>
    </div>
    <div class="flex gap-3">
        <button class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-6 py-3 rounded-full transition">Simpan</button>
        <a href="{{ route('admin.users.index') }}" class="font-heading font-bold text-sm px-6 py-3 rounded-full border border-nf-blue/25 hover:bg-nf-blue-soft transition">Batal</a>
    </div>
</form>
@endsection
