<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::with('agenda')->latest()->paginate(12);

        return view('admin.galleries.index', compact('galleries'));
    }

    public function create()
    {
        $agendas = Agenda::orderByDesc('date')->get();

        return view('admin.galleries.create', compact('agendas'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'agenda_id' => 'nullable|exists:agendas,id',
        'photos'    => 'required|array|min:1',
        'photos.*'  => 'image|mimes:jpg,jpeg,png|max:2048',
        'caption'   => 'required|string|max:150',
    ]);

    foreach ($request->file('photos') as $photo) {
        Gallery::create([
            'agenda_id'   => $validated['agenda_id'] ?? null,
            'photo'       => $photo->store('galleries', 'public'),
            'caption'     => $validated['caption'],
            'uploaded_by' => auth()->id(),
        ]);
    }

    $count = count($request->file('photos'));

    return redirect()
        ->route('admin.galleries.index')
        ->with('success', "{$count} foto berhasil ditambahkan ke galeri.");
}

    public function destroy(Gallery $gallery)
    {
        $gallery->delete();

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Foto berhasil dihapus.');
    }
}