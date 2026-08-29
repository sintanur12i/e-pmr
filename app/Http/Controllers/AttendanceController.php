<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function store(Agenda $agenda)
    {
        $user = Auth::user();

        $alreadyAttended = Attendance::where('agenda_id', $agenda->id)
            ->where(function ($query) use ($user) {
                if ($user->role === 'candidate_member') {
                    $query->where('registration_id', $user->registration->id);
                } else {
                    $query->where('member_id', $user->member->id);
                }
            })
            ->exists();

        if ($alreadyAttended) {
            return back()->with('error', 'Anda sudah melakukan presensi untuk agenda ini.');
        }

        Attendance::create([
            'agenda_id'        => $agenda->id,
            'member_id'        => $user->role !== 'candidate_member' ? $user->member->id : null,
            'registration_id'  => $user->role === 'candidate_member' ? $user->registration->id : null,
            'status'           => 'present',
            'attendance_time'  => now(),
        ]);

        return back()->with('success', 'Presensi berhasil dicatat.');
    }
}