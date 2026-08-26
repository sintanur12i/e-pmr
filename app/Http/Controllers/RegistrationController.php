<?php

namespace App\Http\Controllers;

use App\Models\Period;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    public function create()
    {
        $activePeriod = Period::where('status', 'active')->first();

        return view('register', compact('activePeriod'));
    }

    public function store(Request $request)
    {
        $activePeriod = Period::where('status', 'active')->first();

        if (! $activePeriod) {
            return back()->withErrors(['period' => 'Pendaftaran sedang ditutup, belum ada periode aktif.']);
        }

        $validated = $request->validate([
            'username'     => 'required|string|max:50|unique:users,username',
            'email'        => 'required|email|max:100|unique:users,email',
            'password'     => 'required|string|min:6|confirmed',
            'full_name'    => 'required|string|max:100',
            'class'        => 'required|string|max:50',
            'join_reason'  => 'required|string',
        ]);

        DB::transaction(function () use ($validated, $activePeriod) {
            $user = User::create([
                'username'  => $validated['username'],
                'email'     => $validated['email'],
                'password'  => bcrypt($validated['password']),
                'full_name' => $validated['full_name'],
                'role'      => 'candidate_member',
                'is_active' => true,
            ]);

            Registration::create([
                'user_id'           => $user->id,
                'full_name'         => $validated['full_name'],
                'class'             => $validated['class'],
                'join_reason'       => $validated['join_reason'],
                'period_id'         => $activePeriod->id,
                'status'            => 'pending',
                'registration_date' => now()->toDateString(),
            ]);
        });

        return redirect()
            ->route('login')
            ->with('success', 'Pendaftaran berhasil dikirim. Silakan login dan tunggu konfirmasi admin.');
    }
}