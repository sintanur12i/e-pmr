<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\MemberUnit;
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

        $agendas = Agenda::with(['period', 'unit', 'coach'])
            ->where(function ($query) use ($user) {
                $query->where('target_role', 'all')
                      ->orWhere('target_role', $user->role);
            })
            ->where(function ($query) use ($myUnitIds) {
                // Agenda tipe "unit" cuma muncul kalau user tergabung di unit itu.
                // Agenda tipe "general"/"training" selalu muncul (gak terikat unit manapun).
                $query->where('type', '!=', 'unit')
                      ->orWhereIn('unit_id', $myUnitIds);
            })
            ->orderBy('date')
            ->orderBy('time')
            ->paginate(10);

        return view('agendas.index', compact('agendas'));
    }
}