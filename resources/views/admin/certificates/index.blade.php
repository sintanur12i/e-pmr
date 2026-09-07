@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Kelola Sertifikat</h3>
        <a href="{{ route('admin.certificates.create') }}" class="btn btn-primary">+ Terbitkan Sertifikat</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Anggota</th>
                    <th>Judul</th>
                    <th>Tipe</th>
                    <th>Periode</th>
                    <th>Unit</th>
                    <th>Tgl Terbit</th>
                    <th>File</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($certificates as $cert)
                    <tr>
                        <td>{{ $cert->member->user->full_name }}</td>
                        <td>{{ $cert->title }}</td>
                        <td>{{ $cert->type === 'period_completion' ? 'Keanggotaan' : 'Korp Khusus' }}</td>
                        <td>{{ $cert->period->name }}</td>
                        <td>{{ $cert->unit->name ?? '-' }}</td>
                        <td>{{ $cert->issued_at }}</td>
                        <td><a href="{{ Storage::url($cert->file) }}" target="_blank">Lihat</a></td>
                        <td>
                            <form action="{{ route('admin.certificates.destroy', $cert) }}" method="POST" onsubmit="return confirm('Yakin hapus sertifikat ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center">Belum ada sertifikat diterbitkan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $certificates->links() }}
</div>
@endsection