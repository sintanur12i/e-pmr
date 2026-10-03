@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Pengajuan Unit</h3>

    @php
        $filters = [
            'pending'        => 'Pending',
            'exit_requested' => 'Pengajuan Keluar',
            'approved'       => 'Disetujui',
            'rejected'       => 'Ditolak',
            'left'           => 'Sudah Keluar',
            'all'            => 'Semua',
        ];
        $statusLabels = [
            'pending'        => ['Menunggu persetujuan gabung', 'bg-warning text-dark'],
            'approved'       => ['Disetujui', 'bg-success'],
            'rejected'       => ['Ditolak', 'bg-danger'],
            'exit_requested' => ['Menunggu persetujuan keluar', 'bg-warning text-dark'],
            'left'           => ['Sudah keluar', 'bg-secondary'],
        ];
    @endphp

    <div class="mb-3 d-flex flex-wrap gap-2">
        @foreach ($filters as $key => $label)
            <a href="{{ route('admin.member-units.index', ['status' => $key]) }}"
               class="btn btn-sm {{ $status === $key ? 'btn-primary' : 'btn-outline-primary' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Nama Anggota</th>
                <th>Unit</th>
                <th>Periode</th>
                <th>Tgl Pengajuan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($memberUnits as $mu)
                @php $label = $statusLabels[$mu->status] ?? [ucfirst($mu->status), 'bg-secondary']; @endphp
                <tr>
                    <td>{{ $mu->member->user->full_name }}</td>
                    <td>{{ $mu->unit->name }}</td>
                    <td>{{ $mu->period->name }}</td>
                    <td>{{ $mu->application_date }}</td>
                    <td><span class="badge {{ $label[1] }}">{{ $label[0] }}</span></td>
                    <td>
                        <div class="d-flex gap-1">
                            @if ($mu->status === 'pending')
                                <form action="{{ route('admin.member-units.approve', $mu) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-success">Setujui</button>
                                </form>
                                <form action="{{ route('admin.member-units.reject', $mu) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-danger">Tolak</button>
                                </form>
                            @elseif ($mu->status === 'exit_requested')
                                <form action="{{ route('admin.member-units.approveExit', $mu) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-success">Setujui Keluar</button>
                                </form>
                                <form action="{{ route('admin.member-units.rejectExit', $mu) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-danger">Tolak</button>
                                </form>
                            @elseif ($mu->status === 'approved')
                                <form action="{{ route('admin.member-units.remove', $mu) }}" method="POST"
                                      onsubmit="return confirm('Keluarkan anggota ini dari unit?')">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-danger">Keluarkan</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $memberUnits->links() }}
</div>
@endsection