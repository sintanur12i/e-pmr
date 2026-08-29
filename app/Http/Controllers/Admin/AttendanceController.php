<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;

class AttendanceController extends Controller
{
    public function show(Agenda $agenda)
    {
        $attendances = $agenda->attendances()->with(['member.user', 'registration'])->get();

        return view('admin.attendances.show', compact('agenda', 'attendances'));
    }
}