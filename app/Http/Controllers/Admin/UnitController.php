<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::with('coach')->latest()->paginate(10);

        return view('admin.units.index', compact('units'));
    }

    public function create()
    {
        $coaches = Coach::all();

        return view('admin.units.create', compact('coaches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:50',
            'description' => 'required|string',
            'coach_id'    => 'required|exists:coaches,id',
        ]);

        Unit::create($validated);

        return redirect()
            ->route('admin.units.index')
            ->with('success', 'Unit berhasil ditambahkan.');
    }

    public function edit(Unit $unit)
    {
        $coaches = Coach::all();

        return view('admin.units.edit', compact('unit', 'coaches'));
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:50',
            'description' => 'required|string',
            'coach_id'    => 'required|exists:coaches,id',
        ]);

        $unit->update($validated);

        return redirect()
            ->route('admin.units.index')
            ->with('success', 'Unit berhasil diperbarui.');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();

        return redirect()
            ->route('admin.units.index')
            ->with('success', 'Unit berhasil dihapus.');
    }

    public function members(\App\Models\Unit $unit)
    {
        $members = \App\Models\MemberUnit::where('unit_id', $unit->id)
            ->whereIn('status', ['approved', 'exit_requested'])
            ->with('member.user')
            ->get();

        return view('admin.units.members', compact('unit', 'members'));
    }
}