@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Selamat datang, {{ auth()->user()->full_name }}!</h3>

    @if ($membershipStatus === 'inactive')
        <div class="alert alert-secondary">
            <strong>Status keanggotaan Anda: Tidak Aktif.</strong>
            Anda tetap dapat masuk untuk melihat riwayat data, namun tidak dapat melakukan presensi, mengajukan izin, atau melihat agenda dari periode baru.
        </div>
    @elseif ($membershipStatus === 'pending_exit')
        <div class="alert alert-warning">
            <strong>Pengajuan keluar Anda sedang menunggu persetujuan admin.</strong>
            Selama belum disetujui, Anda tetap dapat beraktivitas normal seperti biasa.
        </div>
    @endif

    <div class="row mt-4 g-3">
        <div class="col-md-4">
            <div class="stat-card-bar">
                <div class="stat-title">Kehadiran Pribadi</div>
                <div class="stat-value">{{ $attendanceRate }}%</div>
                <div class="stat-sub">{{ $isRestricted ? 'Riwayat Periode' : 'Periode Aktif' }} ({{ $totalAgendas }} agenda)</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-bar border-warning">
                <div class="stat-title">Izin</div>
                <div class="stat-value">{{ $myPermissionsCount }}</div>
                <div class="stat-sub">{{ $myPermissionsPending }} menunggu approval</div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">Agenda Terkait</div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Agenda</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Tipe</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($relatedAgendas as $agenda)
                        <tr>
                            <td>{{ $agenda->title }}</td>
                            <td>{{ $agenda->date }}</td>
                            <td>{{ $agenda->time }}</td>
                            <td>{{ ucfirst($agenda->type) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">Tidak ada agenda terkait.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection