@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Dashboard Admin</h3>

    @if (! $activePeriod)
        <div class="alert alert-warning">Belum ada periode aktif — statistik di bawah tidak dapat dihitung.</div>
    @endif

    <div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h2>{{ $overallAttendanceRate }}%</h2>
                <p class="text-muted mb-0">Kehadiran Keseluruhan</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h2>{{ $memberAttendanceRate }}%</h2>
                <p class="text-muted mb-0">Kehadiran Member ({{ $totalMembers }} orang)</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h2>{{ $candidateAttendanceRate }}%</h2>
                <p class="text-muted mb-0">Kehadiran Calon Anggota ({{ $totalCandidates }} orang)</p>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card text-center">
            <div class="card-body">
                <h2>{{ $pendingRegistrations + $pendingPermissions + $pendingMemberUnits }}</h2>
                <p class="text-muted mb-0">Total Menunggu Persetujuan</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-center">
            <div class="card-body">
                <h2>{{ count($membersBelowStandard) }}</h2>
                <p class="text-muted mb-0">Di Bawah Standar Kehadiran (Member + Calon Anggota)</p>
            </div>
        </div>
    </div>
</div>
    </div>

    @if (count($membersBelowStandard) > 0)
    <div class="card mb-4">
        <div class="card-header">Di Bawah 75% Kehadiran</div>
        <div class="card-body">
            <table class="table table-sm mb-0">
                <thead><tr><th>Nama</th><th>Tipe</th><th>Persentase Hadir</th></tr></thead>
                <tbody>
                    @foreach ($membersBelowStandard as $item)
                        <tr>
                            <td>{{ $item['name'] }}</td>
                            <td><span class="badge bg-secondary">{{ $item['type'] }}</span></td>
                            <td><span class="badge bg-danger">{{ $item['rate'] }}%</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between">
                    Pendaftaran Pending
                    <span class="badge bg-warning">{{ $pendingRegistrations }}</span>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($recentPendingRegistrations as $reg)
                        <li class="list-group-item">
                            <a href="{{ route('admin.registrations.show', $reg) }}">{{ $reg->full_name }}</a>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Tidak ada.</li>
                    @endforelse
                </ul>
                @if ($pendingRegistrations > 0)
                    <div class="card-footer">
                        <a href="{{ route('admin.registrations.index') }}" class="small">Lihat semua →</a>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between">
                    Izin Pending
                    <span class="badge bg-warning">{{ $pendingPermissions }}</span>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($recentPendingPermissions as $perm)
                        <li class="list-group-item">
                            <a href="{{ route('admin.permissions.show', $perm) }}">
                                {{ $perm->member->user->full_name ?? $perm->registration->full_name }} — {{ $perm->agenda->title }}
                            </a>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Tidak ada.</li>
                    @endforelse
                </ul>
                @if ($pendingPermissions > 0)
                    <div class="card-footer">
                        <a href="{{ route('admin.permissions.index') }}" class="small">Lihat semua →</a>
                    </div>
                @endif
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between">
                    Pengajuan Gabung Unit Pending
                    <span class="badge bg-warning">{{ $pendingMemberUnits }}</span>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($recentPendingMemberUnits as $mu)
                        <li class="list-group-item">
                            {{ $mu->member->user->full_name }} → {{ $mu->unit->name }}
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Tidak ada.</li>
                    @endforelse
                </ul>
                @if ($pendingMemberUnits > 0)
                    <div class="card-footer">
                        <a href="{{ route('admin.member-units.index') }}" class="small">Lihat semua →</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection