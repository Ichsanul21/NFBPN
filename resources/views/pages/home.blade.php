@extends('layouts.app')
@section('title', 'Beranda')

@section('content')

{{-- 3. HERO: pattern islami + rotating tagline --}}
<section class="relative overflow-hidden bg-nf-ink text-white">
    <div class="absolute inset-0 islamic-pattern opacity-40"></div>
    <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-nf-blue/20 blur-3xl"></div>
    <div class="absolute -bottom-32 -left-24 w-96 h-96 rounded-full bg-nf-blue/25 blur-3xl"></div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 py-16 md:py-24 grid lg:grid-cols-2 gap-10 items-center">
        <div>
            <p class="font-arabic text-2xl md:text-3xl text-nf-yellow text-left" dir="rtl">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</p>
            <h1 class="mt-5 font-heading font-extrabold text-3xl sm:text-4xl md:text-5xl leading-[1.15]">
                Di Nurul Fikri, kami menumbuhkan
                <span id="heroWords" data-words='@json($heroWords)' class="block mt-2 text-nf-yellow min-h-[2.6em]"></span>
            </h1>
            <p class="mt-5 text-white/75 max-w-xl leading-relaxed">Sekolah Islam Terpadu di Balikpapan untuk Daycare, KBIT, SDIT, dan SMPIT yang memadukan iman, ilmu, dan akhlak dalam setiap langkah anak.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('ppdb') }}" class="inline-flex items-center gap-2 bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold px-7 py-3.5 rounded-full shadow-xl shadow-nf-blue/30 transition">
                    Info PPDB & Daftar
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
                </a>
                <a href="{{ route('tentang') }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 font-heading font-bold px-7 py-3.5 rounded-full transition">Mengenal Sekolah</a>
            </div>
            <div class="mt-8 flex items-center gap-4 text-xs text-white/60 font-bold">
                <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-nf-green" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.7-9.3a1 1 0 0 0-1.4-1.4L9 10.6 7.7 9.3a1 1 0 0 0-1.4 1.4l2 2a1 1 0 0 0 1.4 0l4-4Z" clip-rule="evenodd"/></svg> Terakreditasi</span>
                <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-nf-green" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.7-9.3a1 1 0 0 0-1.4-1.4L9 10.6 7.7 9.3a1 1 0 0 0-1.4 1.4l2 2a1 1 0 0 0 1.4 0l4-4Z" clip-rule="evenodd"/></svg> Fullday Islami</span>
                <span class="flex items-center gap-1.5"><svg class="w-4 h-4 text-nf-green" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.7-9.3a1 1 0 0 0-1.4-1.4L9 10.6 7.7 9.3a1 1 0 0 0-1.4 1.4l2 2a1 1 0 0 0 1.4 0l4-4Z" clip-rule="evenodd"/></svg> Tahfidz Bertahap</span>
            </div>
        </div>
        <div class="relative">
            <div class="grid grid-cols-2 gap-4">
                <div class="rounded-3xl bg-gradient-to-br from-nf-blue to-nf-blue-dark p-6 text-white shadow-2xl animate-floaty">
                    <p class="font-heading font-extrabold text-4xl">4</p>
                    <p class="mt-1 text-sm font-bold text-white/90">Jenjang dalam satu kampus terpadu</p>
                    <div class="mt-4 flex gap-1.5">
                        @foreach(['DC','KB','SD','SMP'] as $t)<span class="text-[11px] font-bold bg-white/20 rounded-lg px-2.5 py-1">{{ $t }}</span>@endforeach
                    </div>
                </div>
                <div class="rounded-3xl bg-white text-nf-ink p-6 shadow-2xl mt-8">
                    <p class="font-arabic text-3xl text-nf-blue-dark" dir="rtl">ٱقْرَأْ</p>
                    <p class="mt-1 font-heading font-bold">“Bacalah…” (QS. Al-‘Alaq: 1)</p>
                    <p class="mt-2 text-sm text-nf-ink/60">Literasi, sains, dan iman tumbuh bersama sejak dini.</p>
                </div>
                <div class="rounded-3xl bg-gradient-to-br from-nf-green to-nf-green-dark text-white p-6 shadow-2xl -mt-2">
                    <p class="font-heading font-extrabold text-4xl">7.15</p>
                    <p class="mt-1 text-sm font-bold text-white/90">Hari dimulai dengan doa & shalat dhuha bersama</p>
                </div>
                <div class="rounded-3xl bg-nf-yellow text-nf-ink p-6 shadow-2xl mt-6">
                    <p class="font-heading font-extrabold text-4xl">SMART</p>
                    <p class="mt-1 text-sm font-bold">Sholeh · Muslih · cerdAs · mandiRi · Terampil</p>
                </div>
            </div>
        </div>
    </div>
    <div class="relative bg-nf-yellow/15 border-t border-white/10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 py-3 overflow-hidden">
            <div class="flex whitespace-nowrap animate-marquee gap-10 text-xs font-bold tracking-widest uppercase text-nf-yellow">
                @foreach(array_merge(['Tahfidz Bertahap','Sains Project-Based','Bahasa Inggris & Arab','Shalat Berjamaah','Ekstrakurikuler Lengkap','Laporan Harian Orang Tua'],['Tahfidz Bertahap','Sains Project-Based','Bahasa Inggris & Arab','Shalat Berjamaah','Ekstrakurikuler Lengkap','Laporan Harian Orang Tua']) as $t)
                <span class="flex items-center gap-3"><span class="w-1.5 h-1.5 rotate-45 bg-nf-green inline-block"></span>{{ $t }}</span>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- 4. PESAN SINGKAT: Purpose & Promise --}}
