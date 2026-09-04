@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Selamat datang, {{ auth()->user()->full_name }}!</h3>

    <div class="col-md-4">
        <div class="stat-card-bar">
            <div class="stat-title">Status Pendaftaran</div>
            <div class="stat-value" style="font-size: 1.3rem;">
                @switch($registration->status ?? '-')
                    @case('pending') Pending @break
                    @case('training') Diklat @break
                    @case('accepted') Diterima @break
                    @case('rejected') Ditolak @break
                    @case('cancel_requested') Pembatalan @break
                    @default -
                @endswitch
            </div>
            <div class="stat-sub">
                @if (($registration->status ?? '') === 'training') Sedang Mengikuti @endif
            </div>
        </div>
    </div>
        <div class="col-md-4">
            <div class="stat-card-bar border-info">
                <div class="stat-title">Kehadiran Agenda</div>
                <div class="stat-value">{{ $attendanceRate }}%</div>
                <div class="stat-sub">Rekap Sementara</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-bar border-warning">
                <div class="stat-title">Izin Pending</div>
                <div class="stat-value">{{ $registration ? \App\Models\Permission::where('registration_id', $registration->id)->where('status', 'pending')->count() : 0 }}</div>
                <div class="stat-sub">Menunggu Approval</div>
            </div>
        </div>
    </div>

    @if ($registration && $registration->status === 'accepted')
        <div class="alert alert-success mt-3 mb-0">Selamat! Pendaftaran kamu telah diterima.</div>
    @elseif ($registration && $registration->status === 'rejected')
        <div class="alert alert-danger mt-3 mb-0">Mohon maaf, pendaftaran kamu tidak diterima.</div>
    @elseif ($registration && $registration->status === 'cancel_requested')
        <div class="alert alert-warning mt-3 mb-0">Pengajuan pembatalan sedang menunggu persetujuan admin.</div>
    @endif

    <div class="card mt-4">
        <div class="card-header">Agenda Terdekat</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Sesi</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($upcomingAgendas as $agenda)
                        <tr>
                            <td>{{ $agenda->title }}</td>
                            <td>{{ $agenda->date }}</td>
                            <td>{{ $agenda->time }}</td>
                            <td><span class="badge bg-info">Terjadwal</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">Tidak Tersedia</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($registration && $registration->status === 'pending')
        <form action="{{ route('registration.cancel') }}" method="POST" class="mt-3" onsubmit="return confirm('Yakin ajukan pembatalan pendaftaran?')">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm">Ajukan Pembatalan Pendaftaran</button>
        </form>
    @endif
</div>
@endsection