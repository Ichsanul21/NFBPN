<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Admin Nurul Fikri Balikpapan</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @endif
</head>
<body class="bg-nf-cream text-nf-ink antialiased" x-data="{ sidebar: false }">
    <div class="min-h-screen lg:grid lg:grid-cols-[260px_1fr]">
        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full transition-transform lg:translate-x-0 lg:static bg-nf-ink text-white flex flex-col"
               :class="sidebar ? 'translate-x-0' : '-translate-x-full'">
            <div class="absolute inset-0 islamic-pattern opacity-30 pointer-events-none"></div>
            <a href="{{ route('admin.dashboard') }}" class="relative flex items-center gap-3 px-5 pt-6 pb-5">
                <img src="{{ asset('logo.webp') }}" alt="Logo" class="w-10 h-10 rounded-xl object-contain bg-white">
                <span class="leading-tight">
                    <span class="block font-heading font-bold">NF Admin</span>
                    <span class="block text-[11px] text-white/60">{{ auth()->user()->getRoleNames()->first() }}</span>
                </span>
            </a>
            <nav class="relative flex-1 overflow-y-auto px-3 pb-4 space-y-1 text-sm font-bold">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-nf-blue text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10h5v-6h4v6h5V10"/></svg>
                    Dashboard
                </a>

                @canany(['ppdb.periods', 'ppdb.fields', 'ppdb.registrations', 'ppdb.documents'])
                <p class="px-3 pt-4 pb-1 text-[11px] uppercase tracking-widest text-nf-yellow/80">PPDB</p>
                @can('ppdb.registrations')
                <a href="{{ route('admin.registrations.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.registrations.*') ? 'bg-nf-blue text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6M9 8h6M5 3h14a1 1 0 011 1v16a1 1 0 01-1 1H5a1 1 0 01-1-1V4a1 1 0 011-1z"/></svg>
                    Pendaftar
                </a>
                @endcan
                @can('ppdb.periods')
                <a href="{{ route('admin.periods.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.periods.*') ? 'bg-nf-blue text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1z"/></svg>
                    Periode
                </a>
                @endcan
                @can('ppdb.fields')
                <a href="{{ route('admin.fields.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.fields.*') ? 'bg-nf-blue text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                    Form Pendaftaran
                </a>
                <a href="{{ route('admin.commitments.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.commitments.*') ? 'bg-nf-blue text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Komitmen Ortu
                </a>
                @endcan
                @can('ppdb.documents')
                <a href="{{ route('admin.documents.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.documents.*') ? 'bg-nf-blue text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H9a2 2 0 00-2 2v14a2 2 0 002 2zM9 3v4h6V3"/></svg>
                    Dokumen Wajib
                </a>
                @endcan
                @endcanany

                @canany(['news.manage', 'gallery.manage', 'agenda.manage', 'testimonial.manage'])
                <p class="px-3 pt-4 pb-1 text-[11px] uppercase tracking-widest text-nf-yellow/80">Konten</p>
                @can('news.manage')
                <a href="{{ route('admin.news.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.news.*') ? 'bg-nf-blue text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM9 9h6M9 13h6"/></svg>
                    Berita
                </a>
                @endcan
                @can('gallery.manage')
                <a href="{{ route('admin.galleries.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.galleries.*') ? 'bg-nf-blue text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4-4 4 4 4-6 4 4M4 5h16a1 1 0 011 1v12a1 1 0 01-1 1H4a1 1 0 01-1-1V6a1 1 0 011-1z"/></svg>
                    Galeri
                </a>
                @endcan
                @can('agenda.manage')
                <a href="{{ route('admin.agendas.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.agendas.*') ? 'bg-nf-blue text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Agenda
                </a>
                @endcan
                @can('testimonial.manage')
                <a href="{{ route('admin.testimonials.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.testimonials.*') ? 'bg-nf-blue text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8m-8 4h5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Testimoni
                </a>
                @endcan
                @endcanany

                @can('messages.manage')
                <a href="{{ route('admin.messages.index') }}" class="mt-4 flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.messages.*') ? 'bg-nf-blue text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l9 6 9-6M4 6h16a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V7a1 1 0 011-1z"/></svg>
                    Pesan Masuk
                </a>
                @endcan
                @can('users.manage')
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.users.*') ? 'bg-nf-blue text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6-4a3 3 0 11-3-3"/></svg>
                    Pengguna
                </a>
                @endcan
                @can('settings.manage')
                <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.settings.*') ? 'bg-nf-blue text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 16v-2m8-8h2M4 12H2m15.5-5.5l1.4-1.4M5.1 18.9l1.4-1.4m0-11l-1.4-1.4M18.9 18.9l-1.4-1.4M12 8a4 4 0 100 8 4 4 0 000-8z"/></svg>
                    Pengaturan
                </a>
                @endcan
            </nav>
            <div class="relative p-4 border-t border-white/10">
                <a href="{{ route('home') }}" target="_blank" class="block text-center text-xs font-bold text-white/60 hover:text-nf-yellow transition py-2">Lihat Website →</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full text-center text-xs font-bold bg-white/10 hover:bg-white/20 rounded-xl py-2.5 transition">Keluar</button>
                </form>
            </div>
        </aside>
        <div class="fixed inset-0 z-30 bg-black/50 lg:hidden" x-show="sidebar" @click="sidebar = false" x-cloak></div>

        <div class="min-w-0 flex flex-col min-h-screen">
            <header class="sticky top-0 z-20 bg-white/95 backdrop-blur border-b border-nf-blue/15">
                <div class="flex items-center gap-3 px-4 sm:px-6 h-16">
                    <button class="lg:hidden grid place-items-center w-10 h-10 rounded-xl border border-nf-blue/20" @click="sidebar = true" aria-label="Menu">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                    </button>
                    <h1 class="font-heading font-extrabold text-lg truncate">@yield('page-title', 'Dashboard')</h1>
                    <div class="ml-auto flex items-center gap-3">
                        <span class="hidden sm:block text-sm text-nf-ink/60">{{ auth()->user()->name }}</span>
                        <span class="grid place-items-center w-9 h-9 rounded-full bg-nf-blue-soft text-nf-blue-dark font-heading font-bold">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 w-full max-w-6xl mx-auto">
                @if(session('success'))
                <div class="mb-4 rounded-2xl bg-nf-green-soft border border-nf-green/40 text-nf-ink text-sm font-bold px-5 py-3.5">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                <div class="mb-4 rounded-2xl bg-red-50 border border-red-300 text-red-800 text-sm font-bold px-5 py-3.5">{{ session('error') }}</div>
                @endif
                @if($errors->any())
                <div class="mb-4 rounded-2xl bg-red-50 border border-red-300 text-red-800 text-sm font-bold px-5 py-3.5">
                    <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