<section class="mx-auto max-w-7xl px-4 sm:px-6 py-14 md:py-20">
    <div class="reveal max-w-3xl">
        <p class="text-xs font-bold tracking-[0.25em] uppercase text-nf-blue">Tentang Nurul Fikri Balikpapan</p>
        <h2 class="mt-3 font-heading font-extrabold text-2xl md:text-4xl leading-tight">Sekolah tempat rasa ingin tahu tumbuh, ukhuwah menguat, dan karakter bersemi.</h2>
    </div>
    <div class="islamic-divider my-8 reveal"></div>
    <div class="grid md:grid-cols-2 gap-6">
        <div class="reveal rounded-3xl bg-nf-cream border border-nf-blue/20 p-8">
            <p class="text-xs font-bold tracking-widest uppercase text-nf-blue-dark">Tujuan Kami</p>
            <p class="mt-3 text-lg leading-relaxed text-nf-ink/85">Menumbuhkan generasi yang berani berubah dengan belajar lewat pengalaman langsung dan kurikulum yang menantang, sehingga mencintai ilmu dan memberi manfaat bagi sesama.</p>
        </div>
        <div class="reveal rounded-3xl bg-nf-ink text-white p-8 relative overflow-hidden">
            <div class="absolute inset-0 islamic-pattern opacity-50"></div>
            <div class="relative">
                <p class="text-xs font-bold tracking-widest uppercase text-nf-yellow">Janji Kami</p>
                <p class="mt-3 text-lg leading-relaxed text-white/90">Anak adalah pusat dari semua yang kami lakukan. Inilah komunitas belajar yang hangat, di mana kesejahteraan, kedekatan, dan tujuan menggerakkan setiap penemuan. Karena pendidikan adalah sebuah petualangan.</p>
            </div>
        </div>
    </div>
</section>

