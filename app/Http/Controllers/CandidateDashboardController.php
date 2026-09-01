<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Support\Facades\Auth;

class CandidateDashboardController extends Controller
{
    public function index()
    {
        $registration = Auth::user()->registration;

        // "Mengikuti diklat" dianggap true kalau calon anggota ini sudah pernah absen di agenda tipe training
        $hasAttendedTraining = false;
        if ($registration) {
            $hasAttendedTraining = \App\Models\Attendance::where('registration_id', $registration->id)
                ->whereHas('agenda', fn ($q) => $q->where('type', 'training'))
                ->exists();
        }

        $upcomingTraining = Agenda::where('type', 'training')
            ->whereIn('target_role', ['all', 'candidate_member'])
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('time')
            ->first();

        return view('candidate.dashboard', compact('registration', 'hasAttendedTraining', 'upcomingTraining'));
    }
}