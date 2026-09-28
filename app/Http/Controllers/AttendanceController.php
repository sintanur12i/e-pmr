<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Attendance;
use App\Models\MemberUnit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    // Jendela presensi terhadap jam mulai agenda (bisa diubah di sini).
    public const OPEN_BEFORE_MINUTES = 60;   // dibuka 1 jam sebelum agenda mulai
    public const CLOSE_AFTER_MINUTES = 120;  // ditutup 2 jam setelah agenda mulai (toleransi telat)

    public function store(Agenda $agenda)
    {
        $user = Auth::user();

        if ($user->role === 'member' && $user->member && $user->member->membership_status === 'inactive') {
            return back()->with('error', 'Akun Anda sudah tidak aktif, tidak dapat melakukan presensi.');
        }

        if ($user->role === 'candidate_member' && $user->registration && in_array($user->registration->status, ['rejected', 'cancelled'])) {
            return back()->with('error', 'Pendaftaran Anda sudah tidak aktif, tidak dapat melakukan presensi.');
        }

        // Hak akses: target agenda, periode pendaftaran (calon anggota), keanggotaan unit (agenda unit).
        if ($reason = $this->denyReason($user, $agenda)) {
            return back()->with('error', $reason);
        }

        // Presensi hanya bisa dilakukan di dalam jendela waktu (patokan jam WIB).
        $startAt = Carbon::parse(Carbon::parse($agenda->date)->toDateString() . ' ' . $agenda->time, 'Asia/Jakarta');
        $opensAt = $startAt->copy()->subMinutes(self::OPEN_BEFORE_MINUTES);
        $closesAt = $startAt->copy()->addMinutes(self::CLOSE_AFTER_MINUTES);
        $now = now('Asia/Jakarta');

        if ($now->lt($opensAt)) {
            return back()->with('error', 'Presensi belum dibuka. Presensi dibuka pada ' . $opensAt->format('d/m/Y H:i') . ' WIB.');
        }

        if ($now->gt($closesAt)) {
            return back()->with('error', 'Presensi sudah ditutup pada ' . $closesAt->format('d/m/Y H:i') . ' WIB.');
        }

        $alreadyAttended = Attendance::where('agenda_id', $agenda->id)
            ->where(function ($query) use ($user) {
                if ($user->role === 'candidate_member') {
                    $query->where('registration_id', $user->registration->id);
                } else {
                    $query->where('member_id', $user->member->id);
                }
            })
            ->exists();

        if ($alreadyAttended) {
            return back()->with('error', 'Anda sudah melakukan presensi untuk agenda ini.');
        }

        Attendance::create([
            'agenda_id'        => $agenda->id,
            'member_id'        => $user->role !== 'candidate_member' ? $user->member->id : null,
            'registration_id'  => $user->role === 'candidate_member' ? $user->registration->id : null,
            'status'           => 'present',
            'attendance_time'  => now(),
        ]);

        return back()->with('success', 'Presensi berhasil dicatat.');
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