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
                            <form action="{{ route('attendances.store', $agenda) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Absen</button>
                            </form>

                            <a href="{{ route('permissions.create', $agenda) }}" class="btn btn-sm btn-warning">Ajukan Izin</a>
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