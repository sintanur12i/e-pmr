<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $agendas = Agenda::with(['period', 'unit', 'coach'])
            ->where(function ($query) use ($user) {
                $query->where('target_role', 'all')
                      ->orWhere('target_role', $user->role);
            })
            ->orderBy('date')
            ->orderBy('time')
            ->paginate(10);

        return view('agendas.index', compact('agendas'));
    }
}