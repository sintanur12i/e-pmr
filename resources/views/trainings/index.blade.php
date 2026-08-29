@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Riwayat Pelatihan & Sertifikat Saya</h3>
        <a href="{{ route('trainings.create') }}" class="btn btn-primary">+ Tambah Riwayat</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Pelatihan</th>
                <th>Penyelenggara</th>
                <th>Tanggal</th>
                <th>Sertifikat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($trainings as $training)
                <tr>
                    <td>{{ $training->training_name }}</td>
                    <td>{{ $training->organizer }}</td>
                    <td>{{ $training->date }}</td>
                    <td><a href="{{ Storage::url($training->certificate) }}" target="_blank">Lihat</a></td>
                    <td>
                        <a href="{{ route('trainings.edit', $training) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('trainings.destroy', $training) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus riwayat ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Belum ada riwayat pelatihan.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $trainings->links() }}
</div>
@endsection