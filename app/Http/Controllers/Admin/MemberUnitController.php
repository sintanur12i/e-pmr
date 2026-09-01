<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberUnit;
use Illuminate\Http\Request;

class MemberUnitController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');

        $memberUnits = MemberUnit::with(['member.user', 'unit', 'period'])
            ->when($status !== 'all', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(10);

        return view('admin.member_units.index', compact('memberUnits', 'status'));
    }

    public function approve(MemberUnit $memberUnit)
    {
        $memberUnit->update([
            'status'        => 'approved',
            'decision_date' => now()->toDateString(),
        ]);

        return back()->with('success', 'Pengajuan disetujui.');
    }

    public function reject(MemberUnit $memberUnit)
    {
        $memberUnit->update([
            'status'        => 'rejected',
            'decision_date' => now()->toDateString(),
        ]);

        return back()->with('success', 'Pengajuan ditolak.');
    }

    public function remove(\App\Models\MemberUnit $memberUnit)
    {
        $memberUnit->update(['status' => 'left']);

        return back()->with('success', 'Anggota berhasil dikeluarkan dari unit.');
    }

    public function approveExit(\App\Models\MemberUnit $memberUnit)
    {
        $memberUnit->update(['status' => 'left']);

        return back()->with('success', 'Pengajuan keluar unit disetujui.');
    }

    public function rejectExit(\App\Models\MemberUnit $memberUnit)
    {
        $memberUnit->update(['status' => 'approved']);

        return back()->with('success', 'Pengajuan keluar unit ditolak, anggota tetap di unit.');
    }
}