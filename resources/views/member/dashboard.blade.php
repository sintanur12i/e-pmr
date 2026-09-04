@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Selamat datang, {{ auth()->user()->full_name }}!</h3>

    <div class="row mt-4 g-3">
        <div class="col-md-4">
            <div class="stat-card-bar">
                <div class="stat-title">Kehadiran Pribadi</div>
                <div class="stat-value">{{ $attendanceRate }}%</div>
                <div class="stat-sub">Periode Aktif ({{ $totalAgendas }} agenda)</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-bar border-warning">
                <div class="stat-title">Izin</div>
                <div class="stat-value">{{ $myPermissionsCount }}</div>
                <div class="stat-sub">{{ $myPermissionsPending }} menunggu approval</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card-bar border-info">
                <div class="stat-title">Pelatihan</div>
                <div class="stat-value">{{ $myTrainingsCount }}</div>
                <div class="stat-sub">Tersimpan</div>
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

    <div class="mt-3">
        <a href="{{ route('trainings.index') }}" class="btn btn-outline-primary btn-sm">Lihat Riwayat Pelatihan & Sertifikat Saya</a>
    </div>
</div>
@endsection