{{-- 5. UNIT CARDS --}}
<section class="bg-nf-cream border-y border-nf-blue/15">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 py-14 md:py-20">
        <div class="reveal flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold tracking-[0.25em] uppercase text-nf-blue">Unit Pendidikan</p>
                <h2 class="mt-3 font-heading font-extrabold text-2xl md:text-4xl">Satu perjalanan, dari bayi hingga remaja.</h2>
            </div>
            <a href="{{ route('ppdb') }}" class="font-bold text-nf-blue-dark hover:underline">Lihat info PPDB →</a>
        </div>
        <div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($units as $i => $u)
            <a href="{{ route('unit', $u['slug']) }}" class="reveal group rounded-3xl bg-white border border-nf-blue/20 overflow-hidden shadow-sm hover:shadow-2xl hover:-translate-y-1.5 transition-all duration-300">
                <div class="h-28 bg-gradient-to-br from-nf-blue to-nf-blue-dark relative overflow-hidden">
                    <div class="absolute inset-0 islamic-pattern opacity-40"></div>
                    <span class="absolute bottom-3 left-5 font-heading font-extrabold text-white/95 text-4xl">{{ $u['initial'] }}</span>
                    <span class="absolute top-3 right-3 text-[11px] font-bold bg-white/90 text-nf-ink rounded-full px-3 py-1">{{ $u['ages'] }}</span>
                </div>
                <div class="p-5">
                    <h3 class="font-heading font-bold text-lg group-hover:text-nf-blue-dark transition">{{ $u['full'] }}</h3>
                    <p class="mt-1.5 text-sm text-nf-ink/65 leading-relaxed">{{ $u['tagline'] }}</p>
                    <span class="mt-4 inline-flex items-center gap-1.5 text-sm font-bold text-nf-blue">Jelajahi unit
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
                    </span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- 6. STATISTIK --}}
<section class="mx-auto max-w-7xl px-4 sm:px-6 py-14 md:py-20">
    <div class="reveal text-center max-w-2xl mx-auto">
        <p class="text-xs font-bold tracking-[0.25em] uppercase text-nf-blue">Sekilas Angka</p>
        <h2 class="mt-3 font-heading font-extrabold text-2xl md:text-4xl">Nurul Fikri dalam angka</h2>
    </div>
    <div class="mt-10 grid grid-cols-2 lg:grid-cols-4 gap-5">
        @foreach($stats as $s)
        <div class="reveal rounded-3xl border border-nf-blue/20 bg-white p-7 text-center shadow-sm hover:shadow-xl transition">
            <p class="font-heading font-extrabold text-4xl md:text-5xl text-nf-blue-dark"><span data-count="{{ $s['value'] }}">0</span><span class="text-nf-green-dark">{{ $s['suffix'] }}</span></p>
            <p class="mt-2 text-sm font-bold text-nf-ink/60">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- 7. FITUR UNGGULAN + TAB --}}
