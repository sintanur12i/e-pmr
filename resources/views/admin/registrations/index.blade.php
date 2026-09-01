@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Daftar Pendaftaran</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('admin.registrations.index', ['status' => 'pending']) }}" class="btn btn-sm {{ $status === 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">Pending</a>
        <a href="{{ route('admin.registrations.index', ['status' => 'accepted']) }}" class="btn btn-sm {{ $status === 'accepted' ? 'btn-primary' : 'btn-outline-primary' }}">Diterima</a>
        <a href="{{ route('admin.registrations.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ $status === 'rejected' ? 'btn-primary' : 'btn-outline-primary' }}">Ditolak</a>
        <a href="{{ route('admin.registrations.index', ['status' => 'all']) }}" class="btn btn-sm {{ $status === 'all' ? 'btn-primary' : 'btn-outline-primary' }}">Semua</a>
        <a href="{{ route('admin.registrations.index', ['status' => 'cancel_requested']) }}" class="btn btn-sm {{ $status === 'cancel_requested' ? 'btn-primary' : 'btn-outline-primary' }}">Pengajuan Batal</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Periode</th>
                <th>Tanggal Daftar</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($registrations as $registration)
                <tr>
                    <td>{{ $registration->full_name }}</td>
                    <td>{{ $registration->class }}</td>
                    <td>{{ $registration->period->name }}</td>
                    <td>{{ $registration->registration_date }}</td>
                    <td>{{ ucfirst($registration->status) }}</td>
                    <td>
                        <a href="{{ route('admin.registrations.show', $registration) }}" class="btn btn-sm btn-info">Detail</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $registrations->links() }}
</div>
@endsection