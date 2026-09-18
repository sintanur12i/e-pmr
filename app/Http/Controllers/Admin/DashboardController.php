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
        // Periode buat statistik Member: periode yang sedang berjalan/dilayani.
        $activePeriod = Period::where('status', 'active')->first();

        // Periode buat statistik Calon Anggota: periode yang sedang buka pendaftaran.
        // Bisa saja beda dari $activePeriod (misal saat masa transisi antar periode).
        $candidatePeriod = Period::where('registration_open', true)->first();

        $totalMembers = Member::where('membership_status', 'active')->count();
        $totalCandidates = $candidatePeriod
            ? Registration::where('period_id', $candidatePeriod->id)->whereIn('status', ['pending', 'training'])->count()
            : 0;

        $memberAgendaCount = 0;
        $candidateAgendaCount = 0;
        $memberAttendanceRate = 0;
        $candidateAttendanceRate = 0;

        if ($activePeriod) {
            $memberAgendaCount = Agenda::where('period_id', $activePeriod->id)
                ->whereIn('target_role', ['all', 'member'])
                ->count();

            // Kehadiran Member
            if ($totalMembers > 0 && $memberAgendaCount > 0) {
                $memberActual = Attendance::whereNotNull('member_id')
                    ->whereHas('agenda', function ($q) use ($activePeriod) {
                        $q->where('period_id', $activePeriod->id)->whereIn('target_role', ['all', 'member']);
                    })->count();

                $memberAttendanceRate = round(($memberActual / ($totalMembers * $memberAgendaCount)) * 100, 1);
            }
        }

        if ($candidatePeriod) {
            $candidateAgendaCount = Agenda::where('period_id', $candidatePeriod->id)
                ->whereIn('target_role', ['all', 'candidate_member'])
                ->count();

            // Kehadiran Calon Anggota
            if ($totalCandidates > 0 && $candidateAgendaCount > 0) {
                $candidateActual = Attendance::whereNotNull('registration_id')
                    ->whereHas('agenda', function ($q) use ($candidatePeriod) {
                        $q->where('period_id', $candidatePeriod->id)->whereIn('target_role', ['all', 'candidate_member']);
                    })->count();

                $candidateAttendanceRate = round(($candidateActual / ($totalCandidates * $candidateAgendaCount)) * 100, 1);
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
        if ($candidatePeriod && $candidateAgendaCount > 0) {
            $candidates = Registration::where('period_id', $candidatePeriod->id)->whereIn('status', ['pending', 'training'])->get();

            foreach ($candidates as $candidate) {
                $attended = Attendance::where('registration_id', $candidate->id)
                    ->whereHas('agenda', function ($q) use ($candidatePeriod) {
                        $q->where('period_id', $candidatePeriod->id)->whereIn('target_role', ['all', 'candidate_member']);
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