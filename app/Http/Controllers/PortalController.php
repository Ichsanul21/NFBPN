<?php

namespace App\Http\Controllers;

use App\Models\PpdbFormField;
use App\Models\PpdbRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PortalController extends Controller
{
    public function index()
    {
        $regs = PpdbRegistration::with('period')
            ->where('user_id', auth()->id())
            ->latest()->get();

        return view('pages.portal', ['regs' => $regs]);
    }

    public function show(PpdbRegistration $registration)
    {
        $this->authorize('view', $registration);

        $registration->load(['period', 'histories.changer']);
        $fields = PpdbFormField::forJenjang($registration->jenjang)->ordered()->get()->keyBy('key');
        $documents = \App\Models\PpdbDocument::forJenjang($registration->jenjang)
            ->active()->ordered()->get()
            ->filter(fn ($d) => $d->isVisibleFor($registration->answers ?? []))->values();

        return view('pages.portal-detail', [
            'item' => $registration,
            'fields' => $fields,
            'documents' => $documents,
        ]);
    }

    public function uploadBerkas(Request $request, PpdbRegistration $registration)
    {
        $this->authorize('update', $registration);

        // Unggah dokumen checklist: patuhi tipe + ukuran + kompresi per dokumen.
        if ($request->filled('doc_id')) {
            $doc = \App\Models\PpdbDocument::forJenjang($registration->jenjang)->findOrFail($request->input('doc_id'));

            $request->validate([
                'berkas' => 'required|file|mimes:'.implode(',', $doc->allowedMimes()).'|max:'.$doc->max_kb,
            ], [], ['berkas' => $doc->label]);

            $file = $request->file('berkas');
            if ($doc->compress && str_starts_with((string) $file->getMimeType(), 'image/')) {
                $stored = app(\App\Services\ImageService::class)->storePhoto($file, 'ppdb/dokumen', 1600);
                $path = $stored['path'];
            } else {
                $path = $file->storeAs(
                    'ppdb/dokumen',
                    \Illuminate\Support\Str::random(24).'.'.$file->getClientOriginalExtension(),
                    'public'
                );
            }

            $answers = $registration->answers ?? [];
            $answers['dokumen'][$doc->docKey()] = [
                'path' => $path,
                'nama' => $doc->label,
                'diunggah' => now()->format('Y-m-d H:i'),
            ];
            $registration->update(['answers' => $answers]);

            return back()->with('success', $doc->label.' berhasil diunggah.');
        }

        $request->validate([
            'berkas' => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $path = $request->file('berkas')->store('ppdb/berkas', 'public');

        $answers = $registration->answers ?? [];
        $answers['berkas_tambahan'][] = [
            'path' => $path,
            'keterangan' => \App\Support\Sanitize::text($request->keterangan, 255),
            'diunggah' => now()->format('Y-m-d H:i'),
        ];
        $registration->update(['answers' => $answers]);

        if (! Storage::disk('public')->exists($path)) {
            return back()->with('error', 'Unggahan gagal, silakan coba lagi.');
        }

        return back()->with('success', 'Berkas berhasil diunggah.');
    }
}
