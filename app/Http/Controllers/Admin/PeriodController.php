<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Period;
use Illuminate\Http\Request;

class PeriodController extends Controller
{
    public function index()
    {
        $periods = Period::latest()->paginate(10);

        return view('admin.periods.index', compact('periods'));
    }

    public function create()
    {
        return view('admin.periods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'status'     => 'required|in:active,inactive',
        ], [
            'end_date.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);

        Period::create($validated);

        return redirect()
            ->route('admin.periods.index')
            ->with('success', 'Periode berhasil ditambahkan.');
    }

    public function edit(Period $period)
    {
        return view('admin.periods.edit', compact('period'));
    }

    public function update(Request $request, Period $period)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:50',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'status'     => 'required|in:active,inactive',
        ], [
            'end_date.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);

        $period->update($validated);

        return redirect()
            ->route('admin.periods.index')
            ->with('success', 'Periode berhasil diperbarui.');
    }

    public function destroy(Period $period)
    {
        $period->delete();

        return redirect()
            ->route('admin.periods.index')
            ->with('success', 'Periode berhasil dihapus.');
    }
}