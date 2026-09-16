<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Management;
use App\Models\Member;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodController extends Controller
{
    public function index()
    {
        $periods = Period::latest()->paginate(10);

        return view('admin.periods.index', compact('periods'));
    }

    public function create()
    {
        return view('admin.periods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:50',
            'angkatan'   => 'required|string|max:20',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'status'     => 'required|in:active,inactive',
        ], [
            'end_date.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);

        $validated['registration_open'] = $request->boolean('registration_open');

        DB::transaction(function () use ($validated) {
            if ($validated['registration_open']) {
                // Cuma 1 periode yang boleh buka pendaftaran dalam satu waktu.
                Period::where('registration_open', true)->update(['registration_open' => false]);
            }

            Period::create($validated);
        });

        return redirect()
            ->route('admin.periods.index')
            ->with('success', 'Periode berhasil ditambahkan.');
    }

    public function edit(Period $period)
    {
        return view('admin.periods.edit', compact('period'));
    }

    public function update(Request $request, Period $period)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:50',
            'angkatan'   => 'required|string|max:20',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'status'     => 'required|in:active,inactive',
        ], [
            'end_date.after_or_equal' => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
        ]);

        $validated['registration_open'] = $request->boolean('registration_open');

        DB::transaction(function () use ($validated, $period) {
            $wasActive = $period->status === 'active';   // cek status SEBELUM diupdate

            if ($validated['registration_open']) {
                // Cuma 1 periode yang boleh buka pendaftaran dalam satu waktu.
                Period::where('id', '!=', $period->id)
                    ->where('registration_open', true)
                    ->update(['registration_open' => false]);
            }

            $period->update($validated);                  // baru update periode-nya

            if ($wasActive && $validated['status'] === 'inactive') {
                // Nonaktifkan semua member dengan angkatan yang sama
                Member::where('generation', $period->angkatan)
                    ->where('membership_status', 'active')
                    ->update(['membership_status' => 'inactive']);

                // Nonaktifkan semua jabatan kepengurusan di periode ini
                Management::where('period_id', $period->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }
        });

        return redirect()
            ->route('admin.periods.index')
            ->with('success', 'Periode berhasil diperbarui.');
    }

    public function destroy(Period $period)
    {
        $period->delete();

        return redirect()
            ->route('admin.periods.index')
            ->with('success', 'Periode berhasil dihapus.');
    }
}