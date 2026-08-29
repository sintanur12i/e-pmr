@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Galeri Kegiatan</h3>

    <div class="row">
        @forelse ($galleries as $gallery)
            <div class="col-md-3 mb-3">
                <div class="card">
                    <img src="{{ Storage::url($gallery->photo) }}" class="card-img-top" style="height: 150px; object-fit: cover;">
                    <div class="card-body">
                        <p class="card-text small">{{ $gallery->caption }}</p>
                        <p class="card-text small text-muted">{{ $gallery->agenda->title ?? '-' }}</p>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">Belum ada foto.</p>
        @endforelse
    </div>

    {{ $galleries->links() }}
</div>
@endsection