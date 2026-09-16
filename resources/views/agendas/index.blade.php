@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Agenda Kegiatan</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if (auth()->user()->role === 'member' && auth()->user()->member && auth()->user()->member->membership_status !== 'active')
        <div class="alert alert-secondary">
            <strong>Status keanggotaan Anda: Tidak Aktif.</strong>
            Daftar di bawah hanya menampilkan riwayat agenda dari periode keanggotaan Anda sendiri.
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Tipe</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Lokasi</th>
                <th>Unit</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($agendas as $agenda)
                <tr>
                    <td>{{ $agenda->title }}</td>
                    <td>{{ ucfirst($agenda->type) }}</td>
                    <td>{{ $agenda->date }}</td>
                    <td>{{ $agenda->time }}</td>
                    <td>{{ $agenda->location }}</td>
                    <td>{{ $agenda->unit->name ?? '-' }}</td>
                    <td>
                        @php
                            $isRestrictedMember = auth()->user()->role === 'member'
                                && auth()->user()->member
                                && auth()->user()->member->membership_status !== 'active';
                        @endphp

                        @if ($isRestrictedMember)
                            <span class="text-muted">Riwayat</span>
                        @elseif (in_array(auth()->user()->role, ['member', 'candidate_member']))
                            @if ($agenda->already_attended)
                                <span class="badge bg-success">✓ Sudah Hadir</span>
                            @elseif ($agenda->permission_status === 'pending')
                                <span class="badge bg-warning text-dark">Izin Diajukan (Pending)</span>
                            @elseif ($agenda->permission_status === 'approved')
                                <span class="badge bg-info">Izin Disetujui</span>
                            @elseif ($agenda->permission_status === 'rejected')
                                {{-- Izin ditolak, tetap boleh coba absen manual --}}
                                <form action="{{ route('attendances.store', $agenda) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Absen</button>
                                </form>
                                <span class="badge bg-danger">Izin Ditolak</span>
                            @else
                                <form action="{{ route('attendances.store', $agenda) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Absen</button>
                                </form>

                                <a href="{{ route('permissions.create', $agenda) }}" class="btn btn-sm btn-warning">Ajukan Izin</a>
                            @endif
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center">Belum ada agenda.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $agendas->links() }}
</div>
@endsection