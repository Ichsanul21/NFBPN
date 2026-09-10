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

    public function create(Request $request)
    {
        $this->authorize('create', PpdbFormField::class);

        return view('admin.fields.form', [
            'item' => new PpdbFormField(['jenjang' => $request->get('jenjang', 'sdit')]),
            'jenjangs' => PpdbPeriodController::JENJANGS,
            'types' => PpdbFormField::TYPES,
        ]);
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

        return view('admin.fields.form', [
            'item' => $field,
            'jenjangs' => PpdbPeriodController::JENJANGS,
            'types' => PpdbFormField::TYPES,
        ]);
    }

    public function update(Request $request, PpdbFormField $field)
    {
        $this->authorize('update', $field);

        $data = $this->validated($request);
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

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'jenjang' => 'required|in:'.implode(',', array_keys(PpdbPeriodController::JENJANGS)),
            'label' => 'required|string|max:255',
            'type' => 'required|in:'.implode(',', array_keys(PpdbFormField::TYPES)),
            'options_text' => 'nullable|string',
            'is_required' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $options = null;
        if (in_array($data['type'], ['select', 'radio', 'checkbox'], true) && ! empty($data['options_text'])) {
            $options = collect(preg_split('/\r\n|\r|\n/', $data['options_text']))
                ->map(fn ($o) => trim($o))->filter()->values()->all();
        }

        return [
            'jenjang' => $data['jenjang'],
            'label' => $data['label'],
            'type' => $data['type'],
            'options' => $options,
            'is_required' => ! empty($data['is_required']),
            'sort_order' => $data['sort_order'] ?? 0,
        ];
    }
}
