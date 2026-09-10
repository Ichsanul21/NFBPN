@extends('layouts.app')
@section('title', 'Tentang Kami')

@section('content')
<section class="relative overflow-hidden bg-nf-ink text-white">
    <div class="absolute inset-0 islamic-pattern"></div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 py-14 md:py-20">
        <p class="text-xs font-bold tracking-[0.25em] uppercase text-nf-yellow">Tentang Kami</p>
        <h1 class="mt-3 font-heading font-extrabold text-3xl md:text-5xl max-w-3xl leading-tight">Mengenal Nurul Fikri Balikpapan lebih dekat.</h1>
        <p class="mt-4 text-white/70 max-w-2xl">Sekolah Islam Terpadu dengan empat jenjang dalam satu pembinaan terpadu: Daycare, KBIT, SDIT, dan SMPIT.</p>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 sm:px-6 py-14">
    <div class="grid lg:grid-cols-2 gap-8 items-start">
        <div class="reveal visible rounded-3xl bg-nf-cream border border-nf-blue/20 p-8">
            <h2 class="font-heading font-extrabold text-2xl">Profil Singkat</h2>
            <div class="islamic-divider my-5"></div>
            <div class="space-y-4 text-nf-ink/75 leading-relaxed">
                <p>Nurul Fikri Balikpapan adalah lembaga pendidikan Islam terpadu yang memadukan kurikulum nasional dengan pembinaan iman, ibadah, dan akhlak. Berawal dari kepedulian terhadap mutu pendidikan Islam, sekolah ini tumbuh menjadi rujukan orang tua di Balikpapan.</p>
                <p>Pembelajaran dilaksanakan fullday dengan sistem sentra (PAUD), tematik (SDIT), dan pendalaman mapel plus tahfidz (SMPIT). Setiap siswa dipandang sebagai amanah yang berhak berprestasi dan mendapatkan pelayanan terbaik.</p>
                <p>Kurikulum khas SMART (<strong>Sholeh, Muslih, cerdAs, mandiRi, Terampil</strong>) menjadi kompas seluruh program, dari pembiasaan doa harian hingga riset mini dan pengabdian masyarakat.</p>
            </div>
        </div>
        <div class="grid gap-5">
            <div class="reveal visible rounded-3xl bg-nf-blue text-white p-8">
                <h2 class="font-heading font-extrabold text-2xl">Visi</h2>
                <p class="mt-3 text-lg leading-relaxed text-white/95">Menjadi sekolah Islam terpadu unggulan yang melahirkan generasi qur’ani: cerdas, mandiri, terampil, dan bermanfaat bagi umat.</p>
            </div>
            <div class="reveal visible rounded-3xl bg-white border border-nf-blue/20 p-8">
                <h2 class="font-heading font-extrabold text-2xl">Misi</h2>
                <ul class="mt-4 space-y-3 text-nf-ink/75">
                    @foreach(['Menyelenggarakan pembelajaran terpadu IPTEK & IMTAK yang menyenangkan.','Membina tahfidz, ibadah, dan akhlak secara bertahap dan berkesinambungan.','Mengembangkan potensi akademik, bakat, dan kepemimpinan tiap siswa.','Bermitra erat dengan orang tua sebagai pendidik utama di rumah.'] as $m)
                    <li class="flex gap-3"><svg class="w-5 h-5 shrink-0 text-nf-blue-dark mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.7-9.3a1 1 0 0 0-1.4-1.4L9 10.6 7.7 9.3a1 1 0 0 0-1.4 1.4l2 2a1 1 0 0 0 1.4 0l4-4Z" clip-rule="evenodd"/></svg><span>{{ $m }}</span></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="mt-10 rounded-3xl border border-nf-blue/20 overflow-hidden">
        <div class="bg-nf-ink text-white px-8 py-6 relative overflow-hidden">
            <div class="absolute inset-0 islamic-pattern opacity-50"></div>
            <h2 class="relative font-heading font-extrabold text-2xl">Jejak Perjalanan</h2>
        </div>
        <div class="grid md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-nf-blue/10">
            @foreach([['2008','Awal berdiri','Layanan PAUD & penitipan anak dimulai dengan 2 kelas.'],['2013','SDIT dibuka','Angkatan pertama SDIT dengan program tahfidz bertahap.'],['2019','SMPIT menyusul','Jenjang menengah + kelas olimpiade & leadership.'],['2026','Satu kampus terpadu','Daycare–SMPIT dalam pembinaan berkesinambungan.']] as [$y,$t,$d])
            <div class="p-7 bg-white">
                <p class="font-heading font-extrabold text-3xl text-nf-blue">{{ $y }}</p>
                <p class="mt-2 font-heading font-bold">{{ $t }}</p>
                <p class="mt-1 text-sm text-nf-ink/60">{{ $d }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <div class="mt-10">
        <h2 class="font-heading font-extrabold text-2xl">Struktur Organisasi</h2>
        <div class="mt-5 grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach([['Kepala Sekolah','Ust. Ahmad Hidayat, M.Pd.','G'],['Waka Kurikulum','Ustzh. Siti Maryam, S.Pd.','B'],['Kepala Daycare & KBIT','Ustzh. Nur Aisyah, S.Psi.','G'],['Kepala SMPIT','Ust. Budi Santoso, S.Si.','B']] as [$j,$n,$c])
            <div class="rounded-3xl bg-white border border-nf-blue/20 p-6 text-center hover:shadow-xl transition">
                <span class="mx-auto grid place-items-center w-16 h-16 rounded-full font-heading font-extrabold text-2xl bg-nf-blue-soft text-nf-blue-dark">{{ mb_substr($n, -1, 1) === '.' ? mb_substr(trim($n),0,1) : mb_substr($n,0,1) }}</span>
                <p class="mt-3 font-heading font-bold">{{ $n }}</p>
                <p class="text-sm text-nf-ink/55">{{ $j }}</p>
            </div>
            @endforeach
        </div>
        <p class="mt-4 text-xs text-nf-ink/45">* Nama & foto bersifat placeholder dan akan diganti data resmi sekolah.</p>
    </div>
</section>
@endsection
