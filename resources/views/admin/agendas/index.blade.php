@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Kelola Agenda</h3>
        <a href="{{ route('admin.agendas.create') }}" class="btn btn-primary">+ Tambah Agenda</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Tipe</th>
                <th>Ditujukan</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Lokasi</th>
                <th>Periode</th>
                <th>Unit</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($agendas as $agenda)
                <tr>
                    <td>{{ $agenda->title }}</td>
                    <td><span class="badge bg-info">{{ ucfirst($agenda->type) }}</span></td>
                    <td>{{ ucfirst(str_replace('_', ' ', $agenda->target_role)) }}</td>
                    <td>{{ $agenda->date }}</td>
                    <td>{{ $agenda->time }}</td>
                    <td>{{ $agenda->location }}</td>
                    <td>{{ $agenda->period->name }}</td>
                    <td>{{ $agenda->unit->name ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.agendas.edit', $agenda) }}" class="btn btn-sm btn-warning">Edit</a>
                        <a href="{{ route('admin.attendances.show', $agenda) }}" class="btn btn-sm btn-info">Rekap Absen</a>
                        <form action="{{ route('admin.agendas.destroy', $agenda) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus agenda ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center">Belum ada data agenda.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $agendas->links() }}
</div>
@endsection