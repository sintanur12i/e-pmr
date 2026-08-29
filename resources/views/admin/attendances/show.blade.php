@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Rekap Presensi — {{ $agenda->title }}</h3>
    <p>{{ $agenda->date }} — {{ $agenda->time }} — {{ $agenda->location }}</p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Status Akun</th>
                <th>Waktu Absen</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($attendances as $attendance)
                <tr>
                    <td>
                        {{ $attendance->member->user->full_name ?? ($attendance->registration->full_name ?? '-') }}
                    </td>
                    <td>{{ $attendance->member ? 'Member' : 'Calon Anggota' }}</td>
                    <td>{{ $attendance->attendance_time }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center">Belum ada yang absen.</td></tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('admin.agendas.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection