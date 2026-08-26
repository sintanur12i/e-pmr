@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Kelola Periode</h3>
        <a href="{{ route('admin.periods.create') }}" class="btn btn-primary">+ Tambah Periode</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nama Periode</th>
                <th>Mulai</th>
                <th>Selesai</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($periods as $period)
                <tr>
                    <td>{{ $period->name }}</td>
                    <td>{{ $period->start_date }}</td>
                    <td>{{ $period->end_date }}</td>
                    <td>
                        <span class="badge {{ $period->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                            {{ $period->status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.periods.edit', $period) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.periods.destroy', $period) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus periode ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Belum ada data periode.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $periods->links() }}
</div>
@endsection