<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberExitController extends Controller
{
    public function create()
    {
        return view('member.exit-request');
    }

    public function store(Request $request)
    {
        $request->validate(['reason' => 'required|string']);

        $member = Auth::user()->member;
        $member->update(['membership_status' => 'pending_exit']);

        return redirect()
            ->route('profile.show')
            ->with('success', 'Pengajuan keluar telah dikirim, menunggu persetujuan admin.');
    }
}