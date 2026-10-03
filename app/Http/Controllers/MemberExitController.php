<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Support\Notify;
use Illuminate\Support\Facades\Auth;

class MemberExitController extends Controller
{
    public function create()
    {
        return view('member.exit-request');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $member = Auth::user()->member;

        if ($member->membership_status !== 'active') {
            return back()->with('error', 'Pengajuan keluar hanya dapat dikirim oleh anggota aktif.');
        }

            $member->update([
        'membership_status' => 'pending_exit',
        'exit_reason'       => $validated['reason'],
        'exit_requested_at' => now(),
    ]);

    Notify::admins(
        'Pengajuan keluar PMR',
        Auth::user()->full_name . ' mengajukan keluar dari PMR.',
        route('admin.members.index', ['status' => 'pending_exit'])
    );

    return redirect()
        ->route('profile.show')
        ->with('success', 'Pengajuan keluar telah dikirim, menunggu persetujuan admin.');
    }
}