<section class="bg-nf-ink text-white relative overflow-hidden">
    <div class="absolute inset-0 islamic-pattern opacity-30"></div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 py-14 md:py-20">
        <div class="reveal max-w-2xl">
            <p class="text-xs font-bold tracking-[0.25em] uppercase text-nf-yellow">Hanya di Nurul Fikri</p>
            <h2 class="mt-3 font-heading font-extrabold text-2xl md:text-4xl">Pengalaman autentik yang membentuk pemimpin masa depan.</h2>
        </div>
        <div class="mt-8" data-tabs>
            <div class="reveal flex flex-wrap gap-2">
                <button data-tab-btn="program" class="bg-nf-blue text-white font-heading font-bold text-sm px-5 py-2.5 rounded-full transition">Program Unggulan</button>
                <button data-tab-btn="fasilitas" class="bg-white text-nf-ink/70 font-heading font-bold text-sm px-5 py-2.5 rounded-full transition">Fasilitas</button>
                <button data-tab-btn="ekskul" class="bg-white text-nf-ink/70 font-heading font-bold text-sm px-5 py-2.5 rounded-full transition">Ekstrakurikuler</button>
            </div>
            <div data-tab-panel="program" class="mt-6 grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach([['Tahfidz Bertahap','Setoran & tasmi rutin dengan target tiap semester, didampingi guru bersanad.'],['Sains Project-Based','Belajar IPA & matematika lewat eksperimen dan proyek nyata tiap tema.'],['Bilingual Harian','Pembiasaan bahasa Inggris & Arab komunikatif dalam aktivitas sehari-hari.'],['Mentoring Akhlak','Kelompok kecil bersama guru untuk adab, ibadah, dan kemandirian.'],['Riset Mini (SMPIT)','Melatih berpikir ilmiah lewat penelitian sederhana dan presentasi.'],['Kelas Olimpiade','Pembinaan MIPA, bahasa & tahfidz untuk yang siap berkompetisi.']] as [$t,$d])
                <div class="reveal rounded-3xl bg-white/5 border border-white/12 p-6 hover:bg-white/10 hover:border-nf-blue/60 transition">
                    <span class="inline-block w-2.5 h-2.5 rotate-45 bg-nf-yellow"></span>
                    <h3 class="mt-3 font-heading font-bold text-lg">{{ $t }}</h3>
                    <p class="mt-1.5 text-sm text-white/65 leading-relaxed">{{ $d }}</p>
                </div>
                @endforeach
            </div>
            <div data-tab-panel="fasilitas" class="hidden mt-6 grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach([['Ruang Kelas Ber-AC','Terang, aman, dan dilengkapi pojok literasi di tiap kelas.'],['Masjid & Aula','Pusat ibadah, tasmi akbar, dan perayaan hari besar Islam.'],['Lab Sains & Komputer','Praktik langsung untuk SDIT & SMPIT dengan pendampingan guru.'],['Perpustakaan','Koleksi buku anak, referensi, dan program readathon rutin.'],['Lapangan & Playground','Area olahraga dan bermain aman untuk semua jenjang.'],['UKS & Dapur Sehat','Layanan kesehatan dan katering bergizi dari dapur sekolah.']] as [$t,$d])
                <div class="rounded-3xl bg-white/5 border border-white/12 p-6 hover:bg-white/10 hover:border-nf-blue/70 transition">
                    <span class="inline-block w-2.5 h-2.5 rotate-45 bg-nf-blue"></span>
                    <h3 class="mt-3 font-heading font-bold text-lg">{{ $t }}</h3>
                    <p class="mt-1.5 text-sm text-white/65 leading-relaxed">{{ $d }}</p>
                </div>
                @endforeach
            </div>
            <div data-tab-panel="ekskul" class="hidden mt-6 grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach([['Panahan & Futsal','Melatih fokus, sportivitas, dan kerja sama tim.'],['Pramuka','Kemandirian, kepemimpinan, dan cinta alam.'],['Robotik & Koding','Logika dan kreativitas lewat proyek teknologi.'],['Tahsin & Kaligrafi','Mempercantik bacaan Al-Qur’an dan seni Islami.'],['Basket & Badminton','Kebugaran dan kompetisi antarsekolah (SMPIT).'],['Jurnalistik & Media','Majalah dinding, podcast siswa, dan klub fotografi.']] as [$t,$d])
                <div class="rounded-3xl bg-white/5 border border-white/12 p-6 hover:bg-white/10 hover:border-nf-yellow/70 transition">
                    <span class="inline-block w-2.5 h-2.5 rotate-45 bg-nf-green"></span>
                    <h3 class="mt-3 font-heading font-bold text-lg">{{ $t }}</h3>
                    <p class="mt-1.5 text-sm text-white/65 leading-relaxed">{{ $d }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- 8. NILAI KHAS --}}
