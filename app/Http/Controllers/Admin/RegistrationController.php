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
        if (! in_array($registration->status, ['pending', 'training'])) {
            return back()->with('error', 'Pendaftaran ini sudah diproses sebelumnya.');
        }

        $registration->load('period');

        return view('admin.registrations.approve', compact('registration'));
    }

    public function approve(Registration $registration)
    {
        if (! in_array($registration->status, ['pending', 'training'])) {
            return back()->with('error', 'Pendaftaran ini sudah diproses sebelumnya.');
        }

        $registration->load('period');

        DB::transaction(function () use ($registration) {
            Member::create([
                'user_id'            => $registration->user_id,
                'student_id'         => $registration->student_id,
                'class'              => $registration->class,
                'generation'         => $registration->period->angkatan,
                'phone_number'       => $registration->phone_number,
                'address'            => $registration->address,
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
        if (! in_array($registration->status, ['pending', 'training'])) {
            return back()->with('error', 'Pendaftaran ini sudah diproses sebelumnya.');
        }

        $registration->update(['status' => 'rejected']);

        return redirect()
            ->route('admin.registrations.index')
            ->with('success', 'Pendaftaran ditolak.');
    }

    public function approveCancel(Registration $registration)
    {
        if ($registration->status !== 'cancel_requested') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $registration->update(['status' => 'rejected']);

        return back()->with('success', 'Pembatalan pendaftaran disetujui.');
    }

    public function rejectCancel(Registration $registration)
    {
        if ($registration->status !== 'cancel_requested') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $registration->update(['status' => 'pending']);

        return back()->with('success', 'Pengajuan pembatalan ditolak, pendaftaran tetap pending.');
    }

    public function startTraining(Registration $registration)
    {
        if ($registration->status !== 'pending') {
            return back()->with('error', 'Pendaftaran ini sudah diproses sebelumnya.');
        }

        $registration->update(['status' => 'training']);

        return back()->with('success', 'Status diubah menjadi Mengikuti Diklat.');
    }
}