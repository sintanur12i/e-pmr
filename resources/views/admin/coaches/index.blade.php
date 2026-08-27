@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Kelola Pelatih</h3>
        <a href="{{ route('admin.coaches.create') }}" class="btn btn-primary">+ Tambah Pelatih</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>No. HP</th>
                <th>Spesialisasi</th>
                <th>Asal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($coaches as $coach)
                <tr>
                    <td>{{ $coach->name }}</td>
                    <td>{{ $coach->phone_number }}</td>
                    <td>{{ $coach->specialization }}</td>
                    <td>{{ ucfirst($coach->origin) }}</td>
                    <td>
                        <a href="{{ route('admin.coaches.edit', $coach) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.coaches.destroy', $coach) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus pelatih ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Belum ada data pelatih.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $coaches->links() }}
</div>
@endsection