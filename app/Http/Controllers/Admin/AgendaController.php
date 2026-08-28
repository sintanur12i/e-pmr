<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Coach;
use App\Models\Period;
use App\Models\Unit;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index()
    {
        $agendas = Agenda::with(['period', 'unit', 'coach'])->latest('date')->paginate(10);

        return view('admin.agendas.index', compact('agendas'));
    }

    public function create()
    {
        $periods = Period::where('status', 'active')->get();
        $units = Unit::all();
        $coaches = Coach::all();

        return view('admin.agendas.create', compact('periods', 'units', 'coaches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'period_id'   => 'required|exists:periods,id',
            'unit_id'     => 'nullable|exists:units,id',
            'type'        => 'required|in:general,unit,training',
            'coach_id'    => 'nullable|exists:coaches,id',
            'title'       => 'required|string|max:100',
            'description' => 'required|string',
            'date'        => 'required|date',
            'time'        => 'required',
            'location'    => 'required|string|max:100',
        ]);

        $validated['created_by'] = auth()->id();

        Agenda::create($validated);

        return redirect()
            ->route('admin.agendas.index')
            ->with('success', 'Agenda berhasil ditambahkan.');
    }

    public function edit(Agenda $agenda)
    {
        $periods = Period::all();
        $units = Unit::all();
        $coaches = Coach::all();

        return view('admin.agendas.edit', compact('agenda', 'periods', 'units', 'coaches'));
    }

    public function update(Request $request, Agenda $agenda)
    {
        $validated = $request->validate([
            'period_id'   => 'required|exists:periods,id',
            'unit_id'     => 'nullable|exists:units,id',
            'type'        => 'required|in:general,unit,training',
            'target_role'  => 'required|in:all,member,candidate_member',
            'coach_id'    => 'nullable|exists:coaches,id',
            'title'       => 'required|string|max:100',
            'description' => 'required|string',
            'date'        => 'required|date',
            'time'        => 'required',
            'location'    => 'required|string|max:100',
        ]);

        $agenda->update($validated);

        return redirect()
            ->route('admin.agendas.index')
            ->with('success', 'Agenda berhasil diperbarui.');
    }

    public function destroy(Agenda $agenda)
    {
        $agenda->delete();

        return redirect()
            ->route('admin.agendas.index')
            ->with('success', 'Agenda berhasil dihapus.');
    }
}