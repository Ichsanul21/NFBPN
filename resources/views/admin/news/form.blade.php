@extends('layouts.admin')
@section('title', ($item->exists ? 'Ubah' : 'Tulis').' Berita')
@section('page-title', ($item->exists ? 'Ubah' : 'Tulis').' Berita')

@section('content')
<form method="POST" action="{{ $item->exists ? route('admin.news.update', $item) : route('admin.news.store') }}" enctype="multipart/form-data" class="rounded-3xl bg-white border border-nf-blue/15 p-6 grid gap-5 max-w-4xl">
    @csrf
    @if($item->exists) @method('PUT') @endif

    <label class="grid gap-1.5 text-sm font-bold">Judul
        <input name="title" required value="{{ old('title', $item->title) }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue" placeholder="Judul berita">
    </label>
    <div class="grid sm:grid-cols-2 gap-5">
        <label class="grid gap-1.5 text-sm font-bold">Slug (opsional, otomatis)
            <input name="slug" value="{{ old('slug', $item->slug) }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue" placeholder="otomatis-dari-judul">
        </label>
        <label class="grid gap-1.5 text-sm font-bold">Kategori
            <select name="category_id" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
                <option value="">Tanpa kategori</option>
                @foreach($categories as $c)
                <option value="{{ $c->id }}" @selected(old('category_id', $item->category_id) == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </label>
    </div>
    <label class="grid gap-1.5 text-sm font-bold">Ringkasan
        <textarea name="excerpt" rows="2" maxlength="500" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-nf-blue" placeholder="1-2 kalimat pembuka">{{ old('excerpt', $item->excerpt) }}</textarea>
    </label>
    <div class="grid gap-1.5 text-sm font-bold">Isi berita
        <textarea id="editor" name="body" rows="10">{{ old('body', $item->body) }}</textarea>
    </div>
    <div class="grid sm:grid-cols-2 gap-5">
        <label class="grid gap-1.5 text-sm font-bold">Sampul (JPG/PNG/WebP, maks 5MB)
            <input type="file" name="cover" accept="image/*" class="font-normal text-sm file:mr-3 file:rounded-full file:border-0 file:bg-nf-blue-soft file:text-nf-blue-dark file:font-bold file:px-4 file:py-2">
            @if($item->cover_path)<span class="text-xs font-normal text-nf-ink/55">Sudah ada sampul. Unggah baru untuk mengganti.</span>@endif
        </label>
        <label class="grid gap-1.5 text-sm font-bold">Tanggal terbit (kosongkan = draf)
            <input type="datetime-local" name="published_at" value="{{ old('published_at', $item->published_at?->format('Y-m-d\TH:i')) }}" class="font-normal rounded-xl border border-nf-blue/25 px-4 py-2.5">
        </label>
    </div>
    <div class="flex gap-3">
        <button class="bg-nf-blue hover:bg-nf-blue-dark text-white font-heading font-bold text-sm px-6 py-3 rounded-full transition">Simpan</button>
        <a href="{{ route('admin.news.index') }}" class="font-heading font-bold text-sm px-6 py-3 rounded-full border border-nf-blue/25 hover:bg-nf-blue-soft transition">Batal</a>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
<script>
class NFUploadAdapter {
    constructor(loader) { this.loader = loader; }
    upload() {
        return this.loader.file.then(file => new Promise((resolve, reject) => {
            const fd = new FormData();
            fd.append('upload', file);
            fetch("{{ route('admin.news.image') }}", {
                method: 'POST',
                body: fd,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            }).then(r => r.json())
              .then(d => d.url ? resolve({ default: d.url }) : reject(d.message || 'Unggah gagal'))
              .catch(reject);
        }));
    }
    abort() {}
}
ClassicEditor.create(document.querySelector('#editor'), {
    extraPlugins: [editor => { editor.plugins.get('FileRepository').createUploadAdapter = loader => new NFUploadAdapter(loader); }],
    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', '|', 'imageUpload', 'undo', 'redo']
}).catch(console.error);
</script>
@endpush
