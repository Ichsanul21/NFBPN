<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpdbPeriod;
use Illuminate\Http\Request;

class PpdbPeriodController extends Controller
{
    public const JENJANGS = ['daycare' => 'Daycare', 'kbit' => 'KBIT', 'sdit' => 'SDIT', 'smpit' => 'SMPIT'];

    public function index()
    {
        $this->authorize('viewAny', PpdbPeriod::class);

        $periods = PpdbPeriod::withCount('registrations')->orderBy('starts_on', 'desc')->paginate(15);

        return view('admin.periods.index', compact('periods'));
    }

    public function create()
    {
        $this->authorize('create', PpdbPeriod::class);

        return view('admin.periods.form', ['item' => new PpdbPeriod(), 'jenjangs' => self::JENJANGS]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', PpdbPeriod::class);

        PpdbPeriod::create($this->validated($request));

        return redirect()->route('admin.periods.index')->with('success', 'Periode berhasil ditambahkan.');
    }

    public function edit(PpdbPeriod $period)
    {
        $this->authorize('update', $period);

        return view('admin.periods.form', ['item' => $period, 'jenjangs' => self::JENJANGS]);
    }

    public function update(Request $request, PpdbPeriod $period)
    {
        $this->authorize('update', $period);

        $period->update($this->validated($request));

        return redirect()->route('admin.periods.index')->with('success', 'Periode berhasil diperbarui.');
    }

    public function destroy(PpdbPeriod $period)
    {
        $this->authorize('delete', $period);

        $period->delete();

        return redirect()->route('admin.periods.index')->with('success', 'Periode beserta pendaftarnya berhasil dihapus.');
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'jenjang' => 'required|in:'.implode(',', array_keys(self::JENJANGS)),
            'starts_on' => 'required|date',
            'ends_on' => 'required|date|after_or_equal:starts_on',
            'quota' => 'nullable|integer|min:1',
            'note' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
