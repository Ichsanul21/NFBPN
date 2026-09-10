<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpdbFormField;
use App\Models\PpdbRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PpdbFieldController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', PpdbFormField::class);

        $jenjang = $request->get('jenjang', 'sdit');
        abort_unless(array_key_exists($jenjang, PpdbPeriodController::JENJANGS), 404);

        $fields = PpdbFormField::forJenjang($jenjang)->ordered()->get();

        $editField = null;
        if ($request->filled('edit')) {
            $editField = PpdbFormField::forJenjang($jenjang)->find($request->input('edit'));
        }

        $triggers = $editField
            ? PpdbFormField::triggerCandidates($jenjang, $editField->id)
            : collect();

        return view('admin.fields.studio', [
            'fields' => $fields,
            'jenjang' => $jenjang,
            'sections' => PpdbFormField::forJenjang($jenjang)->whereNotNull('section')->distinct()->pluck('section')->filter()->values(),
            'jenjangs' => PpdbPeriodController::JENJANGS,
            'types' => PpdbFormField::TYPES,
            'operators' => PpdbFormField::OPERATORS,
            'editField' => $editField,
            'triggers' => $triggers,
            'answerCounts' => $this->answerCounts($jenjang),
            'conditions' => $this->conditionsFor($fields),
            'sample' => $this->sampleAnswers($fields),
        ]);
    }

    /**
     * Buat field baru dari pemilih tipe, langsung buka inspector.
     */
    public function quick(Request $request)
    {
        $this->authorize('create', PpdbFormField::class);

        $data = $request->validate([
            'jenjang' => 'required|in:'.implode(',', array_keys(PpdbPeriodController::JENJANGS)),
            'type' => 'required|in:'.implode(',', array_keys(PpdbFormField::TYPES)),
        ]);

        $label = 'Pertanyaan baru';
        $field = PpdbFormField::create([
            'jenjang' => $data['jenjang'],
            'key' => Str::slug($label, '_').'_'.time(),
            'label' => $label,
            'type' => $data['type'],
            'options' => in_array($data['type'], ['select', 'radio', 'checkbox'], true) ? ['Opsi 1', 'Opsi 2'] : null,
            'sort_order' => (int) PpdbFormField::forJenjang($data['jenjang'])->max('sort_order') + 1,
        ]);

        return redirect()->route('admin.fields.index', ['jenjang' => $field->jenjang, 'edit' => $field->id]);
    }

    /**
     * Simpan urutan drag-and-drop.
     */
    public function reorder(Request $request)
    {
        $this->authorize('update', PpdbFormField::class);

        $data = $request->validate([
            'jenjang' => 'required|string',
            'order' => 'required|array',
            'order.*' => 'integer',
        ]);

        $ids = PpdbFormField::forJenjang($data['jenjang'])->pluck('id')->all();
        abort_unless(empty(array_diff($data['order'], $ids)) && count($data['order']) === count($ids), 422);

        foreach ($data['order'] as $i => $id) {
            PpdbFormField::where('id', $id)->update(['sort_order' => $i]);
        }

        return response()->json(['ok' => true]);
    }

    public function update(Request $request, PpdbFormField $field)
    {
        $this->authorize('update', $field);

        $data = $this->validated($request, $field);
        if ($field->is_core) {
            unset($data['key'], $data['type'], $data['jenjang']);
        }

        $field->update($data);

        return redirect()->route('admin.fields.index', ['jenjang' => $field->jenjang, 'edit' => $field->id])
            ->with('success', 'Field berhasil diperbarui. Cek pratinjau di kanan.');
    }

    public function destroy(PpdbFormField $field)
    {
        $this->authorize('delete', $field);

        if ($field->is_core) {
            return back()->with('error', 'Field inti tidak dapat dihapus.');
        }

        $dependents = PpdbFormField::forJenjang($field->jenjang)
            ->where('visible_if_field', $field->key)->pluck('label')->all();
        if ($dependents) {
            return back()->with('error', 'Tidak bisa dihapus. Field ini dipakai aturan tampil oleh: '.implode(', ', $dependents).'. Ubah dulu aturan tersebut.');
        }

        if (($this->answerCounts($field->jenjang)[$field->key] ?? 0) > 0) {
            return back()->with('error', 'Tidak bisa dihapus karena sudah ada jawaban pendaftar. Arsipkan saja (nonaktifkan) agar tidak tampil di form baru.');
        }

        $field->delete();

        return redirect()->route('admin.fields.index', ['jenjang' => $field->jenjang])
            ->with('success', 'Field berhasil dihapus.');
    }

    protected function validated(Request $request, ?PpdbFormField $field = null): array
    {
        $data = $request->validate([
            'jenjang' => 'required|in:'.implode(',', array_keys(PpdbPeriodController::JENJANGS)),
            'label' => 'required|string|max:255',
            'section' => 'nullable|string|max:255',
            'type' => 'required|in:'.implode(',', array_keys(PpdbFormField::TYPES)),
            'options' => 'nullable|array|max:50',
            'options.*' => 'nullable|string|max:255',
            'is_required' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'visible_if_field' => 'nullable|string|max:255',
            'visible_if_operator' => 'nullable|in:'.implode(',', array_keys(PpdbFormField::OPERATORS)),
            'visible_if_value' => 'nullable|string|max:2000',
            'visible_if_values' => 'nullable|array',
            'visible_if_values.*' => 'string|max:255',
        ]);

        $options = collect($data['options'] ?? [])
            ->map(fn ($o) => \App\Support\Sanitize::text((string) $o, 255))->filter()->values()->all();
        if (in_array($data['type'], ['select', 'radio', 'checkbox'], true)) {
            abort_if(empty($options), 422, 'Tipe pilihan wajib punya minimal satu opsi.');
            $options = $options ?: null;
        } else {
            $options = null;
        }

        return [
            'jenjang' => $data['jenjang'],
            'label' => \App\Support\Sanitize::name($data['label']),
            'section' => \App\Support\Sanitize::name($data['section'] ?? null),
            'type' => $data['type'],
            'options' => $options,
            'is_required' => ! empty($data['is_required']),
            'is_active' => ! empty($data['is_active']),
            'sort_order' => $data['sort_order'] ?? $field?->sort_order ?? 0,
            ...$this->validatedCondition($request, $data['jenjang'], $field),
        ];
    }

    protected function validatedCondition(Request $request, string $jenjang, ?PpdbFormField $field = null): array
    {
        $triggerKey = $request->input('visible_if_field');
        $operator = $request->input('visible_if_operator');

        if (empty($triggerKey) || empty($operator)) {
            return ['visible_if_field' => null, 'visible_if_operator' => null, 'visible_if_value' => null];
        }

        $trigger = PpdbFormField::forJenjang($jenjang)->where('key', $triggerKey)->first();
        abort_unless($trigger, 422, 'Field pemicu tidak ditemukan.');
        abort_if($field && $trigger->id === $field->id, 422, 'Field tidak boleh memicu dirinya sendiri.');
        abort_if($trigger->hasCondition(), 422, 'Field pemicu tidak boleh field yang berkondisi (maksimal 1 level).');
        abort_if($trigger->type === 'file', 422, 'Field unggahan tidak dapat menjadi pemicu.');

        if (in_array($operator, ['filled', 'empty'], true)) {
            return [
                'visible_if_field' => $trigger->key,
                'visible_if_operator' => $operator,
                'visible_if_value' => null,
            ];
        }

        if (in_array($operator, ['in', 'not_in'], true)) {
            $values = collect((array) $request->input('visible_if_values', []))
                ->map(fn ($v) => \App\Support\Sanitize::text((string) $v, 255))->filter()->values()->all();
            abort_if(empty($values), 422, 'Pilih minimal satu nilai pembanding.');
            if ($trigger->options) {
                abort_unless(empty(array_diff($values, $trigger->options)), 422, 'Nilai pembanding harus dari opsi pemicu.');
            }

            return [
                'visible_if_field' => $trigger->key,
                'visible_if_operator' => $operator,
                'visible_if_value' => $values,
            ];
        }

        $value = \App\Support\Sanitize::text((string) $request->input('visible_if_value', ''), 255) ?? '';
        abort_if($value === '', 422, 'Nilai pembanding wajib diisi.');
        if ($trigger->options) {
            abort_unless(in_array($value, $trigger->options, true), 422, 'Nilai pembanding harus dari opsi pemicu.');
        }

        return [
            'visible_if_field' => $trigger->key,
            'visible_if_operator' => $operator,
            'visible_if_value' => $value,
        ];
    }

    /**
     * Hitung jawaban per key untuk pengaman hapus.
     * @return array<string,int>
     */
    protected function answerCounts(string $jenjang): array
    {
        $counts = [];
        PpdbRegistration::where('jenjang', $jenjang)->pluck('answers')->each(function ($answers) use (&$counts) {
            foreach ((array) $answers as $key => $val) {
                if ($val !== null && $val !== '' && $val !== []) {
                    $counts[$key] = ($counts[$key] ?? 0) + 1;
                }
            }
        });

        return $counts;
    }

    protected function conditionsFor($fields): array
    {
        $out = [];
        foreach ($fields as $f) {
            if ($f->hasCondition()) {
                $out[$f->key] = [
                    'trigger' => $f->visible_if_field,
                    'op' => $f->visible_if_operator,
                    'value' => $f->visible_if_value,
                ];
            }
        }

        return $out;
    }

    /**
     * Jawaban contoh agar pratinjau langsung menunjukkan field kondisional.
     */
    protected function sampleAnswers($fields): array
    {
        $sample = [];
        foreach ($fields as $f) {
            if (! $f->hasCondition()) {
                continue;
            }
            if (array_key_exists($f->visible_if_field, $sample)) {
                continue;
            }
            $sample[$f->visible_if_field] = match ($f->visible_if_operator) {
                'equals' => is_array($f->visible_if_value) ? ($f->visible_if_value[0] ?? 'Contoh') : ($f->visible_if_value ?? 'Contoh'),
                'in' => is_array($f->visible_if_value) ? ($f->visible_if_value[0] ?? 'Contoh') : 'Contoh',
                'filled' => 'Contoh jawaban',
                'not_equals', 'not_in' => 'Nilai lain',
                default => null,
            };
        }

        return array_filter($sample, fn ($v) => $v !== null);
    }
}
