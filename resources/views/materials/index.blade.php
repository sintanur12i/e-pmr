@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Bank Materi</h3>

    <div class="row">
        @forelse ($materials as $material)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title mb-1">{{ $material->title }}</h6>
                        <p class="card-text small text-muted mb-2">{{ $material->date }}</p>
                        <a href="{{ Storage::url($material->file) }}" target="_blank" class="btn btn-sm btn-primary mt-auto">Unduh / Lihat</a>
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