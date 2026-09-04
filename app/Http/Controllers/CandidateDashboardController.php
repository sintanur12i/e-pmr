<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;

class CandidateDashboardController extends Controller
{
    public function index()
    {
        $registration = Auth::user()->registration;
        $attendanceRate = 0;

        if ($registration) {
            $totalAgendas = Agenda::where('period_id', $registration->period_id)
                ->whereIn('target_role', ['all', 'candidate_member'])
                ->count();

            if ($totalAgendas > 0) {
                $attended = Attendance::where('registration_id', $registration->id)
                    ->whereHas('agenda', function ($q) use ($registration) {
                        $q->where('period_id', $registration->period_id)->whereIn('target_role', ['all', 'candidate_member']);
                    })->count();

                $attendanceRate = round(($attended / $totalAgendas) * 100, 1);
            }
        }

        $upcomingAgendas = Agenda::with('unit')
            ->whereIn('target_role', ['all', 'candidate_member'])
            ->where('date', '>=', now()->toDateString())
            ->orderBy('date')
            ->orderBy('time')
            ->take(5)
            ->get();

        return view('candidate.dashboard', compact('registration', 'attendanceRate', 'upcomingAgendas'));
    }
}