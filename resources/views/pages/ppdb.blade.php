@extends('layouts.site')
@section('title', 'PPDB Online 2026/2027')

@section('content')
<section class="relative overflow-hidden bg-nf-ink text-white">
    <div class="absolute inset-0 islamic-pattern opacity-40"></div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 py-14 md:py-20 grid lg:grid-cols-2 gap-10 items-center">
        <div>
            <p class="text-xs font-bold tracking-[0.25em] uppercase text-nf-yellow">PPDB 2026/2027</p>
            <h1 class="mt-3 font-heading font-extrabold text-3xl md:text-5xl leading-tight">Pendaftaran siswa baru, cukup 10 menit dari rumah.</h1>
            <p class="mt-4 text-white/70">Buat akun atau masuk, isi formulir online, dan tim admisi menghubungi maksimal 2 hari kerja untuk jadwal observasi/tes pemetaan.</p>
            <div class="mt-6 grid grid-cols-3 gap-3 text-center">
                @foreach([['1','Isi Formulir'],['2','Observasi / Tes'],['3','Daftar Ulang']] as [$n,$t])
                <div class="rounded-2xl bg-white/8 border border-white/15 p-4">
                    <p class="font-heading font-extrabold text-2xl text-nf-yellow">{{ $n }}</p>
                    <p class="text-xs font-bold text-white/75 mt-1">{{ $t }}</p>
                </div>
                @endforeach
            </div>
        </div>
        <div class="rounded-3xl bg-white text-nf-ink p-7">
            <h2 class="font-heading font-extrabold text-xl">Periode yang sedang dibuka</h2>
            @if(count($periods))
            <ul class="mt-4 space-y-3 text-sm">
                @foreach($periods as $p)
                <li class="flex gap-3 rounded-2xl bg-nf-cream border border-nf-blue/20 p-4">
                    <span class="shrink-0 font-heading font-extrabold text-nf-blue-dark">{{ strtoupper($p->jenjang) }}</span>
                    <span class="text-nf-ink/70">{{ $p->name }} · {{ $p->starts_on->format('d M Y') }} s.d. {{ $p->ends_on->format('d M Y') }}{{ $p->quota ? ' · kuota '.$p->quota : '' }}</span>
                </li>
                @endforeach
            </ul>
            @else
            <p class="mt-4 text-sm text-nf-ink/60 rounded-2xl bg-nf-cream border border-nf-blue/20 p-4">Belum ada periode pendaftaran yang dibuka. Silakan hubungi kami via WhatsApp untuk info gelombang berikutnya.</p>
            @endif
            <div class="mt-4 rounded-2xl bg-nf-cream border border-nf-blue/20 p-4 text-sm text-nf-ink/70">
                Syarat umum: fotokopi KK dan akta lahir, pas foto anak, rapor terakhir (SDIT/SMPIT). Berkas asli dibawa saat daftar ulang.
            </div>
            <a href="#formulir" class="mt-5 block text-center bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-extrabold px-5 py-3.5 rounded-full transition">Isi Formulir di Bawah</a>
        </div>
    </div>
</section>

