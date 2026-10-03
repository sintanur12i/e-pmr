<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Attendance;
use App\Models\MemberUnit;
use App\Models\Period;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $myUnitIds = [];
        if ($user->role === 'member' && $user->member) {
            $myUnitIds = MemberUnit::where('member_id', $user->member->id)
                ->where('status', 'approved')
                ->pluck('unit_id')
                ->toArray();
        }

        $query = Agenda::with(['period', 'unit', 'coach'])
            ->where(function ($query) use ($user) {
                $query->where('target_role', 'all')
                      ->orWhere('target_role', $user->role);
            })
            ->where(function ($query) use ($myUnitIds) {
                $query->where('type', '!=', 'unit')
                      ->orWhereIn('unit_id', $myUnitIds);
            });

        // Hanya member yang BENAR-BENAR sudah tidak aktif (periode berakhir / dikeluarkan /
        // pengajuan keluar SUDAH disetujui admin) yang dibatasi. Selama masih 'pending_exit'
        // (menunggu keputusan admin), member tetap bisa beraktivitas normal.
        if ($user->role === 'member' && $user->member && $user->member->membership_status === 'inactive') {
            $myPeriod = Period::where('angkatan', $user->member->generation)->first();

            $query->when(
                $myPeriod,
                fn ($q) => $q->where('period_id', $myPeriod->id),
                fn ($q) => $q->whereRaw('1 = 0')
            );
        }

        if ($user->role === 'candidate_member') {
            $query->when(
                $user->registration,
                fn ($q) => $q->where('period_id', $user->registration->period_id),
                fn ($q) => $q->whereRaw('1 = 0')
            );
        }

        $agendas = $query->orderBy('date')->orderBy('time')->paginate(10);

        if (in_array($user->role, ['member', 'candidate_member'])) {
            $ownerField = $user->role === 'candidate_member' ? 'registration_id' : 'member_id';
            $ownerId = $user->role === 'candidate_member' ? ($user->registration->id ?? null) : ($user->member->id ?? null);

            foreach ($agendas as $agenda) {
                $agenda->already_attended = $ownerId
                    ? Attendance::where('agenda_id', $agenda->id)->where($ownerField, $ownerId)->exists()
                    : false;

                $agenda->permission_status = $ownerId
                    ? Permission::where('agenda_id', $agenda->id)->where($ownerField, $ownerId)->latest()->value('status')
                    : null;
            }
        }

        return view('agendas.index', compact('agendas'));
    }
}