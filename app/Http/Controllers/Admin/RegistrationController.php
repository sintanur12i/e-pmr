<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        $registrations = Registration::with(['user', 'period'])
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10);

        return view('admin.registrations.index', compact('registrations', 'status'));
    }

    public function show(Registration $registration)
    {
        $registration->load(['user', 'period']);

        return view('admin.registrations.show', compact('registration'));
    }

    public function approveForm(Registration $registration)
    {
        if ($registration->status !== 'pending') {
            return back()->with('error', 'Pendaftaran ini sudah diproses sebelumnya.');
        }

        return view('admin.registrations.approve', compact('registration'));
    }

    public function approve(Request $request, Registration $registration)
    {
        if ($registration->status !== 'pending') {
            return back()->with('error', 'Pendaftaran ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'student_id'   => 'required|string|max:20',
            'class'        => 'required|string|max:20',
            'generation'   => 'required|string|max:15',
            'phone_number' => 'required|string|max:15',
            'address'      => 'required|string',
        ]);

        DB::transaction(function () use ($validated, $registration) {
            Member::create([
                'user_id'            => $registration->user_id,
                'student_id'         => $validated['student_id'],
                'class'              => $validated['class'],
                'generation'         => $validated['generation'],
                'phone_number'       => $validated['phone_number'],
                'address'            => $validated['address'],
                'membership_status'  => 'active',
            ]);

            $registration->user->update(['role' => 'member']);
            $registration->update(['status' => 'accepted']);
        });

        return redirect()
            ->route('admin.registrations.index')
            ->with('success', 'Pendaftaran disetujui, akun member berhasil dibuat.');
    }

    public function reject(Registration $registration)
    {
        if ($registration->status !== 'pending') {
            return back()->with('error', 'Pendaftaran ini sudah diproses sebelumnya.');
        }

        $registration->update(['status' => 'rejected']);

        return redirect()
            ->route('admin.registrations.index')
            ->with('success', 'Pendaftaran ditolak.');
    }

    public function approveCancel(\App\Models\Registration $registration)
{
    if ($registration->status !== 'cancel_requested') {
        return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
    }

    $registration->update(['status' => 'rejected']);

    return back()->with('success', 'Pembatalan pendaftaran disetujui.');
    }

    public function rejectCancel(\App\Models\Registration $registration)
    {
        if ($registration->status !== 'cancel_requested') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $registration->update(['status' => 'pending']);

        return back()->with('success', 'Pengajuan pembatalan ditolak, pendaftaran tetap pending.');
    }

    public function startTraining(\App\Models\Registration $registration)
    {
        if ($registration->status !== 'pending') {
            return back()->with('error', 'Pendaftaran ini sudah diproses sebelumnya.');
        }

        $registration->update(['status' => 'training']);

        return back()->with('success', 'Status diubah menjadi Mengikuti Diklat.');
    }
}