<section id="formulir" class="mx-auto max-w-4xl px-4 sm:px-6 py-12">
    @guest
    <div class="rounded-3xl border border-nf-blue/25 bg-white shadow-xl p-10 text-center">
        <h2 class="font-heading font-extrabold text-2xl">Masuk untuk mengisi formulir.</h2>
        <p class="mt-2 text-nf-ink/60">Buat akun orang tua gratis agar status pendaftaran terpantau di portal.</p>
        <div class="mt-6 flex flex-wrap justify-center gap-3">
            <a href="{{ route('login') }}" class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-extrabold px-7 py-3 rounded-full transition">Masuk</a>
            <a href="{{ route('register') }}" class="border border-nf-blue/30 hover:bg-nf-blue-soft font-heading font-bold px-7 py-3 rounded-full transition">Buat Akun</a>
        </div>
    </div>
    @else
    <div class="rounded-3xl border border-nf-blue/25 bg-white shadow-xl p-7 md:p-10">
        <h2 class="font-heading font-extrabold text-2xl">Formulir Pendaftaran</h2>
        <p class="mt-1 text-sm text-nf-ink/55">Data tersimpan aman dan hanya terlihat oleh tim admisi.</p>
        @if(session('error'))<p class="mt-4 text-sm font-bold text-red-700 bg-red-50 rounded-xl px-4 py-3">{{ session('error') }}</p>@endif
        <form method="POST" action="{{ route('ppdb.store') }}" enctype="multipart/form-data" class="mt-6 grid gap-6" x-data="ppdbForm(@js($conditionsByJenjang), @js(old('answers', [])))">
            @csrf
            <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">
            <div>
                <h3 class="font-heading font-bold text-nf-blue-dark">A. Periode dan Jenjang</h3>
                <div class="mt-3 grid sm:grid-cols-2 gap-4">
                    <label class="grid gap-1.5 text-sm font-bold">Periode
                        <select name="period_id" x-model="periodId" x-on:change="jenjang = $event.target.selectedOptions[0].dataset.jenjang || 'sdit'" required class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
                            <option value="">Pilih periode</option>
                            @foreach($periods as $p)
                            <option value="{{ $p->id }}" data-jenjang="{{ $p->jenjang }}" @selected(old('period_id') == $p->id)>{{ $p->name }} ({{ strtoupper($p->jenjang) }})</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="grid gap-1.5 text-sm font-bold">Jenjang (otomatis dari periode)
                        <select name="jenjang_display" disabled class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 bg-nf-cream" x-text="jenjang.toUpperCase()"></select>
                    </label>
                </div>
            </div>
            <div>
                <h3 class="font-heading font-bold text-nf-blue-dark">B. Data Calon Siswa</h3>
                <div class="mt-3 grid sm:grid-cols-2 gap-4">
                    <label class="grid gap-1.5 text-sm font-bold">Nama lengkap<input name="child_name" required value="{{ old('child_name') }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue" placeholder="Nama anak"></label>
                    <label class="grid gap-1.5 text-sm font-bold">Tanggal lahir<input name="child_birthdate" required type="date" value="{{ old('child_birthdate') }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue"></label>
                    <label class="grid gap-1.5 text-sm font-bold">Jenis kelamin
                        <select name="gender" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5"><option value="">Pilih</option><option @selected(old('gender')==='Laki-laki')>Laki-laki</option><option @selected(old('gender')==='Perempuan')>Perempuan</option></select>
                    </label>
                </div>
            </div>
            <div>
                <h3 class="font-heading font-bold text-nf-blue-dark">C. Data Orang Tua</h3>
                <div class="mt-3 grid sm:grid-cols-2 gap-4">
                    <label class="grid gap-1.5 text-sm font-bold">Nama ayah/ibu<input name="parent_name" required value="{{ old('parent_name', auth()->user()->name) }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue"></label>
                    <label class="grid gap-1.5 text-sm font-bold">No. WhatsApp<input name="whatsapp" required value="{{ old('whatsapp') }}" placeholder="08xx-xxxx-xxxx" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue"></label>
                </div>
            </div>
            @foreach($fieldsByJenjang as $j => $fields)
            <template x-if="jenjang === '{{ $j }}'">
                <div data-jenjang-section="{{ $j }}">
                    <h3 class="font-heading font-bold text-nf-blue-dark">D. Informasi Tambahan ({{ strtoupper($j) }})</h3>
                    @php $grouped = $fields->groupBy(fn ($f) => $f->section ?: ''); @endphp
                    @foreach($grouped as $sectionName => $group)
                    @if($sectionName !== '')<h4 class="mt-5 mb-1 font-heading font-bold text-sm uppercase tracking-widest text-nf-blue-dark/70">{{ $sectionName }}</h4>@endif
                    <div class="mt-3 grid sm:grid-cols-2 gap-4">
                        @foreach($group as $f)
                        @php $cond = $f->hasCondition(); @endphp
                        <div @if($cond) x-show="isVisible('{{ $j }}', '{{ $f->key }}')" x-transition.opacity.duration.200ms @endif>
                        <label class="grid gap-1.5 text-sm font-bold {{ in_array($f->type, ['textarea']) ? 'sm:col-span-2' : '' }}">{{ $f->label }} @if($f->is_required)<span class="text-red-600">*</span>@endif
                            @if($f->type === 'textarea')
                            <textarea name="answers[{{ $f->key }}]" rows="3" x-on:input="sync('{{ $f->key }}', $event.target)" @if($f->is_required && !$cond) required @elseif($f->is_required) :required="isVisible('{{ $j }}', '{{ $f->key }}')" @endif @if($cond) :disabled="!isVisible('{{ $j }}', '{{ $f->key }}')" @endif class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 disabled:opacity-50">{{ old('answers.'.$f->key) }}</textarea>
                            @elseif($f->type === 'select')
                            <select name="answers[{{ $f->key }}]" x-on:change="sync('{{ $f->key }}', $event.target)" @if($f->is_required && !$cond) required @elseif($f->is_required) :required="isVisible('{{ $j }}', '{{ $f->key }}')" @endif @if($cond) :disabled="!isVisible('{{ $j }}', '{{ $f->key }}')" @endif class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 disabled:opacity-50">
                                <option value="">Pilih</option>
                                @foreach($f->options ?? [] as $o)<option @selected(old('answers.'.$f->key) === $o)>{{ $o }}</option>@endforeach
                            </select>
                            @elseif($f->type === 'radio')
                            <span class="flex flex-wrap gap-3 font-normal">
                                @foreach($f->options ?? [] as $o)<label class="flex items-center gap-1.5"><input type="radio" name="answers[{{ $f->key }}]" value="{{ $o }}" @checked(old('answers.'.$f->key) === $o) x-on:change="sync('{{ $f->key }}', $event.target)" @if($cond) :disabled="!isVisible('{{ $j }}', '{{ $f->key }}')" @endif class="text-nf-blue disabled:opacity-50"> {{ $o }}</label>@endforeach
                            </span>
                            @elseif($f->type === 'checkbox')
                            <span class="flex flex-wrap gap-3 font-normal">
                                @foreach($f->options ?? [] as $o)<label class="flex items-center gap-1.5"><input type="checkbox" name="answers[{{ $f->key }}][]" value="{{ $o }}" @checked(in_array($o, old('answers.'.$f->key, []))) x-on:change="sync('{{ $f->key }}', $event.target)" @if($cond) :disabled="!isVisible('{{ $j }}', '{{ $f->key }}')" @endif class="rounded text-nf-blue disabled:opacity-50"> {{ $o }}</label>@endforeach
                            </span>
                            @elseif($f->type === 'file')
                            <input type="file" name="answers[{{ $f->key }}]" @if($f->is_required && !$cond) required @elseif($f->is_required) :required="isVisible('{{ $j }}', '{{ $f->key }}')" @endif @if($cond) :disabled="!isVisible('{{ $j }}', '{{ $f->key }}')" @endif accept=".pdf,.jpg,.jpeg,.png,.webp" class="font-normal text-sm file:mr-3 file:rounded-full file:border-0 file:bg-nf-blue-soft file:text-nf-blue-dark file:font-bold file:px-4 file:py-2 disabled:opacity-50">
                            @elseif($f->type === 'number')
                            <input type="number" name="answers[{{ $f->key }}]" value="{{ old('answers.'.$f->key) }}" x-on:input="sync('{{ $f->key }}', $event.target)" @if($f->is_required && !$cond) required @elseif($f->is_required) :required="isVisible('{{ $j }}', '{{ $f->key }}')" @endif @if($cond) :disabled="!isVisible('{{ $j }}', '{{ $f->key }}')" @endif class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 disabled:opacity-50">
                            @elseif($f->type === 'date')
                            <input type="date" name="answers[{{ $f->key }}]" value="{{ old('answers.'.$f->key) }}" x-on:change="sync('{{ $f->key }}', $event.target)" @if($f->is_required && !$cond) required @elseif($f->is_required) :required="isVisible('{{ $j }}', '{{ $f->key }}')" @endif @if($cond) :disabled="!isVisible('{{ $j }}', '{{ $f->key }}')" @endif class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 disabled:opacity-50">
                            @else
                            <input type="text" name="answers[{{ $f->key }}]" value="{{ old('answers.'.$f->key) }}" x-on:input="sync('{{ $f->key }}', $event.target)" @if($f->is_required && !$cond) required @elseif($f->is_required) :required="isVisible('{{ $j }}', '{{ $f->key }}')" @endif @if($cond) :disabled="!isVisible('{{ $j }}', '{{ $f->key }}')" @endif class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 disabled:opacity-50">
                            @endif
                        </label>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </template>
            @endforeach
            <button class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-extrabold px-6 py-3.5 rounded-full transition shadow-lg shadow-nf-blue/30">Kirim Pendaftaran</button>
        </form>
    </div>
    @endguest
