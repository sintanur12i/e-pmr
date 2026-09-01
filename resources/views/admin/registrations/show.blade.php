@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Detail Pendaftaran</h3>

    <table class="table table-bordered w-auto">
        <tr><th>Nama</th><td>{{ $registration->full_name }}</td></tr>
        <tr><th>Kelas</th><td>{{ $registration->class }}</td></tr>
        <tr><th>Alasan Bergabung</th><td>{{ $registration->join_reason }}</td></tr>
        <tr><th>Periode</th><td>{{ $registration->period->name }}</td></tr>
        <tr><th>Username</th><td>{{ $registration->user->username }}</td></tr>
        <tr><th>Email</th><td>{{ $registration->user->email }}</td></tr>
        <tr><th>Status</th><td>{{ ucfirst($registration->status) }}</td></tr>
    </table>

    @if ($registration->status === 'cancel_requested')
    <div class="alert alert-warning">Calon anggota ini mengajukan pembatalan pendaftaran.</div>

    <form action="{{ route('admin.registrations.approveCancel', $registration) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-success">Setujui Pembatalan</button>
    </form>

    <form action="{{ route('admin.registrations.rejectCancel', $registration) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-warning">Tolak (Tetap Pending)</button>
    </form>
    @endif

    @if ($registration->status === 'pending')
        <a href="{{ route('admin.registrations.approveForm', $registration) }}" class="btn btn-success">Approve</a>

        <form action="{{ route('admin.registrations.reject', $registration) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin tolak pendaftaran ini?')">
            @csrf
            <button type="submit" class="btn btn-danger">Reject</button>
        </form>
    @endif

    <a href="{{ route('admin.registrations.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection