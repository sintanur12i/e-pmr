<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $managements = [];

        if (Auth::user()->member) {
            $managements = \App\Models\Management::where('member_id', Auth::user()->member->id)
                ->with('period')
                ->orderByDesc('period_id')
                ->get();
        }

        return view('profile.show', compact('managements'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'full_name'     => 'required|string|max:100',
            'email'         => 'required|email|max:100|unique:users,email,' . $user->id,
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
            'password'      => 'nullable|string|min:6|confirmed',
        ]);

        if ($request->hasFile('profile_photo')) {
            $validated['profile_photo'] = $request->file('profile_photo')->store('profile-photos', 'public');
        } else {
            unset($validated['profile_photo']);
        }

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('profile.show')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}