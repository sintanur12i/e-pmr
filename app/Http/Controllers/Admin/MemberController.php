<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'active');

        $members = Member::with('user')
            ->when($status !== 'all', fn ($q) => $q->where('membership_status', $status))
            ->latest()
            ->paginate(10);

        return view('admin.members.index', compact('members', 'status'));
    }

    public function remove(Member $member)
    {
        $member->update(['membership_status' => 'inactive']);

        return back()->with('success', 'Anggota berhasil dikeluarkan.');
    }

    public function approveExit(Member $member)
    {
        $member->update(['membership_status' => 'inactive']);

        return back()->with('success', 'Pengajuan keluar disetujui.');
    }

    public function rejectExit(Member $member)
    {
        $member->update(['membership_status' => 'active']);

        return back()->with('success', 'Pengajuan keluar ditolak, anggota tetap aktif.');
    }
}