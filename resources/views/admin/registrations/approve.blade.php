@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Konfirmasi Penerimaan Anggota — {{ $registration->full_name }}</h3>
    <p class="text-muted">Periksa data berikut sebelum menyetujui pendaftaran ini.</p>

    <table class="table table-bordered w-auto">
        <tr><th>Nama</th><td>{{ $registration->full_name }}</td></tr>
        <tr><th>NIS / Student ID</th><td>{{ $registration->student_id }}</td></tr>
        <tr><th>Kelas</th><td>{{ $registration->class }}</td></tr>
        <tr><th>Angkatan</th><td>{{ $registration->period->angkatan }}</td></tr>
        <tr><th>No. HP</th><td>{{ $registration->phone_number }}</td></tr>
        <tr><th>Alamat</th><td>{{ $registration->address }}</td></tr>
    </table>

    <form method="POST" action="{{ route('admin.registrations.approve', $registration) }}">
        @csrf
        <button type="submit" class="btn btn-success">Setujui & Buat Akun Member</button>
        <a href="{{ route('admin.registrations.show', $registration) }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection