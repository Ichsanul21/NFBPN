<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpdbDocument;
use App\Models\PpdbFormField;
use Illuminate\Http\Request;

class PpdbDocumentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', PpdbDocument::class);

        $jenjang = $request->get('jenjang', 'sdit');
        $docs = PpdbDocument::forJenjang($jenjang)->ordered()->get();

        return view('admin.documents.index', [
            'docs' => $docs,
            'jenjang' => $jenjang,
            'jenjangs' => PpdbPeriodController::JENJANGS,
        ]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', PpdbDocument::class);

        return view('admin.documents.form', $this->formData(
            new PpdbDocument([
                'jenjang' => $request->get('jenjang', 'sdit'),
                'allowed' => array_keys(PpdbDocument::TYPES),
                'max_kb' => 2048,
                'compress' => true,
            ])
        ));
    }

    public function store(Request $request)
    {
        $this->authorize('create', PpdbDocument::class);

        $doc = PpdbDocument::create($this->validated($request));

        return redirect()->route('admin.documents.index', ['jenjang' => $doc->jenjang])
            ->with('success', 'Dokumen berhasil ditambahkan.');
    }

    public function edit(PpdbDocument $document)
    {
        $this->authorize('update', $document);

        return view('admin.documents.form', $this->formData($document));
    }

    public function update(Request $request, PpdbDocument $document)
    {
        $this->authorize('update', $document);

        $document->update($this->validated($request, $document));

        return redirect()->route('admin.documents.index', ['jenjang' => $document->jenjang])
            ->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(PpdbDocument $document)
    {
        $this->authorize('delete', $document);

        $document->delete();

        return redirect()->route('admin.documents.index', ['jenjang' => $document->jenjang])
            ->with('success', 'Dokumen berhasil dihapus.');
    }

    protected function formData(PpdbDocument $doc): array
    {
        return [
            'item' => $doc,
            'jenjangs' => PpdbPeriodController::JENJANGS,
            'types' => PpdbDocument::TYPES,
            'operators' => PpdbFormField::OPERATORS,
            'triggers' => PpdbFormField::triggerCandidates($doc->jenjang ?: 'sdit'),
        ];
    }

    protected function validated(Request $request, ?PpdbDocument $doc = null): array
    {
        $data = $request->validate([
            'jenjang' => 'required|in:'.implode(',', array_keys(PpdbPeriodController::JENJANGS)),
            'label' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:1000',
            'wajib' => 'nullable|boolean',
            'aktif' => 'nullable|boolean',
            'urut' => 'nullable|integer|min:0',
            'allowed' => 'nullable|array',
            'allowed.*' => 'in:'.implode(',', array_keys(PpdbDocument::TYPES)),
            'max_kb' => 'required|integer|min:100|max:20480',
            'compress' => 'nullable|boolean',
            'visible_if_field' => 'nullable|string|max:255',
            'visible_if_operator' => 'nullable|in:'.implode(',', array_keys(PpdbFormField::OPERATORS)),
            'visible_if_value' => 'nullable|string|max:2000',
            'visible_if_values' => 'nullable|array',
            'visible_if_values.*' => 'string|max:255',
        ]);

        return [
            'jenjang' => $data['jenjang'],
            'label' => \App\Support\Sanitize::name($data['label']),
            'deskripsi' => \App\Support\Sanitize::text($data['deskripsi'] ?? null, 1000),
            'wajib' => ! empty($data['wajib']),
            'aktif' => ! empty($data['aktif']),
            'urut' => $data['urut'] ?? $doc?->urut ?? 0,
            'allowed' => ! empty($data['allowed']) ? array_values($data['allowed']) : array_keys(PpdbDocument::TYPES),
            'max_kb' => $data['max_kb'],
            'compress' => ! empty($data['compress']),
            ...$this->validatedCondition($request, $data['jenjang']),
        ];
    }

    protected function validatedCondition(Request $request, string $jenjang): array
    {
        $triggerKey = $request->input('visible_if_field');
        $operator = $request->input('visible_if_operator');

        if (empty($triggerKey) || empty($operator)) {
            return ['visible_if_field' => null, 'visible_if_operator' => null, 'visible_if_value' => null];
        }

        $trigger = PpdbFormField::forJenjang($jenjang)->where('key', $triggerKey)->first();
        abort_unless($trigger, 422, 'Field pemicu tidak ditemukan.');
        abort_if($trigger->hasCondition(), 422, 'Field pemicu tidak boleh field yang berkondisi.');
        abort_if($trigger->type === 'file', 422, 'Field unggahan tidak dapat menjadi pemicu.');

        if (in_array($operator, ['filled', 'empty'], true)) {
            return ['visible_if_field' => $trigger->key, 'visible_if_operator' => $operator, 'visible_if_value' => null];
        }

        if (in_array($operator, ['in', 'not_in'], true)) {
            $values = collect((array) $request->input('visible_if_values', []))
                ->map(fn ($v) => \App\Support\Sanitize::text((string) $v, 255))->filter()->values()->all();
            abort_if(empty($values), 422, 'Pilih minimal satu nilai pembanding.');
            if ($trigger->options) {
                abort_unless(empty(array_diff($values, $trigger->options)), 422, 'Nilai pembanding harus dari opsi pemicu.');
            }

            return ['visible_if_field' => $trigger->key, 'visible_if_operator' => $operator, 'visible_if_value' => $values];
        }

        $value = \App\Support\Sanitize::text((string) $request->input('visible_if_value', ''), 255) ?? '';
        abort_if($value === '', 422, 'Nilai pembanding wajib diisi.');
        if ($trigger->options) {
            abort_unless(in_array($value, $trigger->options, true), 422, 'Nilai pembanding harus dari opsi pemicu.');
        }

        return ['visible_if_field' => $trigger->key, 'visible_if_operator' => $operator, 'visible_if_value' => $value];
    }
}
