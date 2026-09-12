<?php

namespace Database\Seeders;

use App\Models\Agenda;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\PpdbCommitment;
use App\Models\PpdbDocument;
use App\Models\PpdbFormField;
use App\Models\PpdbPeriod;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'dashboard.view',
            'users.manage',
            'ppdb.periods', 'ppdb.fields', 'ppdb.registrations', 'ppdb.export', 'ppdb.documents',
            'news.manage', 'gallery.manage', 'agenda.manage', 'testimonial.manage',
            'messages.manage', 'settings.manage',
        ];
        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name]);
        }

        $roles = [
            'super-admin' => $permissions,
            'admin-ppdb' => ['dashboard.view', 'ppdb.periods', 'ppdb.fields', 'ppdb.registrations', 'ppdb.export', 'ppdb.documents', 'messages.manage'],
            'editor' => ['dashboard.view', 'news.manage', 'gallery.manage', 'agenda.manage', 'testimonial.manage'],
            'kepala-sekolah' => ['dashboard.view'],
            'orang-tua' => [],
        ];
        foreach ($roles as $name => $perms) {
            $role = Role::firstOrCreate(['name' => $name]);
            $role->syncPermissions($perms);
        }

        $users = [
            ['Administrator', 'admin@nfbpn.id', 'super-admin'],
            ['Staf TU', 'tu@nfbpn.id', 'admin-ppdb'],
            ['Humas Sekolah', 'humas@nfbpn.id', 'editor'],
            ['Kepala Sekolah', 'kepsek@nfbpn.id', 'kepala-sekolah'],
            ['Contoh Ortu', 'ortu@nfbpn.id', 'orang-tua'],
        ];
        foreach ($users as [$name, $email, $role]) {
            $user = User::firstOrCreate(['email' => $email], [
                'name' => $name,
                'password' => 'password123',
            ]);
            $user->assignRole($role);
        }

        $categories = [];
        foreach (['PPDB', 'Prestasi', 'Kegiatan', 'Orang Tua'] as $name) {
            $categories[$name] = NewsCategory::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }

        $editor = User::where('email', 'humas@nfbpn.id')->first();
        $samples = [
            ['PPDB Tahun Ajaran 2026/2027 Resmi Dibuka', 'PPDB', 'Pendaftaran siswa baru Daycare, KBIT, SDIT, dan SMPIT telah dibuka. Kuota tiap jenjang terbatas.'],
            ['Siswa SDIT Raih Juara Olimpiade Sains Tingkat Kota', 'Prestasi', 'Dua siswa kelas 5 membawa pulang medali emas dan perak Olimpiade Sains tingkat Kota Balikpapan.'],
            ['Tahfidz Camp SMPIT: Menguatkan Hafalan dan Ukhuwah', 'Kegiatan', 'Kegiatan 3 hari 2 malam untuk murajaah intensif, tasmi, dan pembinaan karakter di alam terbuka.'],
        ];
        foreach ($samples as [$title, $cat, $excerpt]) {
            News::firstOrCreate(['slug' => Str::slug($title)], [
                'category_id' => $categories[$cat]->id,
                'user_id' => $editor?->id,
                'title' => $title,
                'excerpt' => $excerpt,
                'body' => '<p>'.$excerpt.'</p><p>Berita lengkap dapat dilengkapi oleh tim Humas melalui panel admin.</p>',
                'published_at' => now(),
            ]);
        }

        $agendas = [
            ['Observasi PPDB Gelombang 1 (KBIT dan SDIT)', '2026-09-12', '08.00 - 12.00 WITA', 'Kampus NF Balikpapan'],
            ['Tes Pemetaan SMPIT + Wawancara Orang Tua', '2026-09-19', '07.30 - 11.30 WITA', 'Gedung SMPIT'],
            ['Tasmi Akbar dan Wisuda Tahfidz Semester Ganjil', '2026-09-26', '08.00 - 11.00 WITA', 'Aula Utama'],
            ['Market Day dan Pameran Karya Siswa', '2026-10-03', '08.00 - 14.00 WITA', 'Lapangan Sekolah'],
        ];
        foreach ($agendas as [$title, $date, $time, $place]) {
            Agenda::firstOrCreate(['title' => $title], [
                'date' => $date, 'time_label' => $time, 'place' => $place, 'is_published' => true,
            ]);
        }

        $testis = [
            ['Anak saya yang pemalu jadi berani tampil. Gurunya sabar dan selalu update perkembangan lewat laporan harian.', 'Bunda Rahma', 'Wali murid KBIT'],
            ['Program tahfidznya terstruktur. Anak setoran rutin dan kami sebagai orang tua dilibatkan lewat tasmi bersama.', 'Pak Hendra', 'Wali murid SDIT'],
            ['Di SMPIT saya belajar mandiri dan ikut riset mini. Sekarang lanjut ke SMA favorit dengan beasiswa.', 'Nadia', 'Alumni SMPIT'],
        ];
        foreach ($testis as $i => [$quote, $name, $role]) {
            Testimonial::firstOrCreate(['quote' => $quote], [
                'name' => $name, 'role' => $role, 'sort_order' => $i, 'is_published' => true,
            ]);
        }

        $jenjangs = ['daycare' => 'Daycare', 'kbit' => 'KBIT', 'sdit' => 'SDIT', 'smpit' => 'SMPIT'];
        foreach ($jenjangs as $slug => $label) {
            PpdbPeriod::firstOrCreate(
                ['jenjang' => $slug, 'name' => 'Gelombang 1'],
                ['starts_on' => '2026-09-01', 'ends_on' => '2026-11-30', 'quota' => 60, 'is_active' => true]
            );

            $fields = [
                ['asal_sekolah', 'Asal sekolah', null, 'text', null, false, 1, null, null, null],
                ['hobi', 'Hobi anak', null, 'text', null, false, 2, null, null, null],
                ['transportasi', 'Transportasi ke sekolah', 'Transportasi', 'select', ['Antar jemput keluarga', 'Kendaraan umum', 'Lainnya'], true, 3, null, null, null],
                ['transport_lain', 'Sebutkan transportasinya', 'Transportasi', 'text', null, true, 4, 'transportasi', 'equals', 'Lainnya'],
                ['kebutuhan_khusus', 'Anak berkebutuhan khusus?', 'Kesehatan', 'radio', ['Ya', 'Tidak'], true, 5, null, null, null],
                ['kebutuhan_detail', 'Jelaskan kebutuhannya', 'Kesehatan', 'textarea', null, true, 6, 'kebutuhan_khusus', 'equals', 'Ya'],
                ['keterangan', 'Keterangan tambahan', null, 'textarea', null, false, 7, null, null, null],
            ];
            foreach ($fields as [$key, $label2, $section, $type, $options, $required, $sort, $cf, $cop, $cval]) {
                PpdbFormField::updateOrCreate(['jenjang' => $slug, 'key' => $key], [
                    'label' => $label2, 'section' => $section, 'type' => $type, 'options' => $options,
                    'is_required' => $required, 'sort_order' => $sort, 'is_core' => false,
                    'visible_if_field' => $cf, 'visible_if_operator' => $cop, 'visible_if_value' => $cval,
                ]);
            }
        }

        $settings = [
            'kontak' => [
                'alamat' => 'Jl. Pendidikan No. 1, Balikpapan Selatan, Kalimantan Timur',
                'telepon' => '(0542) 123-456',
                'email' => 'info@nurulfikri-balikpapan.sch.id',
                'whatsapp' => '62542123456',
                'jam' => 'Senin-Jumat, 07.00-16.00 WITA',
            ],
            'medsos' => [
                'instagram' => '#',
                'facebook' => '#',
                'youtube' => '#',
            ],
        ];
        foreach ($settings as $group => $pairs) {
            foreach ($pairs as $key => $value) {
                SiteSetting::firstOrCreate(['key' => $key], ['value' => $value, 'group' => $group]);
            }
        }

        $baseDocs = [
            ['Kartu Keluarga (KK)', 'Pindaian KK terbaru.', true],
            ['Akta Kelahiran', 'Pindaian akta kelahiran anak.', true],
            ['Pas foto anak', 'Foto formal terbaru, latar bebas.', true],
        ];
        $extraDocs = [
            'sdit' => [['Rapor semester terakhir', 'Pindaian rapor TK/asal sekolah.', true]],
            'smpit' => [['Rapor semester terakhir', 'Pindaian rapor SD/MI.', true]],
        ];
        foreach (['daycare', 'kbit', 'sdit', 'smpit'] as $i => $slug) {
            $docs = array_merge($baseDocs, $extraDocs[$slug] ?? []);
            foreach ($docs as $n => [$label, $deskripsi, $wajib]) {
                PpdbDocument::firstOrCreate(['jenjang' => $slug, 'label' => $label], [
                    'deskripsi' => $deskripsi, 'wajib' => $wajib, 'urut' => $n,
                    'allowed' => ['pdf', 'jpg', 'png', 'webp'], 'max_kb' => 2048, 'compress' => true,
                ]);
            }
            PpdbCommitment::firstOrCreate(['jenjang' => $slug], [
                'teks' => "Dengan ini saya sebagai orang tua/wali calon siswa menyatakan:\n1. Data yang saya isi adalah benar.\n2. Bersedia mengikuti seluruh tata tertib dan program pembinaan sekolah.\n3. Berkomitmen mendampingi proses belajar anak di rumah.\n\nBalikpapan, ____________\n( Nama terang dan tanda tangan )",
            ]);
        }
    }
}
