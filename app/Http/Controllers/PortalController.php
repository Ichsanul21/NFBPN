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

        return view('pages.portal-detail', ['item' => $registration, 'fields' => $fields]);
    }

    public function uploadBerkas(Request $request, PpdbRegistration $registration)
    {
        $this->authorize('update', $registration);

        $request->validate([
            'berkas' => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:5120',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $path = $request->file('berkas')->store('ppdb/berkas', 'public');

        $answers = $registration->answers ?? [];
        $answers['berkas_tambahan'][] = [
            'path' => $path,
            'keterangan' => $request->keterangan,
            'diunggah' => now()->format('Y-m-d H:i'),
        ];
        $registration->update(['answers' => $answers]);

        if (! Storage::disk('public')->exists($path)) {
            return back()->with('error', 'Unggahan gagal, silakan coba lagi.');
        }

        return back()->with('success', 'Berkas berhasil diunggah.');
    }
}
