@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Kelola Izin</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('admin.permissions.index', ['status' => 'pending']) }}" class="btn btn-sm {{ $status === 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">Pending</a>
        <a href="{{ route('admin.permissions.index', ['status' => 'approved']) }}" class="btn btn-sm {{ $status === 'approved' ? 'btn-primary' : 'btn-outline-primary' }}">Disetujui</a>
        <a href="{{ route('admin.permissions.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ $status === 'rejected' ? 'btn-primary' : 'btn-outline-primary' }}">Ditolak</a>
        <a href="{{ route('admin.permissions.index', ['status' => 'all']) }}" class="btn btn-sm {{ $status === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">Semua</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Agenda</th>
                <th>Alasan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($permissions as $permission)
                <tr>
                    <td>{{ $permission->member->user->full_name ?? $permission->registration->full_name }}</td>
                    <td>{{ $permission->agenda->title }}</td>
                    <td>{{ Str::limit($permission->reason, 50) }}</td>
                    <td>{{ ucfirst($permission->status) }}</td>
                    <td>
                        <a href="{{ route('admin.permissions.show', $permission) }}" class="btn btn-sm btn-info">Detail</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $permissions->links() }}
</div>
@endsection