<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpdbCommitment;
use Illuminate\Http\Request;

class PpdbCommitmentController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->can('ppdb.fields'), 403);

        $items = PpdbCommitment::all()->keyBy('jenjang');

        return view('admin.commitments.index', [
            'items' => $items,
            'jenjangs' => PpdbPeriodController::JENJANGS,
        ]);
    }

    public function update(Request $request)
    {
        abort_unless(auth()->user()->can('ppdb.fields'), 403);

        $data = $request->validate([
            'teks' => 'nullable|array',
            'teks.*' => 'nullable|string|max:10000',
        ]);

        foreach (PpdbPeriodController::JENJANGS as $slug => $label) {
            $teks = trim((string) ($data['teks'][$slug] ?? ''));
            PpdbCommitment::updateOrCreate(
                ['jenjang' => $slug],
                ['teks' => $teks !== '' ? $teks : null]
            );
        }

        return back()->with('success', 'Teks komitmen berhasil disimpan.');
    }
}
