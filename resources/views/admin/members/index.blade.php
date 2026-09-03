@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Kelola Anggota</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('admin.members.index', ['status' => 'active']) }}" class="btn btn-sm {{ $status === 'active' ? 'btn-primary' : 'btn-outline-primary' }}">Aktif</a>
        <a href="{{ route('admin.members.index', ['status' => 'pending_exit']) }}" class="btn btn-sm {{ $status === 'pending_exit' ? 'btn-primary' : 'btn-outline-primary' }}">Pengajuan Keluar</a>
        <a href="{{ route('admin.members.index', ['status' => 'inactive']) }}" class="btn btn-sm {{ $status === 'inactive' ? 'btn-primary' : 'btn-outline-primary' }}">Tidak Aktif</a>
        <a href="{{ route('admin.members.index', ['status' => 'all']) }}" class="btn btn-sm {{ $status === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">Semua</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 60px;"></th>
                <th>Nama</th>
                <th>NIS</th>
                <th>Kelas</th>
                <th>Angkatan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($members as $member)
                <tr>
                    <td>
                        @if ($member->user->profile_photo)
                            <img src="{{ Storage::url($member->user->profile_photo) }}" class="table-avatar" alt="Foto">
                        @else
                            <div class="table-avatar-fallback">{{ strtoupper(substr($member->user->full_name, 0, 1)) }}</div>
                        @endif
                    </td>
                    <td>{{ $member->user->full_name }}</td>
                    <td>{{ $member->student_id }}</td>
                    <td>{{ $member->class }}</td>
                    <td>{{ $member->generation }}</td>
                    <td>
                        <span class="badge {{ $member->membership_status === 'active' ? 'bg-success' : ($member->membership_status === 'pending_exit' ? 'bg-warning' : 'bg-secondary') }}">
                            {{ str_replace('_', ' ', ucfirst($member->membership_status)) }}
                        </span>
                    </td>
                    <td>
                        @if ($member->membership_status === 'active')
                            <form action="{{ route('admin.members.remove', $member) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin keluarkan anggota ini?')">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">Keluarkan</button>
                            </form>
                        @elseif ($member->membership_status === 'pending_exit')
                            <form action="{{ route('admin.members.approveExit', $member) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Setujui Keluar</button>
                            </form>
                            <form action="{{ route('admin.members.rejectExit', $member) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning">Tolak</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $members->links() }}
</div>
@endsection