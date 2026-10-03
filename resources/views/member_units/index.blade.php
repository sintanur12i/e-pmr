@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Gabung Unit</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @php
        $statusLabels = [
            'pending'        => ['Menunggu persetujuan', 'bg-warning text-dark'],
            'approved'       => ['Disetujui', 'bg-success'],
            'rejected'       => ['Ditolak', 'bg-danger'],
            'exit_requested' => ['Menunggu persetujuan keluar', 'bg-warning text-dark'],
            'left'           => ['Sudah keluar', 'bg-secondary'],
        ];
    @endphp

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Unit</th>
                <th>Deskripsi</th>
                <th>Pelatih</th>
                <th>Status Pengajuan Saya</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($units as $unit)
                @php
                    $application = $myApplications[$unit->id] ?? null;
                    $label = $application
                        ? ($statusLabels[$application->status] ?? [ucfirst($application->status), 'bg-secondary'])
                        : null;
                @endphp
                <tr>
                    <td>{{ $unit->name }}</td>
                    <td>{{ Str::limit($unit->description, 50) }}</td>
                    <td>{{ $unit->coach->name }}</td>
                    <td>
                        @if ($application)
                            <span class="badge {{ $label[1] }}">{{ $label[0] }}</span>
                        @else
                            <span class="text-muted">Belum mengajukan</span>
                        @endif
                    </td>
                    <td>
                        @if (! $application || in_array($application->status, ['rejected', 'left']))
                            <form action="{{ route('member-units.store', $unit) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary">Ajukan Gabung</button>
                            </form>
                        @elseif ($application->status === 'approved')
                            <form action="{{ route('member-units.requestExit', $unit) }}" method="POST"
                                  onsubmit="return confirm('Yakin ingin mengajukan keluar dari unit ini?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger">Ajukan Keluar</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Belum ada data unit.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection