@extends('layouts.admin')
@section('title', 'Pengguna')
@section('page-title', 'Pengguna')

@section('content')
<div class="flex items-center justify-between mb-4">
    <p class="text-sm text-nf-ink/60">{{ $users->total() }} pengguna</p>
    @can('create', App\Models\User::class)
    <a href="{{ route('admin.users.create') }}" class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-5 py-2.5 rounded-full transition">+ Tambah Pengguna</a>
    @endcan
</div>
<div class="rounded-3xl bg-white border border-nf-blue/15 overflow-hidden">
    <div class="divide-y divide-nf-blue/10">
        @foreach($users as $u)
        <div class="flex items-center gap-4 px-5 py-4">
            <span class="grid place-items-center w-10 h-10 rounded-full bg-nf-blue-soft text-nf-blue-dark font-heading font-bold shrink-0">{{ mb_substr($u->name, 0, 1) }}</span>
            <div class="flex-1 min-w-0">
                <p class="font-heading font-bold truncate">{{ $u->name }}</p>
                <p class="text-xs text-nf-ink/50 mt-0.5">{{ $u->email }} · {{ $u->getRoleNames()->join(', ') ?: 'tanpa role' }}</p>
            </div>
            @can('update', $u)<a href="{{ route('admin.users.edit', $u) }}" class="text-sm font-bold text-nf-blue-dark hover:underline shrink-0">Ubah</a>@endcan
            @can('delete', $u)
            <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Hapus pengguna ini?')" class="shrink-0">
                @csrf @method('DELETE')
                <button class="text-sm font-bold text-red-600 hover:underline">Hapus</button>
            </form>
            @endcan
        </div>
        @endforeach
    </div>
</div>
<div class="mt-4">{{ $users->links() }}</div>
@endsection
