<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\MemberUnit;
use App\Models\Period;
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
                // Agenda tipe "unit" cuma muncul kalau user tergabung di unit itu.
                // Agenda tipe "general"/"training" selalu muncul (gak terikat unit manapun).
                $query->where('type', '!=', 'unit')
                      ->orWhereIn('unit_id', $myUnitIds);
            });

        // Member yang sudah tidak aktif (periode berakhir / keluar / dikeluarkan)
        // hanya bisa melihat riwayat agenda dari periode mereka sendiri,
        // tidak ikut melihat agenda dari periode yang lebih baru.
        if ($user->role === 'member' && $user->member && $user->member->membership_status !== 'active') {
            $myPeriod = Period::where('angkatan', $user->member->generation)->first();

            $query->when(
                $myPeriod,
                fn ($q) => $q->where('period_id', $myPeriod->id),
                fn ($q) => $q->whereRaw('1 = 0') // jaga-jaga kalau periode angkatannya sudah tidak ada
            );
        }

        $agendas = $query->orderBy('date')->orderBy('time')->paginate(10);

        return view('agendas.index', compact('agendas'));
    }
}