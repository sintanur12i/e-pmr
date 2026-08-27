<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Management;
use App\Models\Member;
use App\Models\Period;
use Illuminate\Http\Request;

class ManagementController extends Controller
{
    public function index()
    {
        $managements = Management::with(['member.user', 'period'])->latest()->paginate(10);

        return view('admin.managements.index', compact('managements'));
    }

    public function create()
    {
        $members = Member::with('user')->get();
        $periods = Period::all();

        return view('admin.managements.create', compact('members', 'periods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'period_id' => 'required|exists:periods,id',
            'position'  => 'required|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Management::create($validated);

        return redirect()
            ->route('admin.managements.index')
            ->with('success', 'Jabatan berhasil ditambahkan.');
    }

    public function edit(Management $management)
    {
        $members = Member::with('user')->get();
        $periods = Period::all();

        return view('admin.managements.edit', compact('management', 'members', 'periods'));
    }

    public function update(Request $request, Management $management)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'period_id' => 'required|exists:periods,id',
            'position'  => 'required|string|max:50',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $management->update($validated);

        return redirect()
            ->route('admin.managements.index')
            ->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Management $management)
    {
        $management->delete();

        return redirect()
            ->route('admin.managements.index')
            ->with('success', 'Data jabatan berhasil dihapus.');
    }
}