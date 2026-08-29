@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Bank Materi</h3>

    <div class="row">
        @forelse ($materials as $material)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $material->title }}</h5>
                        <p class="card-text small text-muted">{{ $material->category }} • {{ $material->uploader->name }}</p>
                        <p class="card-text">{{ Str::limit($material->description, 80) }}</p>
                        <a href="{{ Storage::url($material->file) }}" target="_blank" class="btn btn-sm btn-primary">Unduh / Lihat</a>
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