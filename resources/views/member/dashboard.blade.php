@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Selamat datang, {{ auth()->user()->full_name }}!</h3>

    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h2>{{ $attendanceRate }}%</h2>
                    <p class="text-muted mb-0">Persentase Kehadiran Saya ({{ $totalAgendas }} agenda periode ini)</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">Riwayat Izin Saya (5 terbaru)</div>
        <ul class="list-group list-group-flush">
            @forelse ($myPermissions as $permission)
                <li class="list-group-item d-flex justify-content-between">
                    <span>{{ $permission->agenda->title }}</span>
                    <span class="badge {{ $permission->status === 'approved' ? 'bg-success' : ($permission->status === 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                        {{ ucfirst($permission->status) }}
                    </span>
                </li>
            @empty
                <li class="list-group-item text-muted">Belum pernah mengajukan izin.</li>
            @endforelse
        </ul>
    </div>

    <div class="mt-4">
        <a href="{{ route('trainings.index') }}" class="btn btn-outline-primary">Lihat Riwayat Pelatihan & Sertifikat Saya</a>
    </div>
</div>
@endsection