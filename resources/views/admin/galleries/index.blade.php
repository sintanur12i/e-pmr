@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Kelola Galeri</h3>
        <a href="{{ route('admin.galleries.create') }}" class="btn btn-primary">+ Tambah Foto</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @forelse ($galleries as $gallery)
            <div class="col-md-3 mb-3">
                <div class="card">
                    <img src="{{ Storage::url($gallery->photo) }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                    <div class="card-body">
                        <p class="card-text small">{{ $gallery->caption }}</p>
                        <p class="card-text small text-muted">{{ $gallery->agenda->title ?? '-' }}</p>
                        <form action="{{ route('admin.galleries.destroy', $gallery) }}" method="POST" onsubmit="return confirm('Yakin hapus foto ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">Belum ada foto galeri.</p>
        @endforelse
    </div>

    {{ $galleries->links() }}
</div>
@endsection