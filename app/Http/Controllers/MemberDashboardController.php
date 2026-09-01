<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Agenda;
use App\Models\Period;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;

class MemberDashboardController extends Controller
{
    public function index()
    {
        $member = Auth::user()->member;
        $activePeriod = Period::where('status', 'active')->first();

        $attendanceRate = 0;
        $totalAgendas = 0;

        if ($activePeriod) {
            $totalAgendas = Agenda::where('period_id', $activePeriod->id)
                ->whereIn('target_role', ['all', 'member'])
                ->count();

            if ($totalAgendas > 0) {
                $attended = Attendance::where('member_id', $member->id)
                    ->whereHas('agenda', function ($q) use ($activePeriod) {
                        $q->where('period_id', $activePeriod->id)->whereIn('target_role', ['all', 'member']);
                    })->count();

                $attendanceRate = round(($attended / $totalAgendas) * 100, 1);
            }
        }

        $myPermissions = Permission::where('member_id', $member->id)
            ->with('agenda')
            ->latest()
            ->take(5)
            ->get();

        return view('member.dashboard', compact('attendanceRate', 'totalAgendas', 'myPermissions'));
    }
}