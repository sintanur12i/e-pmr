<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function create(Agenda $agenda)
    {
        $user = Auth::user();

        if ($user->role === 'member' && $user->member && $user->member->membership_status !== 'active') {
            return redirect()
                ->route('agendas.index')
                ->with('error', 'Akun Anda sudah tidak aktif, tidak dapat mengajukan izin.');
        }

        if ($user->role === 'candidate_member' && $user->registration && in_array($user->registration->status, ['rejected', 'cancelled'])) {
            return redirect()
                ->route('agendas.index')
                ->with('error', 'Pendaftaran Anda sudah tidak aktif, tidak dapat mengajukan izin.');
        }

        return view('permissions.create', compact('agenda'));
    }

    public function store(Request $request, Agenda $agenda)
    {
        $user = Auth::user();

        if ($user->role === 'member' && $user->member && $user->member->membership_status !== 'active') {
            return redirect()
                ->route('agendas.index')
                ->with('error', 'Akun Anda sudah tidak aktif, tidak dapat mengajukan izin.');
        }

        if ($user->role === 'candidate_member' && $user->registration && in_array($user->registration->status, ['rejected', 'cancelled'])) {
            return redirect()
                ->route('agendas.index')
                ->with('error', 'Pendaftaran Anda sudah tidak aktif, tidak dapat mengajukan izin.');
        }

        $validated = $request->validate([
            'reason' => 'required|string',
            'proof'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $proofPath = null;
        if ($request->hasFile('proof')) {
            $proofPath = $request->file('proof')->store('permission-proofs', 'public');
        }

        Permission::create([
            'agenda_id'       => $agenda->id,
            'member_id'       => $user->role === 'member' ? $user->member->id : null,
            'registration_id' => $user->role === 'candidate_member' ? $user->registration->id : null,
            'reason'          => $validated['reason'],
            'proof'           => $proofPath,
            'status'          => 'pending',
        ]);

        return redirect()
            ->route('agendas.index')
            ->with('success', 'Pengajuan izin berhasil dikirim, menunggu persetujuan admin.');
    }
}