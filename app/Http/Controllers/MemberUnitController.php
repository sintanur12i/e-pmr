<?php

namespace App\Http\Controllers;

use App\Models\MemberUnit;
use App\Models\Period;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;

class MemberUnitController extends Controller
{
    public function index()
    {
        $member = Auth::user()->member;

        $units = Unit::with('coach')->get();
        $myApplications = MemberUnit::where('member_id', $member->id)
            ->with('unit')
            ->latest()
            ->get()
            ->keyBy('unit_id');

        return view('member_units.index', compact('units', 'myApplications'));
    }

    public function store(Unit $unit)
    {
        $member = Auth::user()->member;

        $activePeriod = Period::where('status', 'active')->first();

        if (! $activePeriod) {
            return back()->with('error', 'Belum ada periode aktif, tidak bisa mengajukan gabung unit.');
        }

        $alreadyApplied = MemberUnit::where('member_id', $member->id)
            ->where('unit_id', $unit->id)
            ->where('period_id', $activePeriod->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($alreadyApplied) {
            return back()->with('error', 'Anda sudah pernah mengajukan atau sudah tergabung di unit ini pada periode ini.');
        }

        MemberUnit::create([
            'member_id'         => $member->id,
            'unit_id'           => $unit->id,
            'period_id'         => $activePeriod->id,
            'status'            => 'pending',
            'application_date'  => now()->toDateString(),
        ]);

        return back()->with('success', 'Pengajuan gabung unit berhasil dikirim, menunggu persetujuan admin.');
    }
}