</section>

<script>
function ppdbForm(conditions, initialAnswers) {
    const asArr = v => (Array.isArray(v) ? v.map(String) : (v === undefined || v === null || v === '' ? [] : [String(v)]));
    const first = v => (Array.isArray(v) ? (v[0] ?? '') : (v ?? ''));
    return {
        jenjang: 'sdit',
        periodId: '',
        answers: initialAnswers || {},
        init() {
            const sel = this.$el.querySelector('select[name="period_id"]');
            if (sel && sel.selectedOptions[0]) {
                this.periodId = sel.value;
                this.jenjang = sel.selectedOptions[0].dataset.jenjang || 'sdit';
            }
        },
        isVisible(j, key) {
            const c = (conditions[j] || {})[key];
            if (!c) return true;
            const actual = this.answers[c.trigger];
            const exp = c.value;
            switch (c.op) {
                case 'equals':
                    if (Array.isArray(actual)) return actual.map(String).includes(String(first(exp)));
                    return String(actual ?? '') === String(first(exp));
                case 'not_equals':
                    if (Array.isArray(actual)) return !actual.map(String).includes(String(first(exp)));
                    return String(actual ?? '') !== String(first(exp));
                case 'in':
                    return asArr(actual).some(v => asArr(exp).includes(v));
                case 'not_in':
                    return !asArr(actual).some(v => asArr(exp).includes(v));
                case 'filled':
                    return actual !== undefined && actual !== null && String(actual).trim() !== '' && !(Array.isArray(actual) && actual.length === 0);
                case 'empty': {
                    const filled = actual !== undefined && actual !== null && String(actual).trim() !== '' && !(Array.isArray(actual) && actual.length === 0);
                    return !filled;
                }
                default:
                    return true;
            }
        },
        sync(key, el) {
            const scope = el.closest('[data-jenjang-section]') || document;
            if (el.type === 'checkbox') {
                this.answers[key] = [...scope.querySelectorAll('input[name="answers[' + key + '][]"]:checked')].map(b => b.value);
            } else if (el.type === 'radio') {
                const sel = scope.querySelector('input[name="answers[' + key + ']"]:checked');
                this.answers[key] = sel ? sel.value : '';
            } else {
                this.answers[key] = el.value;
            }
        }
    };
}
</script>
@endsection
