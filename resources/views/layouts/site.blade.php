<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php($docTitle = trim($__env->yieldContent('title')) !== '' ? strip_tags(trim($__env->yieldContent('title'))).' - Nurul Fikri Balikpapan' : 'Nurul Fikri Balikpapan | Sekolah Islam Terpadu')
    <title>{!! $docTitle !!}</title>
    <meta name="description" content="@yield('meta', 'Daycare, KBIT, SDIT, dan SMPIT Nurul Fikri Balikpapan yang menumbuhkan generasi qurani yang cerdas dan berkarakter.')">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    {{-- Open Graph / Twitter Card: tampilkan logo saat link dibagikan --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Nurul Fikri Balikpapan">
    <meta property="og:title" content="{!! $docTitle !!}">
    <meta property="og:description" content="@yield('meta', 'Daycare, KBIT, SDIT, dan SMPIT Nurul Fikri Balikpapan yang menumbuhkan generasi qurani yang cerdas dan berkarakter.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ url('logo.webp') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Logo Nurul Fikri Balikpapan">

    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{!! $docTitle !!}">
    <meta name="twitter:description" content="@yield('meta', 'Daycare, KBIT, SDIT, dan SMPIT Nurul Fikri Balikpapan yang menumbuhkan generasi qurani yang cerdas dan berkarakter.')">
    <meta name="twitter:image" content="{{ url('logo.webp') }}">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @endif
</head>
<body class="bg-white text-nf-ink antialiased">

    {{-- Utility bar --}}
    <div class="bg-nf-ink text-white/85 text-xs">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 flex items-center justify-between gap-4 py-2">
            <p class="truncate">Senin–Jumat · 07.00–16.00 WITA · Balikpapan, Kalimantan Timur</p>
            <div class="hidden sm:flex items-center gap-4 shrink-0">
                <a href="tel:+62542123456" class="hover:text-nf-yellow transition">(0542) 123-456</a>
                <a href="#" class="hover:text-nf-yellow transition">Instagram</a>
                <a href="#" class="hover:text-nf-yellow transition">Facebook</a>
                <a href="#" class="hover:text-nf-yellow transition">YouTube</a>
            </div>
        </div>
    </div>

    {{-- Header --}}
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-nf-blue/20 shadow-[0_2px_20px_-12px_rgba(26,46,26,.35)]">
        <div class="mx-auto max-w-7xl px-4 sm:px-6">
            <div class="flex items-center justify-between h-16 md:h-20 gap-3">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('logo.webp') }}" alt="Logo Nurul Fikri Balikpapan" class="w-11 h-11 md:w-12 md:h-12 rounded-2xl object-contain bg-white shadow">
                    <span class="leading-tight">
                        <span class="block font-heading font-bold text-nf-ink text-base md:text-lg">Nurul Fikri <span class="text-nf-blue-dark">Balikpapan</span></span>
                        <span class="block text-[11px] md:text-xs text-nf-ink/60 font-semibold tracking-wide">Daycare · KBIT · SDIT · SMPIT</span>
                    </span>
                </a>
                <nav class="hidden lg:flex items-center gap-1 text-sm font-bold">
                    <a href="{{ route('home') }}" class="px-3 py-2 rounded-lg hover:bg-nf-blue-soft hover:text-nf-blue-dark transition {{ request()->routeIs('home') ? 'text-nf-blue-dark bg-nf-blue-soft' : 'text-nf-ink/80' }}">Beranda</a>
                    <a href="{{ route('tentang') }}" class="px-3 py-2 rounded-lg hover:bg-nf-blue-soft hover:text-nf-blue-dark transition {{ request()->routeIs('tentang') ? 'text-nf-blue-dark bg-nf-blue-soft' : 'text-nf-ink/80' }}">Tentang Kami</a>
                    <div class="relative group">
                        <button class="px-3 py-2 rounded-lg hover:bg-nf-blue-soft hover:text-nf-blue-dark transition text-nf-ink/80 flex items-center gap-1">
                            Unit
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                        </button>
                        <div class="absolute left-0 top-full pt-2 hidden group-hover:block">
                            <div class="w-64 rounded-2xl border border-nf-blue/25 bg-white shadow-xl p-2">
                                @foreach([['daycare','Daycare','Usia 6 bln – 2 th'],['kbit','KBIT','Usia 2 – 4 th'],['sdit','SDIT','Kelas 1 – 6'],['smpit','SMPIT','Kelas 7 – 9']] as [$s,$n,$a])
                                <a href="{{ route('unit', $s) }}" class="flex items-center justify-between px-3 py-2.5 rounded-xl hover:bg-nf-cream transition">
                                    <span class="font-bold text-nf-ink">{{ $n }}</span>
                                    <span class="text-xs text-nf-ink/55">{{ $a }}</span>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('berita') }}" class="px-3 py-2 rounded-lg hover:bg-nf-blue-soft hover:text-nf-blue-dark transition {{ request()->routeIs('berita*') ? 'text-nf-blue-dark bg-nf-blue-soft' : 'text-nf-ink/80' }}">Berita</a>
                    <a href="{{ route('galeri') }}" class="px-3 py-2 rounded-lg hover:bg-nf-blue-soft hover:text-nf-blue-dark transition {{ request()->routeIs('galeri') ? 'text-nf-blue-dark bg-nf-blue-soft' : 'text-nf-ink/80' }}">Galeri</a>
                    <a href="{{ route('kontak') }}" class="px-3 py-2 rounded-lg hover:bg-nf-blue-soft hover:text-nf-blue-dark transition {{ request()->routeIs('kontak') ? 'text-nf-blue-dark bg-nf-blue-soft' : 'text-nf-ink/80' }}">Kontak</a>
                </nav>
                <div class="flex items-center gap-2">
                    <a href="{{ route('ppdb') }}" class="hidden sm:inline-flex items-center gap-2 bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-5 py-2.5 rounded-full shadow-lg shadow-nf-blue/30 transition">
                        Daftar PPDB
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
                    </a>
                    <button id="menuBtn" class="lg:hidden grid place-items-center w-11 h-11 rounded-xl border border-nf-blue/30 text-nf-ink" aria-label="Buka menu">
                        <svg id="menuIconOpen" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                        <svg id="menuIconClose" class="w-5 h-5 hidden" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/></svg>
                    </button>
                </div>
            </div>
        </div>
        <div id="mobileMenu" class="lg:hidden hidden border-t border-nf-blue/15 bg-white">
            <div class="px-4 py-3 grid gap-1 text-sm font-bold">
                @foreach([['home','Beranda'],['tentang','Tentang Kami'],['berita','Berita'],['galeri','Galeri'],['kontak','Kontak']] as [$r,$l])
                <a href="{{ route($r) }}" class="px-3 py-2.5 rounded-xl hover:bg-nf-cream {{ request()->routeIs($r.'*') ? 'bg-nf-blue-soft text-nf-blue-dark' : 'text-nf-ink/85' }}">{{ $l }}</a>
                @endforeach
                <p class="px-3 pt-2 text-xs uppercase tracking-widest text-nf-ink/50">Unit Pendidikan</p>
                <div class="grid grid-cols-2 gap-2 pb-1">
                    @foreach([['daycare','Daycare'],['kbit','KBIT'],['sdit','SDIT'],['smpit','SMPIT']] as [$s,$n])
                    <a href="{{ route('unit', $s) }}" class="px-3 py-2.5 rounded-xl bg-nf-cream text-nf-ink text-center">{{ $n }}</a>
                    @endforeach
                </div>
                <a href="{{ route('ppdb') }}" class="mt-1 text-center bg-nf-blue text-white font-heading font-bold px-4 py-3 rounded-xl">Daftar PPDB 2026/2027</a>
            </div>
        </div>
    </header>

    <main>@yield('content')</main>

    {{-- Footer --}}
    <footer class="mt-0 bg-nf-ink text-white relative overflow-hidden">
        <div class="absolute inset-0 islamic-pattern opacity-30 pointer-events-none"></div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 pt-14 pb-8">
            <div class="grid gap-10 md:grid-cols-4">
                <div class="md:col-span-1">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('logo.webp') }}" alt="Logo Nurul Fikri Balikpapan" class="w-11 h-11 rounded-2xl object-contain bg-white">
                        <span class="font-heading font-bold text-lg">Nurul Fikri <span class="text-nf-yellow">Balikpapan</span></span>
                    </div>
                    <p class="mt-4 text-sm text-white/70 leading-relaxed">Sekolah Islam Terpadu: Daycare, KBIT, SDIT, dan SMPIT. Memadukan iman, ilmu, dan akhlak di setiap langkah.</p>
                </div>
                <div>
                    <h4 class="font-heading font-bold text-nf-yellow tracking-wide text-sm uppercase">Unit</h4>
                    <ul class="mt-4 space-y-2.5 text-sm text-white/75">
                        <li><a class="hover:text-nf-yellow transition" href="{{ route('unit','daycare') }}">Daycare, 6 bln–2 th</a></li>
                        <li><a class="hover:text-nf-yellow transition" href="{{ route('unit','kbit') }}">KBIT, 2–4 th</a></li>
                        <li><a class="hover:text-nf-yellow transition" href="{{ route('unit','sdit') }}">SDIT, 1–6</a></li>
                        <li><a class="hover:text-nf-yellow transition" href="{{ route('unit','smpit') }}">SMPIT, 7–9</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-heading font-bold text-nf-yellow tracking-wide text-sm uppercase">Tautan</h4>
                    <ul class="mt-4 space-y-2.5 text-sm text-white/75">
                        <li><a class="hover:text-nf-yellow transition" href="{{ route('tentang') }}">Tentang Kami</a></li>
                        <li><a class="hover:text-nf-yellow transition" href="{{ route('berita') }}">Berita & Kegiatan</a></li>
                        <li><a class="hover:text-nf-yellow transition" href="{{ route('galeri') }}">Galeri</a></li>
                        <li><a class="hover:text-nf-yellow transition" href="{{ route('ppdb') }}">PPDB Online</a></li>
                        <li><a class="hover:text-nf-yellow transition" href="{{ route('ppdb.status') }}">Cek Status Pendaftaran</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-heading font-bold text-nf-yellow tracking-wide text-sm uppercase">Hubungi Kami</h4>
                    <ul class="mt-4 space-y-2.5 text-sm text-white/75">
                        <li>Jl. Pendidikan No. 1, Balikpapan Selatan, Kalimantan Timur</li>
                        <li>(0542) 123-456 · 08.00–16.00 WITA</li>
                        <li>info@nurulfikri-balikpapan.sch.id</li>
                    </ul>
                    <a href="{{ route('ppdb') }}" class="mt-5 inline-flex items-center gap-2 bg-nf-yellow text-nf-ink font-heading font-bold text-sm px-5 py-2.5 rounded-full hover:bg-white transition">Daftar Sekarang</a>
                </div>
            </div>
            <div class="mt-10 pt-6 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-white/55">
                <p>© 2026 Nurul Fikri Balikpapan. Seluruh hak cipta dilindungi.</p>
                <p>Sekolah Islam Terpadu: Daycare · KBIT · SDIT · SMPIT</p>
            </div>
        </div>
    </footer>

    {{-- WhatsApp float --}}
    <a href="https://wa.me/62542123456?text=Assalamu%27alaikum%2C%20saya%20ingin%20bertanya%20tentang%20PPDB%20Nurul%20Fikri%20Balikpapan"
       target="_blank" rel="noopener"
       class="fixed bottom-5 right-5 z-50 group flex items-center gap-2 bg-[#25D366] text-white pl-4 pr-4 py-3 rounded-full shadow-2xl hover:scale-105 transition"
       aria-label="Chat WhatsApp sekolah">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2Zm5.2 14.2c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .2-3.4-.7-2.9-1.2-4.7-4.1-4.9-4.3-.1-.2-1.1-1.5-1.1-2.9s.7-2 1-2.3c.2-.3.5-.3.7-.3h.5c.2 0 .4 0 .6.5s.8 1.9.8 2c.1.1.1.3 0 .5-.3.6-.6.8-.4 1.1.6 1 1.4 1.9 2.5 2.4.3.2.5.1.7-.1l.8-.9c.2-.3.4-.2.7-.1l2 1c.3.1.5.2.6.4 0 .1 0 .6-.3 1.5Z"/></svg>
        <span class="text-sm font-bold hidden sm:inline">Tanya PPDB</span>
    </a>

    {{-- Watermark global --}}
    <div class="watermark-overlay pointer-events-none fixed inset-0 z-[100]" aria-hidden="true"></div>

    <script>
        // mobile menu
        const menuBtn = document.getElementById('menuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const io = document.getElementById('menuIconOpen');
        const ic = document.getElementById('menuIconClose');
        if (menuBtn) menuBtn.addEventListener('click', () => {
            const open = mobileMenu.classList.toggle('hidden');
            io.classList.toggle('hidden', !open ? true : false);
            ic.classList.toggle('hidden', !open ? false : true);
        });

        // reveal on scroll
        const revealObs = new IntersectionObserver((entries) => {
            entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); revealObs.unobserve(e.target); } });
        }, { threshold: 0.12 });
        document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));

        // animated counters
        const cntObs = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (!e.isIntersecting) return;
                const el = e.target;
                cntObs.unobserve(el);
                const target = parseInt(el.dataset.count || '0', 10);
                const dur = 1400; const t0 = performance.now();
                const step = (t) => {
                    const p = Math.min((t - t0) / dur, 1);
                    el.textContent = Math.round(target * (1 - Math.pow(1 - p, 3))).toLocaleString('id-ID');
                    if (p < 1) requestAnimationFrame(step);
                };
                requestAnimationFrame(step);
            });
        }, { threshold: 0.5 });
        document.querySelectorAll('[data-count]').forEach(el => cntObs.observe(el));

        // rotating hero words
        const heroWords = document.getElementById('heroWords');
        if (heroWords) {
            const words = JSON.parse(heroWords.dataset.words || '[]');
            let i = 0;
            const render = () => {
                heroWords.innerHTML = '';
                const span = document.createElement('span');
                span.className = 'hero-word inline-block';
                span.textContent = words[i % words.length] || '';
                heroWords.appendChild(span);
            };
            render();
            setInterval(() => { i++; render(); }, 5000);
        }

        // tabs (fitur unggulan)
        document.querySelectorAll('[data-tabs]').forEach(group => {
            const btns = group.querySelectorAll('[data-tab-btn]');
            const panels = group.querySelectorAll('[data-tab-panel]');
            btns.forEach(btn => btn.addEventListener('click', () => {
                btns.forEach(b => {
                    const active = b === btn;
                    b.classList.toggle('bg-nf-ink', active);
                    b.classList.toggle('text-white', active);
                    b.classList.toggle('bg-white', !active);
                    b.classList.toggle('text-nf-ink/70', !active);
                });
                panels.forEach(p => p.classList.toggle('hidden', p.dataset.tabPanel !== btn.dataset.tabBtn));
            }));
        });

        // gallery filter + lightbox
        const filterBtns = document.querySelectorAll('[data-filter-btn]');
        const galItems = document.querySelectorAll('[data-gal-cat]');
        filterBtns.forEach(btn => btn.addEventListener('click', () => {
            filterBtns.forEach(b => {
                const on = b === btn;
                b.classList.toggle('bg-nf-blue', on);
                b.classList.toggle('text-white', on);
            });
            const f = btn.dataset.filterBtn;
            galItems.forEach(it => it.classList.toggle('hidden', f !== 'semua' && it.dataset.galCat !== f));
        }));
        const lightbox = document.getElementById('lightbox');
        if (lightbox) {
            const lbTitle = document.getElementById('lbTitle');
            const lbMeta = document.getElementById('lbMeta');
            galItems.forEach(it => it.addEventListener('click', () => {
                lbTitle.textContent = it.dataset.galTitle || 'Dokumentasi';
                lbMeta.textContent = (it.dataset.galCat || '') + ' · ' + (it.dataset.galUnit || '');
                lightbox.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }));
            lightbox.addEventListener('click', (e) => {
                if (e.target.closest('[data-lb-close]') || e.target === lightbox) {
                    lightbox.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            });
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') { lightbox.classList.add('hidden'); document.body.style.overflow = ''; }
            });
        }

        // fake PPDB submit -> success panel
        const ppdbForm = document.getElementById('ppdbForm');
        if (ppdbForm) ppdbForm.addEventListener('submit', (e) => {
            e.preventDefault();
            document.getElementById('ppdbFormWrap').classList.add('hidden');
            const ok = document.getElementById('ppdbSuccess');
            ok.classList.remove('hidden');
            ok.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    </script>
</body>
</html>
