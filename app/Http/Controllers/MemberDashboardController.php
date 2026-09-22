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
        $membershipStatus = $member->membership_status; // 'active' | 'pending_exit' | 'inactive'
        $isRestricted = $membershipStatus === 'inactive';

        // Member aktif ATAU yang masih menunggu persetujuan pengajuan keluar (pending_exit)
        // tetap dianggap "beroperasi normal" dan melihat statistik periode yang sedang berjalan.
        // Hanya yang BENAR-BENAR sudah inactive yang dialihkan ke statistik periode lamanya sendiri (riwayat).
        if (! $isRestricted) {
            $myPeriod = Period::where('status', 'active')->first();
        } else {
            $myPeriod = Period::where('angkatan', $member->generation)->first();
        }

        $attendanceRate = 0;
        $totalAgendas = 0;

        if ($myPeriod) {
            $totalAgendas = Agenda::where('period_id', $myPeriod->id)
                ->whereIn('target_role', ['all', 'member'])
                ->count();

            if ($totalAgendas > 0) {
                $attended = Attendance::where('member_id', $member->id)
                    ->whereHas('agenda', function ($q) use ($myPeriod) {
                        $q->where('period_id', $myPeriod->id)->whereIn('target_role', ['all', 'member']);
                    })->count();

                $attendanceRate = round(($attended / $totalAgendas) * 100, 1);
            }
        }

        $myPermissionsCount = Permission::where('member_id', $member->id)->count();
        $myPermissionsPending = Permission::where('member_id', $member->id)->where('status', 'pending')->count();

        $relatedAgendas = Agenda::with(['period', 'unit'])
            ->when($myPeriod, fn ($q) => $q->where('period_id', $myPeriod->id))
            ->whereIn('target_role', ['all', 'member'])
            ->orderBy('date')
            ->take(5)
            ->get();

        return view('member.dashboard', compact(
            'membershipStatus',
            'isRestricted',
            'attendanceRate',
            'totalAgendas',
            'myPermissionsCount',
            'myPermissionsPending',
            'relatedAgendas'
        ));
    }
}