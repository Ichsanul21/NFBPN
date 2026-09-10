<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpdbFormField;
use App\Models\PpdbPeriod;
use App\Models\PpdbRegistration;
use Illuminate\Http\Request;

class PpdbRegistrationController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', PpdbRegistration::class);

        $regs = PpdbRegistration::with('period')
            ->when($request->period_id, fn ($q, $v) => $q->where('period_id', $v))
            ->when($request->jenjang, fn ($q, $v) => $q->where('jenjang', $v))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when($request->q, fn ($q, $v) => $q->where(fn ($w) => $w
                ->where('child_name', 'like', "%{$v}%")
                ->orWhere('registration_no', 'like', "%{$v}%")))
            ->latest()->paginate(20)->withQueryString();

        return view('admin.registrations.index', [
            'regs' => $regs,
            'periods' => PpdbPeriod::orderBy('starts_on', 'desc')->get(),
            'statuses' => PpdbRegistration::STATUSES,
            'jenjangs' => PpdbPeriodController::JENJANGS,
        ]);
    }

    public function show(PpdbRegistration $registration)
    {
        $this->authorize('view', $registration);

        $registration->load(['period', 'parent', 'histories.changer']);
        $fields = PpdbFormField::forJenjang($registration->jenjang)->ordered()->get()->keyBy('key');

        return view('admin.registrations.show', [
            'item' => $registration,
            'fields' => $fields,
            'statuses' => PpdbRegistration::STATUSES,
        ]);
    }

    public function update(Request $request, PpdbRegistration $registration)
    {
        $this->authorize('update', $registration);

        $data = $request->validate([
            'status' => 'required|in:'.implode(',', array_keys(PpdbRegistration::STATUSES)),
            'admin_note' => 'nullable|string|max:2000',
            'history_note' => 'nullable|string|max:1000',
        ]);

        $from = $registration->status;
        $registration->update([
            'status' => $data['status'],
            'admin_note' => \App\Support\Sanitize::text($data['admin_note'] ?? $registration->admin_note),
        ]);

        if ($from !== $data['status'] || ! empty($data['history_note'])) {
            $registration->histories()->create([
                'from_status' => $from !== $data['status'] ? $from : null,
                'to_status' => $data['status'],
                'note' => $data['history_note'] ?? null,
                'changed_by' => auth()->id(),
            ]);
        }

        return back()->with('success', 'Status pendaftaran berhasil diperbarui.');
    }

    public function destroy(PpdbRegistration $registration)
    {
        $this->authorize('delete', $registration);

        $registration->delete();

        return redirect()->route('admin.registrations.index')->with('success', 'Data pendaftaran berhasil dihapus.');
    }
}
