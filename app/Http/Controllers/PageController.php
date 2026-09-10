<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    private function units(): array
    {
        return [
            [
                'slug' => 'daycare',
                'name' => 'Daycare',
                'full' => 'Daycare Nurul Fikri',
                'tagline' => 'Rumah kedua yang aman & penuh kasih untuk si kecil.',
                'ages' => 'Usia 6 bulan – 2 tahun',
                'hours' => 'Senin–Jumat, 07.00–17.00 WITA',
                'ratio' => '1 : 4',
                'color' => 'green',
                'initial' => 'DC',
                'desc' => 'Layanan penitipan anak dengan pengasuh terlatih, rutinitas harian yang menenangkan, stimulasi motorik & bahasa, serta pembiasaan doa dan adab sejak dini.',
                'features' => ['Pengasuh tersertifikasi & rasio kecil', 'Ruang tidur, bermain & makan terpisah', 'Laporan harian via grup orang tua', 'Stimulasi sensori-motor & bahasa', 'Pembiasaan doa makan, tidur & adab', 'Makanan sehat dari dapur sekolah'],
            ],
            [
                'slug' => 'kbit',
                'name' => 'KBIT',
                'full' => 'Kelompok Bermain Islam Terpadu',
                'tagline' => 'Bermain sambil belajar, menumbuhkan rasa ingin tahu.',
                'ages' => 'Usia 2 – 4 tahun',
                'hours' => 'Senin–Jumat, 07.30–11.00 WITA',
                'ratio' => '1 : 8',
                'color' => 'blue',
                'initial' => 'KB',
                'desc' => 'Program prasekolah berbasis bermain (play-based learning) yang memadukan kurikulum nasional dengan nilai Islami: tahfidz juz 30 awal, doa harian, dan proyek mini STEAM anak usia dini.',
                'features' => ['Play-based learning & sentra bermain', 'Tahfidz & doa harian yang menyenangkan', 'Motorik kasar-halus & pra-calistung', 'Kunjungan edukatif & cooking class', 'Asesmen perkembangan per semester', 'Transisi mulus menuju SDIT'],
            ],
            [
                'slug' => 'sdit',
                'name' => 'SDIT',
                'full' => 'SD Islam Terpadu',
                'tagline' => 'Cerdas akademik, kokoh akhlak, terampil berkarya.',
                'ages' => 'Usia 6 – 12 tahun (Kelas 1–6)',
                'hours' => 'Senin–Jumat, 07.15–15.30 WITA',
                'ratio' => '1 : 20',
                'color' => 'green',
                'initial' => 'SD',
                'desc' => 'Sekolah dasar fullday yang memadukan IPTEK dan IMTAK: kurikulum nasional yang diperkaya tahfidz, sains project-based, bahasa Inggris-Arab, dan pembinaan karakter SMART (Sholeh, Muslih, cerdAs, mandiRi, Terampil).',
                'features' => ['Tahfidz bertahap & tasmi berkala', 'Sains & matematika project-based', 'Bahasa Inggris & Arab komunikatif', 'Literasi & klub robotik/koding', 'Shalat berjamaah & mentoring akhlak', 'Ekstrakurikuler: futsal, panahan, pramuka'],
            ],
            [
                'slug' => 'smpit',
                'name' => 'SMPIT',
                'full' => 'SMP Islam Terpadu',
                'tagline' => 'Mandiri, berprestasi, siap memimpin masa depan.',
                'ages' => 'Usia 12 – 15 tahun (Kelas 7–9)',
                'hours' => 'Senin–Jumat, 07.00–15.45 WITA',
                'ratio' => '1 : 22',
                'color' => 'blue',
                'initial' => 'SMP',
                'desc' => 'Jenjang menengah yang menyiapkan kemandirian belajar, kepemimpinan, dan kesiapan SMA favorit: pendalaman tahfidz, riset mini, olimpiade sains, dan program leadership & pengabdian masyarakat.',
                'features' => ['Tahfidz lanjutan & tahsin intensif', 'Kelas olimpiade MIPA & bahasa', 'Riset mini & science project', 'Leadership, LDKS & OSIS', 'Bimbingan studi lanjut & psikolog', 'Ekstrakurikuler: basket, karya ilmiah, media'],
            ],
        ];
    }

    private function news(): array
    {
        return [
            [
                'slug' => 'ppdb-2026-2027-dibuka',
                'title' => 'PPDB Tahun Ajaran 2026/2027 Resmi Dibuka',
                'date' => '02 Sep 2026',
                'category' => 'PPDB',
                'excerpt' => 'Pendaftaran siswa baru Daycare, KBIT, SDIT, dan SMPIT telah dibuka. Kuota tiap jenjang terbatas — amankan kursi lebih awal.',
                'body' => ['Pendaftaran Peserta Didik Baru (PPDB) Nurul Fikri Balikpapan tahun ajaran 2026/2027 resmi dibuka mulai September 2026 untuk seluruh jenjang: Daycare, KBIT, SDIT, dan SMPIT.', 'Orang tua dapat mengisi formulir online pada halaman PPDB, kemudian mengikuti observasi (KBIT/SDIT) atau tes pemetaan (SMPIT). Tim admisi akan menghubungi maksimal 2 hari kerja setelah formulir terkirim.', 'Gelombang awal mendapatkan prioritas jadwal observasi dan potongan biaya formulir. Informasi lengkap tersedia di halaman PPDB atau melalui WhatsApp sekolah.'],
            ],
            [
                'slug' => 'siswa-sdit-juara-olimpiade-sains',
                'title' => 'Siswa SDIT Raih Juara Olimpiade Sains Tingkat Kota',
                'date' => '28 Agu 2026',
                'category' => 'Prestasi',
                'excerpt' => 'Dua siswa kelas 5 membawa pulang medali emas dan perak Olimpiade Sains tingkat Kota Balikpapan.',
                'body' => ['Kabar membanggakan datang dari tim olimpiade SDIT Nurul Fikri Balikpapan. Dua siswa kelas 5 meraih medali emas (IPA) dan perak (Matematika) pada Olimpiade Sains tingkat Kota Balikpapan.', 'Capaian ini merupakan hasil pembinaan rutin klub sains setiap pekan serta pendampingan guru pembimbing. Kedua siswa akan mewakili kota ke tingkat provinsi pada bulan berikutnya.', 'Barakallah, semoga menjadi inspirasi bagi seluruh siswa untuk berani berkompetisi dengan akhlak yang baik.'],
            ],
            [
                'slug' => 'tahfidz-camp-smpit',
                'title' => 'Tahfidz Camp SMPIT: Menguatkan Hafalan & Ukhuwah',
                'date' => '21 Agu 2026',
                'category' => 'Kegiatan',
                'excerpt' => 'Kegiatan 3 hari 2 malam untuk murajaah intensif, tasmi, dan pembinaan karakter di alam terbuka.',
                'body' => ['SMPIT Nurul Fikri Balikpapan menggelar Tahfidz Camp selama 3 hari 2 malam. Peserta mengikuti halaqah murajaah, setoran tasmi, qiyamul lail bersama, serta outbond yang melatih kerja sama.', 'Selain target hafalan, kegiatan ini menanamkan kemandirian: siswa mengatur jadwal, menjaga amanah kelompok, dan melayani teman satu tenda.', 'Orang tua menerima laporan capaian hafalan tiap peserta pada penutupan kegiatan.'],
            ],
            [
                'slug' => 'kunjungan-edukatif-kbit',
                'title' => 'KBIT Belajar di Luar Kelas: Kunjungan ke Kebun Hidroponik',
                'date' => '15 Agu 2026',
                'category' => 'Kegiatan',
                'excerpt' => 'Anak-anak KBIT belajar menanam, memanen, dan bersyukur atas rezeki Allah melalui kunjungan edukatif.',
                'body' => ['Kelompok Bermain (KBIT) mengadakan kunjungan edukatif ke kebun hidroponik. Anak-anak melihat langsung cara menanam sayur, menyiram tanaman, hingga memanen hasilnya.', 'Melalui kegiatan ini, anak belajar kosakata baru, melatih motorik, serta menanamkan rasa syukur atas ciptaan Allah. Hasil panen dibawa pulang untuk dimasak bersama keluarga.', 'Kunjungan edukatif diadakan rutin tiap tema pembelajaran.'],
            ],
            [
                'slug' => 'workshop-parenting',
                'title' => 'Workshop Parenting: Mendampingi Anak di Era Digital',
                'date' => '07 Agu 2026',
                'category' => 'Orang Tua',
                'excerpt' => 'Ratusan wali murid mengikuti workshop tentang screen time sehat dan komunikasi positif dengan anak.',
                'body' => ['Sekolah mengundang psikolog anak untuk berbagi strategi praktis: menetapkan screen time sehat, memilih tontonan edukatif, dan membangun komunikasi positif di rumah.', 'Sesi diskusi berlangsung interaktif. Banyak orang tua berbagi pengalaman mendampingi anak belajar di rumah dan menyeimbangkan gawai dengan aktivitas fisik.', 'Materi workshop dibagikan dalam bentuk ringkasan PDF kepada seluruh wali murid.'],
            ],
            [
                'slug' => 'qurban-dan-baksos',
                'title' => 'Iduladha: Belajar Berbagi Lewat Qurban & Bakti Sosial',
                'date' => '30 Mei 2026',
                'category' => 'Kegiatan',
                'excerpt' => 'Siswa terlibat langsung dari pengumpulan, penyembelihan, hingga distribusi paket qurban ke warga sekitar.',
                'body' => ['Momentum Iduladha dimanfaatkan sebagai pembelajaran karakter: siswa terlibat dalam pengumpulan hewan qurban, menyaksikan penyembelihan sesuai syariat, mengemas daging, dan mendistribusikannya ke warga sekitar sekolah.', 'Kegiatan ditutup dengan makan bersama dan refleksi tentang makna ikhlas dan berbagi. Total ratusan paket qurban tersalurkan tahun ini.', 'Jazakumullah khairan kepada seluruh orang tua dan donatur yang berpartisipasi.'],
            ],
        ];
    }

    private function gallery(): array
    {
        $items = [];
        $cats = ['Kegiatan', 'Tahfidz', 'Sains', 'Olahraga', 'Seni', 'Kunjungan'];
        for ($i = 1; $i <= 12; $i++) {
            $items[] = [
                'id' => $i,
                'title' => 'Dokumentasi kegiatan ' . $i,
                'category' => $cats[($i - 1) % count($cats)],
                'unit' => ['SDIT', 'KBIT', 'SMPIT', 'Daycare'][($i - 1) % 4],
            ];
        }
        return $items;
    }

    private function agenda(): array
    {
        return [
            ['day' => '12', 'month' => 'Sep', 'title' => 'Observasi PPDB Gelombang 1 (KBIT & SDIT)', 'time' => '08.00 – 12.00 WITA', 'place' => 'Kampus NF Balikpapan'],
            ['day' => '19', 'month' => 'Sep', 'title' => 'Tes Pemetaan SMPIT + Wawancara Orang Tua', 'time' => '07.30 – 11.30 WITA', 'place' => 'Gedung SMPIT'],
            ['day' => '26', 'month' => 'Sep', 'title' => 'Tasmi Akbar & Wisuda Tahfidz Semester Ganjil', 'time' => '08.00 – 11.00 WITA', 'place' => 'Aula Utama'],
            ['day' => '03', 'month' => 'Okt', 'title' => 'Market Day & Pameran Karya Siswa', 'time' => '08.00 – 14.00 WITA', 'place' => 'Lapangan Sekolah'],
        ];
    }

    public function home()
    {
        $units = $this->units();
        $news = array_slice($this->news(), 0, 5);
        return view('pages.home', [
            'units' => $units,
            'news' => $news,
            'gallery' => array_slice($this->gallery(), 0, 6),
            'agenda' => $this->agenda(),
            'stats' => [
                ['value' => 850, 'suffix' => '+', 'label' => 'Siswa aktif 4 jenjang'],
                ['value' => 78, 'suffix' => '', 'label' => 'Guru & pengasuh'],
                ['value' => 18, 'suffix' => '', 'label' => 'Tahun mengabdi'],
                ['value' => 120, 'suffix' => '+', 'label' => 'Prestasi siswa'],
            ],
            'testimonials' => [
                ['quote' => 'Anak saya yang pemalu jadi berani tampil. Gurunya sabar dan selalu update perkembangan lewat laporan harian.', 'name' => 'Bunda Rahma', 'role' => 'Wali murid KBIT'],
                ['quote' => 'Program tahfidznya terstruktur. Anak setoran rutin dan kami sebagai orang tua dilibatkan lewat tasmi bersama.', 'name' => 'Pak Hendra', 'role' => 'Wali murid SDIT'],
                ['quote' => 'Di SMPIT saya belajar mandiri dan ikut riset mini. Sekarang lanjut ke SMA favorit dengan beasiswa.', 'name' => 'Nadia', 'role' => 'Alumni SMPIT'],
            ],
            'heroWords' => [
                "Menumbuhkan generasi qur'ani yang cerdas dan berkarakter.",
                'Bermain, belajar, dan tumbuh dalam nilai-nilai Islami.',
                'Memadukan iman, ilmu, dan akhlak di setiap langkah.',
            ],
            'values' => [
                ['word' => 'Sholeh', 'desc' => 'Taat beribadah dan berakhlak mulia dalam keseharian.'],
                ['word' => 'Muslih', 'desc' => 'Berdampak baik dan gemar memperbaiki lingkungan.'],
                ['word' => 'Cerdas', 'desc' => 'Kritis, kreatif, dan unggul dalam ilmu pengetahuan.'],
                ['word' => 'Mandiri', 'desc' => 'Percaya diri, disiplin, dan bertanggung jawab.'],
                ['word' => 'Terampil', 'desc' => 'Cakap berkarya dan siap menghadapi masa depan.'],
            ],
        ]);
    }

    public function tentang()
    {
        return view('pages.tentang');
    }

    public function unit(string $slug)
    {
        $unit = collect($this->units())->firstWhere('slug', $slug);
        abort_if(! $unit, 404);
        return view('pages.unit', ['unit' => $unit, 'units' => $this->units()]);
    }

    public function berita()
    {
        return view('pages.berita', ['news' => $this->news()]);
    }

    public function beritaDetail(string $slug)
    {
        $item = collect($this->news())->firstWhere('slug', $slug);
        abort_if(! $item, 404);
        $others = collect($this->news())->where('slug', '!=', $slug)->take(3)->all();
        return view('pages.berita-detail', ['item' => $item, 'others' => $others]);
    }

    public function galeri()
    {
        return view('pages.galeri', ['gallery' => $this->gallery()]);
    }

    public function ppdb()
    {
        return view('pages.ppdb', ['units' => $this->units()]);
    }

    public function ppdbStatus()
    {
        return view('pages.ppdb-status');
    }

    public function kontak()
    {
        return view('pages.kontak', ['agenda' => $this->agenda()]);
    }
}
