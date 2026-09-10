<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\ContactMessage;
use App\Models\Gallery;
use App\Models\News;
use App\Models\PpdbFormField;
use App\Models\PpdbPeriod;
use App\Models\PpdbRegistration;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class PageController extends Controller
{
    private function units(): array
    {
        return [
            [
                'slug' => 'daycare',
                'name' => 'Daycare',
                'full' => 'Daycare Nurul Fikri',
                'tagline' => 'Rumah kedua yang aman dan penuh kasih untuk si kecil.',
                'ages' => 'Usia 6 bulan – 2 tahun',
                'hours' => 'Senin–Jumat, 07.00–17.00 WITA',
                'ratio' => '1 : 4',
                'color' => 'green',
                'initial' => 'DC',
                'desc' => 'Layanan penitipan anak dengan pengasuh terlatih, rutinitas harian yang menenangkan, stimulasi motorik dan bahasa, serta pembiasaan doa dan adab sejak dini.',
                'features' => ['Pengasuh tersertifikasi dan rasio kecil', 'Ruang tidur, bermain dan makan terpisah', 'Laporan harian via grup orang tua', 'Stimulasi sensori-motor dan bahasa', 'Pembiasaan doa makan, tidur dan adab', 'Makanan sehat dari dapur sekolah'],
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
                'features' => ['Play-based learning dan sentra bermain', 'Tahfidz dan doa harian yang menyenangkan', 'Motorik kasar-halus dan pra-calistung', 'Kunjungan edukatif dan cooking class', 'Asesmen perkembangan per semester', 'Transisi mulus menuju SDIT'],
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
                'features' => ['Tahfidz bertahap dan tasmi berkala', 'Sains dan matematika project-based', 'Bahasa Inggris dan Arab komunikatif', 'Literasi dan klub robotik/koding', 'Shalat berjamaah dan mentoring akhlak', 'Ekstrakurikuler: futsal, panahan, pramuka'],
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
                'desc' => 'Jenjang menengah yang menyiapkan kemandirian belajar, kepemimpinan, dan kesiapan SMA favorit: pendalaman tahfidz, riset mini, olimpiade sains, dan program leadership dan pengabdian masyarakat.',
                'features' => ['Tahfidz lanjutan dan tahsin intensif', 'Kelas olimpiade MIPA dan bahasa', 'Riset mini dan science project', 'Leadership, LDKS dan OSIS', 'Bimbingan studi lanjut dan psikolog', 'Ekstrakurikuler: basket, karya ilmiah, media'],
            ],
        ];
    }

    private function agendaItems(int $limit = 4): array
    {
        return Agenda::upcoming()->take($limit)->get()->map(fn (Agenda $a) => [
            'day' => $a->date->format('d'),
            'month' => $a->date->format('M'),
            'title' => $a->title,
            'time' => $a->time_label ?: '-',
            'place' => $a->place ?: '-',
        ])->all();
    }

    private function newsItems($collection): array
    {
        return $collection->map(fn (News $n) => [
            'slug' => $n->slug,
            'title' => $n->title,
            'date' => $n->published_at?->format('d M Y') ?? $n->created_at->format('d M Y'),
            'category' => $n->category?->name ?? 'Berita',
            'excerpt' => $n->excerpt ?: Str::limit(strip_tags($n->body), 140),
        ])->all();
    }

    public function home()
    {
        $units = $this->units();
        $news = $this->newsItems(News::with('category')->published()->latest('published_at')->take(5)->get());
        $testimonials = Testimonial::published()->take(3)->get()
            ->map(fn ($t) => ['quote' => $t->quote, 'name' => $t->name, 'role' => $t->role])->all();

        return view('pages.home', [
            'units' => $units,
            'news' => $news,
            'agenda' => $this->agendaItems(4),
            'stats' => [
                ['value' => 850, 'suffix' => '+', 'label' => 'Siswa aktif 4 jenjang'],
                ['value' => 78, 'suffix' => '', 'label' => 'Guru dan pengasuh'],
                ['value' => 18, 'suffix' => '', 'label' => 'Tahun mengabdi'],
                ['value' => 120, 'suffix' => '+', 'label' => 'Prestasi siswa'],
            ],
            'testimonials' => $testimonials,
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
        $news = $this->newsItems(News::with('category')->published()->latest('published_at')->get());

        return view('pages.berita', ['news' => $news]);
    }

    public function beritaDetail(string $slug)
    {
        $model = News::with('category')->where('slug', $slug)->published()->firstOrFail();
        $item = [
            'title' => $model->title,
            'date' => $model->published_at->format('d M Y'),
            'category' => $model->category?->name ?? 'Berita',
            'excerpt' => $model->excerpt ?: Str::limit(strip_tags($model->body), 160),
            'body_html' => $model->body,
            'cover' => $model->cover_path ? asset('storage/'.$model->cover_path) : null,
        ];
        $others = $this->newsItems(
            News::with('category')->published()->where('id', '!=', $model->id)->latest('published_at')->take(3)->get()
        );

        return view('pages.berita-detail', compact('item', 'others'));
    }

    public function galeri()
    {
        $gallery = Gallery::latest()->take(24)->get()->map(fn (Gallery $g) => [
            'id' => $g->id,
            'title' => $g->title,
            'category' => $g->category,
            'unit' => $g->unit ?: 'Umum',
            'src' => $g->url(),
        ])->all();

        return view('pages.galeri', ['gallery' => $gallery]);
    }

    public function ppdb()
    {
        $periods = PpdbPeriod::active()->orderBy('starts_on')->get()
            ->filter(fn ($p) => $p->isOpen());

        $fieldsByJenjang = [];
        $conditionsByJenjang = [];
        foreach (['daycare', 'kbit', 'sdit', 'smpit'] as $j) {
            $fields = PpdbFormField::forJenjang($j)->active()->ordered()->get();
            $fieldsByJenjang[$j] = $fields;
            foreach ($fields as $f) {
                if ($f->hasCondition()) {
                    $conditionsByJenjang[$j][$f->key] = [
                        'trigger' => $f->visible_if_field,
                        'op' => $f->visible_if_operator,
                        'value' => $f->visible_if_value,
                    ];
                }
            }
        }

        $periodsByJenjang = [];
        foreach ($periods as $p) {
            $periodsByJenjang[$p->jenjang][] = ['id' => $p->id, 'name' => $p->name];
        }

        return view('pages.ppdb', [
            'units' => $this->units(),
            'periods' => $periods,
            'periodsByJenjang' => $periodsByJenjang,
            'fieldsByJenjang' => $fieldsByJenjang,
            'conditionsByJenjang' => $conditionsByJenjang,
        ]);
    }

    public function ppdbStore(Request $request)
    {
        if ($request->filled('website')) {
            abort(403);
        }

        $period = PpdbPeriod::findOrFail($request->input('period_id'));
        abort_unless($period->isOpen(), 403, 'Periode pendaftaran tidak aktif.');
        $jenjang = $period->jenjang;

        $user = auth()->user();

        $rules = [
            'period_id' => 'required|exists:ppdb_periods,id',
            'child_name' => 'required|string|max:255',
            'child_birthdate' => 'required|date|before:today',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
        ];
        if ($user) {
            $rules['parent_name'] = 'required|string|max:255';
            $rules['whatsapp'] = 'required|string|max:20';
        } else {
            $rules['parent_name'] = 'required|string|max:255';
            $rules['email'] = 'required|email|max:255|unique:users,email';
            $rules['whatsapp'] = 'required|string|max:20';
            $rules['password'] = ['required', 'string', \App\Support\Passwords::rule(), 'confirmed'];
        }
        $fields = PpdbFormField::forJenjang($jenjang)->active()->ordered()->get();
        $submitted = $request->input('answers', []);
        $visible = $fields->filter(fn ($f) => $f->isVisibleFor($submitted))->values();
        foreach ($visible as $f) {
            $key = "answers.{$f->key}";
            $base = match ($f->type) {
                'number' => 'numeric',
                'date' => 'date',
                'file' => 'file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
                'checkbox' => 'array',
                default => 'string|max:2000',
            };
            $rules[$key] = ($f->is_required ? 'required' : 'nullable').'|'.$base;
            if (in_array($f->type, ['select', 'radio'], true) && $f->options) {
                $rules[$key] .= '|in:'.implode(',', $f->options);
            }
            if ($f->type === 'checkbox' && $f->options) {
                $rules[$key.'.'] = 'in:'.implode(',', $f->options);
            }
        }

        $data = $request->validate($rules, [
            'email.unique' => 'Email ini sudah terdaftar. Silakan masuk dulu, lalu isi formulir.',
        ]);

        if (! $user) {
            $user = User::create([
                'name' => $data['parent_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            if (Role::where('name', 'orang-tua')->exists()) {
                $user->assignRole('orang-tua');
            }
            Auth::login($user);
        }

        $exists = PpdbRegistration::where('user_id', $user->id)
            ->where('period_id', $period->id)->exists();
        if ($exists) {
            return back()->with('error', 'Anda sudah terdaftar pada periode ini. Pantau statusnya di portal orang tua.')->withInput();
        }

        $answers = $data['answers'] ?? [];
        foreach ($visible as $f) {
            if ($f->type === 'file' && $request->hasFile("answers.{$f->key}")) {
                $answers[$f->key] = $request->file("answers.{$f->key}")->store('ppdb/berkas', 'public');
            }
        }

        do {
            $no = 'NF-'.now()->format('Y').'-'.str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (PpdbRegistration::where('registration_no', $no)->exists());

        $reg = PpdbRegistration::create([
            'registration_no' => $no,
            'period_id' => $period->id,
            'user_id' => $user->id,
            'jenjang' => $jenjang,
            'child_name' => \App\Support\Sanitize::name($data['child_name']),
            'child_birthdate' => $data['child_birthdate'],
            'gender' => $data['gender'] ?? null,
            'parent_name' => \App\Support\Sanitize::name($data['parent_name']),
            'whatsapp' => \App\Support\Sanitize::phone($data['whatsapp']),
            'answers' => $answers,
            'status' => 'terkirim',
        ]);

        if (! $user->hasRole('orang-tua') && Role::where('name', 'orang-tua')->exists()) {
            $user->assignRole('orang-tua');
        }

        return redirect()->route('portal.show', $reg)
            ->with('success', 'Pendaftaran terkirim. Nomor registrasi Anda: '.$no);
    }

    public function ppdbStatus(Request $request)
    {
        $result = null;
        if ($request->filled(['no', 'tgl'])) {
            $result = PpdbRegistration::with('histories')
                ->where('registration_no', $request->input('no'))
                ->whereDate('child_birthdate', $request->input('tgl'))
                ->first();
        }

        return view('pages.ppdb-status', ['result' => $result]);
    }

    public function kontak()
    {
        return view('pages.kontak', ['agenda' => $this->agendaItems(4)]);
    }

    public function kontakStore(Request $request)
    {
        if ($request->filled('website')) {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        ContactMessage::create([
            'name' => \App\Support\Sanitize::name($data['name']),
            'whatsapp' => \App\Support\Sanitize::phone($data['whatsapp'] ?? null),
            'subject' => \App\Support\Sanitize::name($data['subject'] ?? null),
            'message' => \App\Support\Sanitize::text($data['message']),
        ]);

        return back()->with('success', 'Pesan terkirim. Tim kami akan menghubungi Anda di jam operasional.');
    }
}
