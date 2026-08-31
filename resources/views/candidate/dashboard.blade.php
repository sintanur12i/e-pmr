@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Selamat datang, {{ auth()->user()->full_name }}!</h3>

    @php
        $registration = auth()->user()->registration;
    @endphp

    @if ($registration)
        <div class="card" style="max-width: 400px;">
            <div class="card-body">
                <p class="mb-1"><strong>Status Pendaftaran:</strong></p>
                <span class="badge {{ $registration->status === 'pending' ? 'bg-warning' : ($registration->status === 'accepted' ? 'bg-success' : 'bg-danger') }}">
                    {{ ucfirst($registration->status) }}
                </span>
            </div>
        </div>
    @endif
</div>
@endsection