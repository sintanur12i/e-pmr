<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::latest()->paginate(10);

        return view('materials.index', compact('materials'));
    }

    /**
     * Tampilkan file langsung di tab baru (inline), tanpa memaksa download.
     * PDF dan gambar akan terbuka langsung di browser. File seperti .docx tetap
     * akan diproses lewat aplikasi/Office Viewer bawaan OS, karena browser
     * memang tidak punya pembaca Word bawaan — ini batasan teknis browser,
     * bukan sesuatu yang bisa diatur dari sisi server.
     */
    public function view(Material $material)
    {
        $disk = Storage::disk('public');

        if (! $disk->exists($material->file)) {
            abort(404, 'File materi tidak ditemukan.');
        }

        $ext = strtolower(pathinfo($material->file, PATHINFO_EXTENSION));

        // PDF & gambar: tampil langsung di browser
        if (in_array($ext, ['pdf', 'jpg', 'jpeg', 'png'])) {
            return $disk->response($material->file);
        }

        // Word (.docx): tampil lewat halaman viewer
        if ($ext === 'docx') {
            return view('materials.preview', compact('material'));
        }

        // .doc, .ppt, .pptx: browser tidak bisa menampilkan, jadi diunduh
        return $this->download($material);
    }

    /** Paksa file ter-download, apa pun jenis filenya. */
    public function download(Material $material)
    {
        if (! Storage::disk('public')->exists($material->file)) {
            abort(404, 'File materi tidak ditemukan.');
        }

        $extension = pathinfo($material->file, PATHINFO_EXTENSION);
        $filename = $material->title . ($extension ? '.' . $extension : '');

        return Storage::disk('public')->download($material->file, $filename);
    }
}