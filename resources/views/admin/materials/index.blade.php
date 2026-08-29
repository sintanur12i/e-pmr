@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Kelola Materi</h3>
        <a href="{{ route('admin.materials.create') }}" class="btn btn-primary">+ Tambah Materi</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Diupload Oleh</th>
                <th>Tanggal</th>
                <th>File</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($materials as $material)
                <tr>
                    <td>{{ $material->title }}</td>
                    <td>{{ $material->category }}</td>
                    <td>{{ $material->uploader->name }}</td>
                    <td>{{ $material->date }}</td>
                    <td><a href="{{ Storage::url($material->file) }}" target="_blank">Lihat File</a></td>
                    <td>
                        <a href="{{ route('admin.materials.edit', $material) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.materials.destroy', $material) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus materi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Belum ada materi.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $materials->links() }}
</div>
@endsection