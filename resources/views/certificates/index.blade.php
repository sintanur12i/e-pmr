@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Sertifikat Saya</h3>

    <div class="row">
        @forelse ($certificates as $cert)
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">{{ $cert->title }}</h6>
                        <p class="card-text small text-muted mb-1">
                            {{ $cert->type === 'period_completion' ? 'Keanggotaan' : 'Korp Khusus' }}
                            @if ($cert->unit) — {{ $cert->unit->name }} @endif
                        </p>
                        <p class="card-text small text-muted">Periode: {{ $cert->period->name }}</p>
                        <a href="{{ Storage::url($cert->file) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat / Unduh</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">Belum ada sertifikat yang diterbitkan untukmu.</p>
        @endforelse
    </div>
</div>
@endsection