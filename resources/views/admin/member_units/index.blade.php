@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Pengajuan Gabung Unit</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('admin.member-units.index', ['status' => 'pending']) }}" class="btn btn-sm {{ $status === 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">Pending</a>
        <a href="{{ route('admin.member-units.index', ['status' => 'approved']) }}" class="btn btn-sm {{ $status === 'approved' ? 'btn-primary' : 'btn-outline-primary' }}">Disetujui</a>
        <a href="{{ route('admin.member-units.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ $status === 'rejected' ? 'btn-primary' : 'btn-outline-primary' }}">Ditolak</a>
        <a href="{{ route('admin.member-units.index', ['status' => 'all']) }}" class="btn btn-sm {{ $status === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">Semua</a>
    </div>

    <table class="table table-bordered">
        <thead>
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
            @forelse ($memberUnits as $memberUnit)
                <tr>
                    <td>{{ $memberUnit->member->user->full_name }}</td>
                    <td>{{ $memberUnit->unit->name }}</td>
                    <td>{{ $memberUnit->period->name }}</td>
                    <td>{{ $memberUnit->application_date }}</td>
                    <td>{{ ucfirst($memberUnit->status) }}</td>
                    <td>
                        @if ($memberUnit->status === 'pending')
                            <form action="{{ route('admin.member-units.approve', $memberUnit) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                            </form>
                            <form action="{{ route('admin.member-units.reject', $memberUnit) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin tolak pengajuan ini?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                            </form>
                        @endif
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