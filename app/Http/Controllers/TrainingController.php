<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingController extends Controller
{
    public function index()
    {
        $trainings = Training::where('member_id', Auth::user()->member->id)
            ->latest('date')
            ->paginate(10);

        return view('trainings.index', compact('trainings'));
    }

    public function create()
    {
        return view('trainings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'training_name' => 'required|string|max:100',
            'organizer'     => 'required|string|max:100',
            'date'          => 'required|date',
            'certificate'   => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'notes'         => 'required|string',
        ]);

        $validated['certificate'] = $request->file('certificate')->store('certificates', 'public');
        $validated['member_id'] = Auth::user()->member->id;

        Training::create($validated);

        return redirect()
            ->route('trainings.index')
            ->with('success', 'Riwayat pelatihan berhasil ditambahkan.');
    }

    public function edit(Training $training)
    {
        // pastikan member cuma bisa edit data miliknya sendiri
        abort_if($training->member_id !== Auth::user()->member->id, 403);

        return view('trainings.edit', compact('training'));
    }

    public function update(Request $request, Training $training)
    {
        abort_if($training->member_id !== Auth::user()->member->id, 403);

        $validated = $request->validate([
            'training_name' => 'required|string|max:100',
            'organizer'     => 'required|string|max:100',
            'date'          => 'required|date',
            'certificate'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'notes'         => 'required|string',
        ]);

        if ($request->hasFile('certificate')) {
            $validated['certificate'] = $request->file('certificate')->store('certificates', 'public');
        }

        $training->update($validated);

        return redirect()
            ->route('trainings.index')
            ->with('success', 'Riwayat pelatihan berhasil diperbarui.');
    }

    public function destroy(Training $training)
    {
        abort_if($training->member_id !== Auth::user()->member->id, 403);

        $training->delete();

        return redirect()
            ->route('trainings.index')
            ->with('success', 'Riwayat pelatihan berhasil dihapus.');
    }
}