<section class="islamic-pattern-light">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 py-14 md:py-20">
        <div class="reveal text-center max-w-2xl mx-auto">
            <p class="text-xs font-bold tracking-[0.25em] uppercase text-nf-blue">Yang membuat kami, kami</p>
            <h2 class="mt-3 font-heading font-extrabold text-2xl md:text-4xl">Nurul Fikri adalah tempat ilmu mengambil langkah; dalam setiap pertanyaan dan kebaikan, anak diundang menjadi insan seutuhnya.</h2>
        </div>
        <div class="mt-10 grid sm:grid-cols-2 lg:grid-cols-5 gap-5">
            @foreach($values as $v)
            <div class="reveal rounded-3xl bg-white border border-nf-blue/20 p-6 text-center shadow-sm hover:shadow-xl hover:-translate-y-1 transition">
                <span class="mx-auto grid place-items-center w-12 h-12 rounded-2xl bg-nf-blue-soft text-nf-blue-dark font-heading font-extrabold text-xl">{{ mb_substr($v['word'],0,1) }}</span>
                <h3 class="mt-3 font-heading font-bold text-lg">{{ $v['word'] }}</h3>
                <p class="mt-1.5 text-sm text-nf-ink/60 leading-relaxed">{{ $v['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 9. TESTIMONI --}}
<section class="mx-auto max-w-7xl px-4 sm:px-6 py-14 md:py-20">
    <div class="reveal flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-xs font-bold tracking-[0.25em] uppercase text-nf-blue">Kata Mereka</p>
            <h2 class="mt-3 font-heading font-extrabold text-2xl md:text-4xl">Cerita dari keluarga besar kami.</h2>
        </div>
    </div>
    <div class="mt-8 grid md:grid-cols-3 gap-5">
        @foreach($testimonials as $t)
        <figure class="reveal rounded-3xl bg-nf-cream border border-nf-blue/20 p-7 flex flex-col">
            <div class="flex gap-1 text-nf-yellow-dark" aria-label="rating">
                @for($i=0;$i<5;$i++)<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10 1.5 12.6 7l6 .4-4.5 4 1.3 5.9L10 14.3 4.6 17.3 5.9 11.4 1.4 7.4l6-.4L10 1.5Z"/></svg>@endfor
            </div>
            <blockquote class="mt-4 text-nf-ink/85 leading-relaxed flex-1">“{{ $t['quote'] }}”</blockquote>
            <figcaption class="mt-5 pt-5 border-t border-nf-blue/15">
                <p class="font-heading font-bold">{{ $t['name'] }}</p>
                <p class="text-sm text-nf-ink/55">{{ $t['role'] }}</p>
            </figcaption>
        </figure>
        @endforeach
    </div>
</section>

{{-- 10. PATH: Mengenal / Merasakan / Mendaftar --}}
<section class="bg-gradient-to-br from-nf-blue-soft via-white to-nf-blue-soft border-y border-nf-blue/15">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 py-14 md:py-20">
        <div class="reveal text-center max-w-2xl mx-auto">
            <p class="text-xs font-bold tracking-[0.25em] uppercase text-nf-blue">Mulai Perjalanan</p>
            <h2 class="mt-3 font-heading font-extrabold text-2xl md:text-4xl">Telusuri jalan melewati kemungkinan.</h2>
        </div>
        <div class="mt-10 grid md:grid-cols-3 gap-5">
            @foreach([['01','Mengenal','Selami kurikulum, program tahfidz, dan kehidupan kampus lewat tur sekolah atau brosur digital.','tentang','Pelajari Profil'],['02','Merasakan','Ikuti trial class & observasi agar anak merasakan langsung suasana belajar di kelas.','kontak','Jadwalkan Kunjungan'],['03','Mendaftar','Isi formulir PPDB online dalam 10 menit. Tim kami menghubungi maks. 2 hari kerja.','ppdb','Daftar PPDB']] as [$n,$t,$d,$r,$c])
            <div class="reveal rounded-3xl bg-white border border-nf-blue/20 p-8 shadow-sm hover:shadow-2xl hover:-translate-y-1.5 transition">
                <p class="font-heading font-extrabold text-5xl text-nf-blue/30">{{ $n }}</p>
                <h3 class="mt-2 font-heading font-extrabold text-xl">{{ $t }}</h3>
                <p class="mt-2 text-nf-ink/65 leading-relaxed">{{ $d }}</p>
                <a href="{{ route($r) }}" class="mt-5 inline-flex items-center gap-2 font-bold text-nf-blue hover:gap-3 transition-all">{{ $c }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 11. AGENDA --}}
<section class="mx-auto max-w-7xl px-4 sm:px-6 py-14 md:py-20">
    <div class="grid lg:grid-cols-5 gap-8">
        <div class="reveal lg:col-span-2">
            <p class="text-xs font-bold tracking-[0.25em] uppercase text-nf-blue">Agenda Terdekat</p>
            <h2 class="mt-3 font-heading font-extrabold text-2xl md:text-4xl leading-tight">Yang ada di agenda kami.</h2>
            <p class="mt-4 text-nf-ink/65 leading-relaxed">Jadwal observasi PPDB, tasmi akbar, dan kegiatan sekolah. Catat tanggalnya dan sampai jumpa di kampus.</p>
            <a href="{{ route('kontak') }}" class="mt-6 inline-flex items-center gap-2 bg-nf-ink text-white font-heading font-bold text-sm px-6 py-3 rounded-full hover:bg-nf-blue-dark transition">Lihat Kalender Lengkap</a>
        </div>
        <div class="lg:col-span-3 grid gap-4">
            @foreach($agenda as $a)
            <div class="reveal flex gap-5 items-start rounded-3xl border border-nf-blue/20 bg-white p-5 hover:shadow-xl transition">
                <div class="shrink-0 w-16 text-center rounded-2xl bg-nf-blue-soft py-3">
                    <p class="font-heading font-extrabold text-2xl text-nf-blue-dark leading-none">{{ $a['day'] }}</p>
                    <p class="text-xs font-bold text-nf-blue-dark/70 uppercase">{{ $a['month'] }}</p>
                </div>
                <div>
                    <h3 class="font-heading font-bold">{{ $a['title'] }}</h3>
                    <p class="mt-1 text-sm text-nf-ink/60">{{ $a['time'] }} · {{ $a['place'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 12. BERITA TERBARU (list minimalis) --}}
<section class="bg-nf-cream border-y border-nf-blue/15">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 py-14 md:py-20">
        <div class="reveal flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-xs font-bold tracking-[0.25em] uppercase text-nf-blue">Kabar Terbaru</p>
                <h2 class="mt-3 font-heading font-extrabold text-2xl md:text-4xl">Berita & kegiatan.</h2>
            </div>
            <a href="{{ route('berita') }}" class="font-bold text-nf-blue-dark hover:underline">Semua berita →</a>
        </div>
        <div class="mt-8 rounded-3xl bg-white border border-nf-blue/20 divide-y divide-nf-blue/10 overflow-hidden">
            @foreach($news as $n)
            <a href="{{ route('berita.detail', $n['slug']) }}" class="reveal flex items-center gap-4 sm:gap-6 px-5 sm:px-8 py-5 hover:bg-nf-blue-soft/50 transition group">
                <span class="hidden sm:inline-flex shrink-0 text-[11px] font-bold uppercase tracking-widest bg-nf-blue-soft text-nf-blue-dark rounded-full px-3 py-1.5">{{ $n['category'] }}</span>
                <span class="flex-1 min-w-0">
                    <span class="block font-heading font-bold text-nf-ink group-hover:text-nf-blue-dark transition truncate">{{ $n['title'] }}</span>
                    <span class="block text-sm text-nf-ink/55 truncate mt-0.5">{{ $n['excerpt'] }}</span>
                </span>
                <span class="shrink-0 text-xs font-bold text-nf-ink/45">{{ $n['date'] }}</span>
                <svg class="shrink-0 w-5 h-5 text-nf-blue-dark group-hover:translate-x-1 transition" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5-5 5M6 12h12"/></svg>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- 13. CTA PPDB --}}
<section class="relative overflow-hidden bg-nf-ink text-white">
    <div class="absolute inset-0 islamic-pattern opacity-40"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[36rem] h-[36rem] rounded-full bg-nf-blue/20 blur-3xl"></div>
    <div class="relative mx-auto max-w-4xl px-4 sm:px-6 py-16 md:py-24 text-center">
        <p class="reveal font-arabic text-3xl text-nf-yellow" dir="rtl">وَقُل رَّبِّ زِدْنِي عِلْمًا</p>
        <p class="reveal mt-2 text-sm text-white/60 italic">“Ya Tuhanku, tambahkanlah kepadaku ilmu.” (QS. Thaha: 114)</p>
        <h2 class="reveal mt-5 font-heading font-extrabold text-3xl md:text-5xl leading-tight">Siap menjadi bagian dari keluarga besar Nurul Fikri?</h2>
        <p class="reveal mt-4 text-white/70 max-w-xl mx-auto">Kuota PPDB 2026/2027 tiap jenjang terbatas. Isi formulir online tanpa antre dan tanpa ribet.</p>
        <div class="reveal mt-8 flex flex-wrap justify-center gap-3">
            <a href="{{ route('ppdb') }}" class="inline-flex items-center gap-2 bg-nf-yellow text-nf-ink font-heading font-extrabold px-8 py-4 rounded-full hover:bg-white transition shadow-xl">Daftar PPDB Sekarang</a>
            <a href="{{ route('ppdb.status') }}" class="inline-flex items-center gap-2 border border-white/25 hover:bg-white/10 font-heading font-bold px-8 py-4 rounded-full transition">Cek Status Pendaftaran</a>
        </div>
    </div>
</section>

@endsection
