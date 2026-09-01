@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Selamat datang, {{ auth()->user()->full_name }}!</h3>

    @if ($registration)
        <div class="card mt-4" style="max-width: 500px;">
            <div class="card-body">
                <p class="mb-3"><strong>Status Pendaftaran:</strong></p>

                <div class="d-flex justify-content-between text-center">
                    <div class="{{ true ? 'text-primary fw-bold' : 'text-muted' }}">
                        1. Pending
                    </div>
                    <div class="{{ $hasAttendedTraining ? 'text-primary fw-bold' : 'text-muted' }}">
                        2. Mengikuti Diklat
                    </div>
                    <div class="{{ $registration->status !== 'pending' ? 'text-primary fw-bold' : 'text-muted' }}">
                        3. {{ $registration->status === 'rejected' ? 'Ditolak' : 'Diterima' }}
                    </div>
                </div>

                @if ($registration->status === 'accepted')
                    <div class="alert alert-success mt-3 mb-0">Selamat! Pendaftaran kamu telah diterima.</div>
                @elseif ($registration->status === 'rejected')
                    <div class="alert alert-danger mt-3 mb-0">Mohon maaf, pendaftaran kamu tidak diterima.</div>
                @endif
            </div>
        </div>
    @endif

    <div class="card mt-4" style="max-width: 500px;">
        <div class="card-header">Jadwal Diklat Terdekat</div>
        <div class="card-body">
            @if ($upcomingTraining)
                <p class="mb-1"><strong>{{ $upcomingTraining->title }}</strong></p>
                <p class="mb-1">{{ $upcomingTraining->date }} — {{ $upcomingTraining->time }}</p>
                <p class="mb-0 text-muted">{{ $upcomingTraining->location }}</p>
            @else
                <p class="text-muted mb-0">Belum ada jadwal diklat terdekat.</p>
            @endif
        </div>
    </div>
</div>
@endsection