<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Attendance;
use App\Models\MemberUnit;
use App\Models\Permission;
use App\Models\User;
use App\Support\Notify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function create(Agenda $agenda)
    {
        if ($error = $this->guard(Auth::user(), $agenda)) {
            return redirect()->route('agendas.index')->with('error', $error);
        }

        return view('permissions.create', compact('agenda'));
    }

    public function store(Request $request, Agenda $agenda)
    {
        $user = Auth::user();

        if ($error = $this->guard($user, $agenda)) {
            return redirect()->route('agendas.index')->with('error', $error);
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

        Notify::admins(
            'Izin baru',
            $user->full_name . ' mengajukan izin untuk agenda "' . $agenda->title . '".',
            route('admin.permissions.index')
        );

        return redirect()
            ->route('agendas.index')
            ->with('success', 'Pengajuan izin berhasil dikirim, menunggu persetujuan admin.');
    }

    /** Pesan error kalau user tidak boleh mengajukan izin untuk agenda ini, null kalau boleh. */
    private function guard(User $user, Agenda $agenda): ?string
    {
        if ($user->role === 'member' && $user->member && $user->member->membership_status === 'inactive') {
            return 'Akun Anda sudah tidak aktif, tidak dapat mengajukan izin.';
        }

        if ($user->role === 'candidate_member' && $user->registration && in_array($user->registration->status, ['rejected', 'cancelled'])) {
            return 'Pendaftaran Anda sudah tidak aktif, tidak dapat mengajukan izin.';
        }

        if ($reason = $this->denyReason($user, $agenda)) {
            return $reason;
        }

        // Izin hanya bisa diajukan sampai hari-H (patokan jam WIB).
        if (Carbon::parse($agenda->date)->toDateString() < now('Asia/Jakarta')->toDateString()) {
            return 'Agenda ini sudah lewat, tidak dapat mengajukan izin.';
        }

        $ownerField = $user->role === 'candidate_member' ? 'registration_id' : 'member_id';
        $ownerId = $user->role === 'candidate_member' ? ($user->registration->id ?? null) : ($user->member->id ?? null);

        if ($ownerId && Attendance::where('agenda_id', $agenda->id)->where($ownerField, $ownerId)->exists()) {
            return 'Anda sudah melakukan presensi untuk agenda ini, tidak perlu mengajukan izin.';
        }

        return null;
    }

    /** Alasan user tidak berhak mengikuti agenda ini, atau null kalau berhak. */
    private function denyReason(User $user, Agenda $agenda): ?string
    {
        if (! in_array($agenda->target_role, ['all', $user->role], true)) {
            return 'Agenda ini tidak ditujukan untuk Anda.';
        }

        if ($user->role === 'candidate_member') {
            $registration = $user->registration;

            if (! $registration || (int) $registration->period_id !== (int) $agenda->period_id) {
                return 'Agenda ini bukan untuk periode pendaftaran Anda.';
            }
        }

        if ($user->role === 'member' && $agenda->type === 'unit') {
            $inUnit = $user->member
                && MemberUnit::where('member_id', $user->member->id)
                    ->where('unit_id', $agenda->unit_id)
                    ->where('status', 'approved')
                    ->exists();

            if (! $inUnit) {
                return 'Agenda ini khusus anggota unit yang bersangkutan.';
            }
        }

        return null;
    }
}