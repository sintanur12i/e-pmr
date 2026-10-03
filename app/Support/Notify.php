<?php

namespace App\Support;

use App\Models\Agenda;
use App\Models\MemberUnit;
use App\Models\Period;
use App\Models\User;
use App\Notifications\AppNotification;
use Illuminate\Support\Facades\Notification;

class Notify
{
    /** Kirim ke semua admin. */
    public static function admins(string $title, string $message, ?string $url = null): void
    {
        Notification::send(
            User::where('role', 'admin')->get(),
            new AppNotification($title, $message, $url)
        );
    }

    /** Kirim ke satu user. */
    public static function user(?User $user, string $title, string $message, ?string $url = null): void
    {
        $user?->notify(new AppNotification($title, $message, $url));
    }

    /**
     * Kirim ke member/calon anggota sesuai target_role ('all', 'member', 'candidate_member').
     * TIDAK ada pengecekan periode/unit/status keanggotaan — hanya cocok dipakai
     * untuk notifikasi umum yang memang berlaku untuk semua orang di role itu.
     * Untuk notifikasi Agenda, pakai Notify::agenda() supaya konsisten dengan
     * siapa saja yang benar-benar berhak melihat agenda itu.
     */
    public static function target(string $targetRole, string $title, string $message, ?string $url = null): void
    {
        $roles = $targetRole === 'all' ? ['member', 'candidate_member'] : [$targetRole];

        Notification::send(
            User::whereIn('role', $roles)->get(),
            new AppNotification($title, $message, $url)
        );
    }

    /**
     * Kirim notifikasi agenda baru/diperbarui HANYA ke user yang benar-benar
     * berhak melihat agenda itu — logikanya disamakan dengan AgendaController (publik):
     * - Member: harus keanggotaannya masih aktif (bukan 'inactive'); kalau tipe agenda
     *   "unit", harus sudah tergabung (approved) di unit itu.
     * - Calon Anggota: periode pendaftarannya harus sama dengan periode agenda ini.
     */
    public static function agenda(Agenda $agenda, string $title, string $message, ?string $url = null): void
    {
        $recipients = collect();

        if (in_array($agenda->target_role, ['all', 'member'])) {
            $membersQuery = User::where('role', 'member')
                ->whereHas('member', function ($q) {
                    $q->where('membership_status', '!=', 'inactive');
                });

            if ($agenda->type === 'unit' && $agenda->unit_id) {
                $eligibleMemberIds = MemberUnit::where('unit_id', $agenda->unit_id)
                    ->where('status', 'approved')
                    ->pluck('member_id');

                $membersQuery->whereHas('member', function ($q) use ($eligibleMemberIds) {
                    $q->whereIn('id', $eligibleMemberIds);
                });
            }

            $recipients = $recipients->merge($membersQuery->get());
        }

        if (in_array($agenda->target_role, ['all', 'candidate_member'])) {
            $candidates = User::where('role', 'candidate_member')
                ->whereHas('registration', function ($q) use ($agenda) {
                    $q->where('period_id', $agenda->period_id);
                })
                ->get();

            $recipients = $recipients->merge($candidates);
        }

        $recipients = $recipients->unique('id');

        if ($recipients->isNotEmpty()) {
            Notification::send($recipients, new AppNotification($title, $message, $url));
        }
    }
}