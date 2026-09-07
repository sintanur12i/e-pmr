<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Member;
use App\Models\Period;
use App\Models\Unit;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::with(['member.user', 'period', 'unit'])->latest()->paginate(10);

        return view('admin.certificates.index', compact('certificates'));
    }

    public function create()
    {
        $members = Member::with('user')->get();
        $periods = Period::all();
        $units = Unit::all();

        return view('admin.certificates.create', compact('members', 'periods', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_id' => 'required|exists:members,id',
            'type'      => 'required|in:period_completion,unit_completion',
            'period_id' => 'required|exists:periods,id',
            'unit_id'   => 'nullable|required_if:type,unit_completion|exists:units,id',
            'title'     => 'required|string|max:150',
            'file'      => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'issued_at' => 'required|date',
        ]);

        $validated['file'] = $request->file('file')->store('certificates', 'public');
        $validated['issued_by'] = auth()->id();

        Certificate::create($validated);

        return redirect()
            ->route('admin.certificates.index')
            ->with('success', 'Sertifikat berhasil diterbitkan.');
    }

    public function destroy(Certificate $certificate)
    {
        $certificate->delete();

        return redirect()
            ->route('admin.certificates.index')
            ->with('success', 'Sertifikat berhasil dihapus.');
    }
}