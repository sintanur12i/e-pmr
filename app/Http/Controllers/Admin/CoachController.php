<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    public function index()
    {
        $coaches = Coach::latest()->paginate(10);

        return view('admin.coaches.index', compact('coaches'));
    }

    public function create()
    {
        return view('admin.coaches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:100',
            'phone_number'   => 'required|string|max:15',
            'specialization' => 'required|string|max:100',
            'origin'         => 'required|in:internal,external',
        ]);

        Coach::create($validated);

        return redirect()
            ->route('admin.coaches.index')
            ->with('success', 'Pelatih berhasil ditambahkan.');
    }

    public function edit(Coach $coach)
    {
        return view('admin.coaches.edit', compact('coach'));
    }

    public function update(Request $request, Coach $coach)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:100',
            'phone_number'   => 'required|string|max:15',
            'specialization' => 'required|string|max:100',
            'origin'         => 'required|in:internal,external',
        ]);

        $coach->update($validated);

        return redirect()
            ->route('admin.coaches.index')
            ->with('success', 'Data pelatih berhasil diperbarui.');
    }

    public function destroy(Coach $coach)
    {
        $coach->delete();

        return redirect()
            ->route('admin.coaches.index')
            ->with('success', 'Pelatih berhasil dihapus.');
    }
}