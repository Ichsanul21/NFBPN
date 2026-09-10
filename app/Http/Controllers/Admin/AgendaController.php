<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Agenda::class);

        $agendas = Agenda::orderBy('date', 'desc')->paginate(15);

        return view('admin.agendas.index', compact('agendas'));
    }

    public function create()
    {
        $this->authorize('create', Agenda::class);

        return view('admin.agendas.form', ['item' => new Agenda()]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Agenda::class);

        Agenda::create($this->validated($request));

        return redirect()->route('admin.agendas.index')->with('success', 'Agenda berhasil ditambahkan.');
    }

    public function edit(Agenda $agenda)
    {
        $this->authorize('update', $agenda);

        return view('admin.agendas.form', ['item' => $agenda]);
    }

    public function update(Request $request, Agenda $agenda)
    {
        $this->authorize('update', $agenda);

        $agenda->update($this->validated($request));

        return redirect()->route('admin.agendas.index')->with('success', 'Agenda berhasil diperbarui.');
    }

    public function destroy(Agenda $agenda)
    {
        $this->authorize('delete', $agenda);

        $agenda->delete();

        return redirect()->route('admin.agendas.index')->with('success', 'Agenda berhasil dihapus.');
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'time_label' => 'nullable|string|max:255',
            'place' => 'nullable|string|max:255',
            'is_published' => 'nullable|boolean',
        ]);
        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }
}
