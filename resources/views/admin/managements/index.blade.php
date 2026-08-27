@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Kelola Jabatan (Kepengurusan)</h3>
        <a href="{{ route('admin.managements.create') }}" class="btn btn-primary">+ Tambah Jabatan</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Anggota</th>
                <th>Jabatan</th>
                <th>Periode</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($managements as $management)
                <tr>
                    <td>{{ $management->member->user->full_name }}</td>
                    <td>{{ $management->position }}</td>
                    <td>{{ $management->period->name }}</td>
                    <td>
                        <span class="badge {{ $management->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $management->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.managements.edit', $management) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.managements.destroy', $management) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data jabatan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Belum ada data jabatan. Pastikan sudah ada data Anggota (lewat Alur Pendaftaran) sebelum menambah jabatan.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $managements->links() }}
</div>
@endsection