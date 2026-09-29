<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use App\Models\Attendance;
use App\Models\Member;
use App\Models\MemberUnit;
use App\Models\Permission;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceRecapController extends Controller
{
    protected int $threshold = 75;

    public function index(Request $request)
    {
        $request->validate(['month' => 'nullable|date_format:Y-m']);

        $tz = 'Asia/Jakarta';
        $monthStart = $request->filled('month')
            ? Carbon::createFromFormat('Y-m', $request->query('month'), $tz)->startOfMonth()
            : now($tz)->startOfMonth();
        $monthEnd = $monthStart->copy()->endOfMonth();
        $today = now($tz)->toDateString();

        // Semua agenda pada bulan yang dipilih.
        $agendas = Agenda::with('period')
            ->whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        $agendaIds = $agendas->pluck('id');

        $attendances = Attendance::whereIn('agenda_id', $agendaIds)
            ->get(['agenda_id', 'member_id', 'registration_id']);

        $excuses = Permission::whereIn('agenda_id', $agendaIds)
            ->where('status', 'approved')
            ->get(['agenda_id', 'member_id', 'registration_id']);

        // Lookup cepat: [agenda_id]['m-{id}' atau 'r-{id}'] = true
        $attended = [];
        $agendaStats = [];
        foreach ($attendances as $a) {
            $key = $a->member_id ? 'm-' . $a->member_id : 'r-' . $a->registration_id;
            $attended[$a->agenda_id][$key] = true;

            $agendaStats[$a->agenda_id]['member'] = ($agendaStats[$a->agenda_id]['member'] ?? 0) + ($a->member_id ? 1 : 0);
            $agendaStats[$a->agenda_id]['candidate'] = ($agendaStats[$a->agenda_id]['candidate'] ?? 0) + ($a->member_id ? 0 : 1);
        }

        $excused = [];
        foreach ($excuses as $e) {
            $key = $e->member_id ? 'm-' . $e->member_id : 'r-' . $e->registration_id;
            $excused[$e->agenda_id][$key] = true;
        }

        // Anggota unit (untuk agenda bertipe unit).
        $unitIds = $agendas->where('type', 'unit')->pluck('unit_id')->filter()->unique();
        $unitMembers = [];
        MemberUnit::whereIn('unit_id', $unitIds)
            ->where('status', 'approved')
            ->get(['member_id', 'unit_id'])
            ->each(function ($mu) use (&$unitMembers) {
                $unitMembers[$mu->unit_id][$mu->member_id] = true;
            });

        // Siapa saja yang tampil di tabel: yang masih berjalan + siapa pun yang tercatat hadir bulan ini.
        $attendedMemberIds = $attendances->pluck('member_id')->filter()->unique();
        $attendedRegistrationIds = $attendances->pluck('registration_id')->filter()->unique();
        $agendaPeriodIds = $agendas->pluck('period_id')->unique();

        $members = Member::with('user')
            ->where(function ($q) use ($attendedMemberIds) {
                $q->whereIn('membership_status', ['active', 'pending_exit'])
                  ->orWhereIn('id', $attendedMemberIds);
            })
            ->get();

        $registrations = Registration::where(function ($q) use ($attendedRegistrationIds, $agendaPeriodIds) {
                $q->where(function ($qq) use ($agendaPeriodIds) {
                    $qq->whereIn('status', ['pending', 'training'])
                       ->whereIn('period_id', $agendaPeriodIds);
                })->orWhereIn('id', $attendedRegistrationIds);
            })
            ->get();

        $people = [];
        foreach ($members as $m) {
            $people[] = ['key' => 'm-' . $m->id, 'id' => $m->id, 'name' => $m->user->full_name ?? '-', 'type' => 'Member', 'period_id' => null];
        }
        foreach ($registrations as $r) {
            $people[] = ['key' => 'r-' . $r->id, 'id' => $r->id, 'name' => $r->full_name, 'type' => 'Calon Anggota', 'period_id' => $r->period_id];
        }

        usort($people, fn ($a, $b) => [$a['type'] === 'Member' ? 0 : 1, $a['name']] <=> [$b['type'] === 'Member' ? 0 : 1, $b['name']]);

        // Susun sel per orang per agenda.
        $rows = [];
        foreach ($people as $p) {
            $cells = [];
            $expectedPast = 0;
            $present = 0;

            foreach ($agendas as $agenda) {
                $agendaDate = Carbon::parse($agenda->date)->toDateString();

                if ($p['type'] === 'Member') {
                    $expected = in_array($agenda->target_role, ['all', 'member'], true)
                        && ($agenda->type !== 'unit' || isset($unitMembers[$agenda->unit_id][$p['id']]));
                } else {
                    $expected = in_array($agenda->target_role, ['all', 'candidate_member'], true)
                        && (int) $agenda->period_id === (int) $p['period_id'];
                }

                $hasAttended = isset($attended[$agenda->id][$p['key']]);
                $hasExcuse = isset($excused[$agenda->id][$p['key']]);

                if ($hasAttended) {
                    $state = 'hadir';
                } elseif ($hasExcuse) {
                    $state = 'izin';
                } elseif (! $expected) {
                    $state = 'na';
                } elseif ($agendaDate > $today) {
                    $state = 'upcoming';
                } else {
                    $state = 'absen';
                }

                if ($expected && $agendaDate <= $today) {
                    $expectedPast++;
                    if ($hasAttended) {
                        $present++;
                    }
                }

                $cells[$agenda->id] = $state;
            }

            $rows[] = $p + [
                'cells'   => $cells,
                'present' => $present,
                'total'   => $expectedPast,
                'percent' => $expectedPast > 0 ? round(($present / $expectedPast) * 100, 1) : null,
            ];
        }

        return view('admin.attendance-recap.index', [
            'monthStart'  => $monthStart,
            'agendas'     => $agendas,
            'agendaStats' => $agendaStats,
            'rows'        => $rows,
            'threshold'   => $this->threshold,
            'today'       => $today,
        ]);
    }
}