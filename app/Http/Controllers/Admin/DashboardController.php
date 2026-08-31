<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Attendance;
use App\Models\Member;
use App\Models\MemberUnit;
use App\Models\Period;
use App\Models\Permission;
use App\Models\Registration;

class DashboardController extends Controller
{
    protected int $threshold = 75;

    public function index()
    {
        $activePeriod = Period::where('status', 'active')->first();

        $totalMembers = Member::where('membership_status', 'active')->count();
        $totalCandidates = $activePeriod
            ? Registration::where('period_id', $activePeriod->id)->where('status', 'pending')->count()
            : 0;

        $memberAgendaCount = 0;
        $candidateAgendaCount = 0;
        $memberAttendanceRate = 0;
        $candidateAttendanceRate = 0;
        $overallAttendanceRate = 0;

        if ($activePeriod) {
            $memberAgendaCount = Agenda::where('period_id', $activePeriod->id)
                ->whereIn('target_role', ['all', 'member'])
                ->count();

            $candidateAgendaCount = Agenda::where('period_id', $activePeriod->id)
                ->whereIn('target_role', ['all', 'candidate_member'])
                ->count();

            // Kehadiran Member
            if ($totalMembers > 0 && $memberAgendaCount > 0) {
                $memberActual = Attendance::whereNotNull('member_id')
                    ->whereHas('agenda', function ($q) use ($activePeriod) {
                        $q->where('period_id', $activePeriod->id)->whereIn('target_role', ['all', 'member']);
                    })->count();

                $memberAttendanceRate = round(($memberActual / ($totalMembers * $memberAgendaCount)) * 100, 1);
            }

            // Kehadiran Calon Anggota
            if ($totalCandidates > 0 && $candidateAgendaCount > 0) {
                $candidateActual = Attendance::whereNotNull('registration_id')
                    ->whereHas('agenda', function ($q) use ($activePeriod) {
                        $q->where('period_id', $activePeriod->id)->whereIn('target_role', ['all', 'candidate_member']);
                    })->count();

                $candidateAttendanceRate = round(($candidateActual / ($totalCandidates * $candidateAgendaCount)) * 100, 1);
            }

            // Kehadiran gabungan (Member + Calon Anggota)
            $totalPossible = ($totalMembers * $memberAgendaCount) + ($totalCandidates * $candidateAgendaCount);
            if ($totalPossible > 0) {
                $totalActual = Attendance::whereHas('agenda', fn ($q) => $q->where('period_id', $activePeriod->id))->count();
                $overallAttendanceRate = round(($totalActual / $totalPossible) * 100, 1);
            }
        }

        // Anggota di bawah standar
        $membersBelowStandard = [];
        if ($activePeriod && $memberAgendaCount > 0) {
            $members = Member::with('user')->where('membership_status', 'active')->get();

            foreach ($members as $member) {
                $attended = Attendance::where('member_id', $member->id)
                    ->whereHas('agenda', function ($q) use ($activePeriod) {
                        $q->where('period_id', $activePeriod->id)->whereIn('target_role', ['all', 'member']);
                    })->count();

                $rate = round(($attended / $memberAgendaCount) * 100, 1);

                if ($rate < $this->threshold) {
                    $membersBelowStandard[] = [
                        'name' => $member->user->full_name,
                        'rate' => $rate,
                        'type' => 'Member',
                    ];
                }
            }
        }

        // Calon Anggota di bawah standar
        if ($activePeriod && $candidateAgendaCount > 0) {
            $candidates = Registration::where('period_id', $activePeriod->id)->where('status', 'pending')->get();

            foreach ($candidates as $candidate) {
                $attended = Attendance::where('registration_id', $candidate->id)
                    ->whereHas('agenda', function ($q) use ($activePeriod) {
                        $q->where('period_id', $activePeriod->id)->whereIn('target_role', ['all', 'candidate_member']);
                    })->count();

                $rate = round(($attended / $candidateAgendaCount) * 100, 1);

                if ($rate < $this->threshold) {
                    $membersBelowStandard[] = [
                        'name' => $candidate->full_name,
                        'rate' => $rate,
                        'type' => 'Calon Anggota',
                    ];
                }
            }
        }

        $pendingRegistrations = Registration::where('status', 'pending')->count();
        $pendingPermissions = Permission::where('status', 'pending')->count();
        $pendingMemberUnits = MemberUnit::where('status', 'pending')->count();

        $recentPendingRegistrations = Registration::where('status', 'pending')->latest()->take(5)->get();
        $recentPendingPermissions = Permission::where('status', 'pending')->with(['agenda', 'member.user', 'registration'])->latest()->take(5)->get();
        $recentPendingMemberUnits = MemberUnit::where('status', 'pending')->with(['member.user', 'unit'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'activePeriod',
            'totalMembers',
            'totalCandidates',
            'overallAttendanceRate',
            'memberAttendanceRate',
            'candidateAttendanceRate',
            'membersBelowStandard',
            'pendingRegistrations',
            'pendingPermissions',
            'pendingMemberUnits',
            'recentPendingRegistrations',
            'recentPendingPermissions',
            'recentPendingMemberUnits'
        ));
    }
}