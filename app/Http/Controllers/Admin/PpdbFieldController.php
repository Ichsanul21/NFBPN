<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpdbFormField;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PpdbFieldController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', PpdbFormField::class);

        $jenjang = $request->get('jenjang', 'sdit');
        $fields = PpdbFormField::forJenjang($jenjang)->ordered()->get();

        return view('admin.fields.index', [
            'fields' => $fields,
            'jenjang' => $jenjang,
            'jenjangs' => PpdbPeriodController::JENJANGS,
            'types' => PpdbFormField::TYPES,
        ]);
    }

    protected function formData(PpdbFormField $item): array
    {
        return [
            'item' => $item,
            'jenjangs' => PpdbPeriodController::JENJANGS,
            'types' => PpdbFormField::TYPES,
            'operators' => PpdbFormField::OPERATORS,
            'triggers' => PpdbFormField::triggerCandidates($item->jenjang ?: 'sdit', $item->id),
        ];
    }

    public function create(Request $request)
    {
        $this->authorize('create', PpdbFormField::class);

        return view('admin.fields.form', $this->formData(
            new PpdbFormField(['jenjang' => $request->get('jenjang', 'sdit')])
        ));
    }

    public function store(Request $request)
    {
        $this->authorize('create', PpdbFormField::class);

        $data = $this->validated($request);
        $data['key'] = Str::slug($data['label'], '_') ?: 'field_'.time();
        $data['is_core'] = false;

        PpdbFormField::create($data);

        return redirect()->route('admin.fields.index', ['jenjang' => $data['jenjang']])
            ->with('success', 'Field berhasil ditambahkan.');
    }

    public function edit(PpdbFormField $field)
    {
        $this->authorize('update', $field);

        return view('admin.fields.form', $this->formData($field));
    }

    public function update(Request $request, PpdbFormField $field)
    {
        $this->authorize('update', $field);

        $data = $this->validated($request, $field);
        if ($field->is_core) {
            unset($data['key'], $data['type'], $data['jenjang']);
        }

        $field->update($data);

        return redirect()->route('admin.fields.index', ['jenjang' => $field->jenjang])
            ->with('success', 'Field berhasil diperbarui.');
    }

    public function destroy(PpdbFormField $field)
    {
        $this->authorize('delete', $field);

        if ($field->is_core) {
            return back()->with('error', 'Field inti tidak dapat dihapus.');
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
            'options_text' => 'nullable|string',
            'is_required' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'visible_if_field' => 'nullable|string|max:255',
            'visible_if_operator' => 'nullable|in:'.implode(',', array_keys(PpdbFormField::OPERATORS)),
            'visible_if_value' => 'nullable|string|max:2000',
            'visible_if_values' => 'nullable|array',
            'visible_if_values.*' => 'string|max:255',
        ]);

        $options = null;
        if (in_array($data['type'], ['select', 'radio', 'checkbox'], true) && ! empty($data['options_text'])) {
            $options = collect(preg_split('/\r\n|\r|\n/', $data['options_text']))
                ->map(fn ($o) => trim($o))->filter()->values()->all();
        }

        $condition = $this->validatedCondition($request, $data['jenjang'], $field);

        return [
            'jenjang' => $data['jenjang'],
            'label' => $data['label'],
            'section' => $data['section'] ? trim($data['section']) : null,
            'type' => $data['type'],
            'options' => $options,
            'is_required' => ! empty($data['is_required']),
            'sort_order' => $data['sort_order'] ?? 0,
            ...$condition,
        ];
    }

    /**
     * Validasi kondisi tampil. Pemicu harus: sejenjang, tanpa kondisi sendiri
     * (mencegah siklus, maksimal 1 level), bukan diri sendiri, bukan tipe file.
     */
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
                ->map(fn ($v) => trim((string) $v))->filter()->values()->all();
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

        $value = trim((string) $request->input('visible_if_value', ''));
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
}
