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

    <div class="row">
        @forelse ($materials as $material)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title mb-1">{{ $material->title }}</h6>
                        <p class="card-text small text-muted mb-2">{{ $material->date }}</p>

                        <div class="mt-auto d-flex justify-content-between align-items-center pt-2">
                            <a href="{{ Storage::url($material->file) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat File</a>
                            <div>
                                <a href="{{ route('admin.materials.edit', $material) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.materials.destroy', $material) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus materi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">Belum ada materi.</p>
        @endforelse
    </div>

    {{ $materials->links() }}
</div>
@endsection