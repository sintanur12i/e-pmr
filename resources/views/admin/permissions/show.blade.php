@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Detail Izin</h3>

    <table class="table table-bordered w-auto">
        <tr><th>Nama</th><td>{{ $permission->member->user->full_name ?? $permission->registration->full_name }}</td></tr>
        <tr><th>Status</th><td>{{ $permission->member ? 'Member' : 'Calon Anggota' }}</td></tr>
        <tr><th>Agenda</th><td>{{ $permission->agenda->title }}</td></tr>
        <tr><th>Alasan</th><td>{{ $permission->reason }}</td></tr>
        <tr>
            <th>Bukti</th>
            <td>
                @if ($permission->proof)
                    <a href="{{ Storage::url($permission->proof) }}" target="_blank">Lihat Bukti</a>
                @else
                    Tidak ada
                @endif
            </td>
        </tr>
        <tr><th>Status</th><td>{{ ucfirst($permission->status) }}</td></tr>
    </table>

    @if ($permission->status === 'pending')
        <form action="{{ route('admin.permissions.approve', $permission) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success">Approve</button>
        </form>

        <form action="{{ route('admin.permissions.reject', $permission) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin tolak izin ini?')">
            @csrf
            <button type="submit" class="btn btn-danger">Reject</button>
        </form>
    @endif

    <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection