<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MemberUnit;
use App\Support\Notify;
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

        $this->notifyMember($memberUnit, 'Pengajuan unit disetujui', 'Kamu resmi bergabung di unit %s.');

        return back()->with('success', 'Pengajuan disetujui.');
    }

    public function reject(MemberUnit $memberUnit)
    {
        $memberUnit->update([
            'status'        => 'rejected',
            'decision_date' => now()->toDateString(),
        ]);

        $this->notifyMember($memberUnit, 'Pengajuan unit ditolak', 'Pengajuan gabung unit %s ditolak.');

        return back()->with('success', 'Pengajuan ditolak.');
    }

    public function remove(MemberUnit $memberUnit)
    {
        $memberUnit->update(['status' => 'left']);

        $this->notifyMember($memberUnit, 'Dikeluarkan dari unit', 'Kamu dikeluarkan dari unit %s.');

        return back()->with('success', 'Anggota berhasil dikeluarkan dari unit.');
    }

    public function approveExit(MemberUnit $memberUnit)
    {
        $memberUnit->update(['status' => 'left']);

        $this->notifyMember($memberUnit, 'Keluar unit disetujui', 'Pengajuan keluar dari unit %s disetujui.');

        return back()->with('success', 'Pengajuan keluar unit disetujui.');
    }

    public function rejectExit(MemberUnit $memberUnit)
    {
        $memberUnit->update(['status' => 'approved']);

        $this->notifyMember($memberUnit, 'Keluar unit ditolak', 'Pengajuan keluar dari unit %s ditolak, kamu tetap di unit.');

        return back()->with('success', 'Pengajuan keluar unit ditolak, anggota tetap di unit.');
    }

    private function notifyMember(MemberUnit $memberUnit, string $title, string $messageFormat): void
    {
        $memberUnit->loadMissing(['member.user', 'unit']);

        Notify::user(
            $memberUnit->member->user,
            $title,
            sprintf($messageFormat, $memberUnit->unit->name),
            route('member-units.index')
        );
    }
}