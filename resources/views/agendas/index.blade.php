@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Agenda Kegiatan</h3>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Tipe</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Lokasi</th>
                <th>Unit</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($agendas as $agenda)
                <tr>
                    <td>{{ $agenda->title }}</td>
                    <td>{{ ucfirst($agenda->type) }}</td>
                    <td>{{ $agenda->date }}</td>
                    <td>{{ $agenda->time }}</td>
                    <td>{{ $agenda->location }}</td>
                    <td>{{ $agenda->unit->name ?? '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Belum ada agenda.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $agendas->links() }}
</div>
@endsection