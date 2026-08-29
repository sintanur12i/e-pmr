<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coach;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::with('uploader')->latest()->paginate(10);

        return view('admin.materials.index', compact('materials'));
    }

    public function create()
    {
        $coaches = Coach::all();

        return view('admin.materials.create', compact('coaches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:100',
            'description' => 'required|string',
            'file'        => 'required|file|mimes:pdf,doc,docx,ppt,pptx|max:5120',
            'category'    => 'required|string|max:50',
            'uploaded_by' => 'required|exists:coaches,id',
            'date'        => 'required|date',
        ]);

        $validated['file'] = $request->file('file')->store('materials', 'public');

        Material::create($validated);

        return redirect()
            ->route('admin.materials.index')
            ->with('success', 'Materi berhasil ditambahkan.');
    }

    public function edit(Material $material)
    {
        $coaches = Coach::all();

        return view('admin.materials.edit', compact('material', 'coaches'));
    }

    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:100',
            'description' => 'required|string',
            'file'        => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:5120',
            'category'    => 'required|string|max:50',
            'uploaded_by' => 'required|exists:coaches,id',
            'date'        => 'required|date',
        ]);

        if ($request->hasFile('file')) {
            $validated['file'] = $request->file('file')->store('materials', 'public');
        }

        $material->update($validated);

        return redirect()
            ->route('admin.materials.index')
            ->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(Material $material)
    {
        $material->delete();

        return redirect()
            ->route('admin.materials.index')
            ->with('success', 'Materi berhasil dihapus.');
    }
}