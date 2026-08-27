@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Kelola Unit</h3>
        <a href="{{ route('admin.units.create') }}" class="btn btn-primary">+ Tambah Unit</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Unit</th>
                <th>Deskripsi</th>
                <th>Pelatih</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($units as $unit)
                <tr>
                    <td>{{ $unit->name }}</td>
                    <td>{{ Str::limit($unit->description, 50) }}</td>
                    <td>{{ $unit->coach->name }}</td>
                    <td>
                        <a href="{{ route('admin.units.edit', $unit) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.units.destroy', $unit) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus unit ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">Belum ada data unit. Tambahkan Pelatih terlebih dahulu sebelum bisa menambah Unit.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $units->links() }}
</div>
@endsection