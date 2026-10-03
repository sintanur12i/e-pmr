<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Auth::user()->member->certificates()->with(['period', 'unit'])->latest()->get();

        return view('certificates.index', compact('certificates'));
    }

    public function download(Certificate $certificate)
    {
        // Anggota hanya boleh mengunduh sertifikat miliknya sendiri.
        abort_unless($certificate->member_id === Auth::user()->member->id, 403);

        abort_unless(Storage::disk('public')->exists($certificate->file), 404, 'File sertifikat tidak ditemukan.');

        $extension = pathinfo($certificate->file, PATHINFO_EXTENSION);
        $filename = Str::slug($certificate->title) . '.' . $extension;

        return Storage::disk('public')->download($certificate->file, $filename);
    }
}