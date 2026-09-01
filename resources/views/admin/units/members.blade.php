@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Anggota Unit: {{ $unit->name }}</h3>
    <p class="text-muted">Pelatih: {{ $unit->coach->name }}</p>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Tanggal Bergabung</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($members as $memberUnit)
                <tr>
                    <td>{{ $memberUnit->member->user->full_name }}</td>
                    <td>{{ $memberUnit->member->class }}</td>
                    <td>{{ $memberUnit->decision_date }}</td>
                    <td>
                        <span class="badge {{ $memberUnit->status === 'approved' ? 'bg-success' : 'bg-warning' }}">
                            {{ str_replace('_', ' ', ucfirst($memberUnit->status)) }}
                        </span>
                    </td>
                    <td>
                        @if ($memberUnit->status === 'approved')
                            <form action="{{ route('admin.member-units.remove', $memberUnit) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin keluarkan dari unit ini?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">Keluarkan</button>
                            </form>
                        @elseif ($memberUnit->status === 'exit_requested')
                            <form action="{{ route('admin.member-units.approveExit', $memberUnit) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Setujui Keluar</button>
                            </form>
                            <form action="{{ route('admin.member-units.rejectExit', $memberUnit) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning">Tolak</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Belum ada anggota di unit ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('admin.units.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection