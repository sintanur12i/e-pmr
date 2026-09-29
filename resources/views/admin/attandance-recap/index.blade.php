@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h3 class="mb-0">Rekap Absensi Bulanan</h3>

        <form method="GET" action="{{ route('admin.attendance-recap.index') }}" class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.attendance-recap.index', ['month' => $monthStart->copy()->subMonth()->format('Y-m')]) }}" class="btn btn-outline-secondary btn-sm">&laquo;</a>
            <input type="month" name="month" value="{{ $monthStart->format('Y-m') }}" class="form-control form-control-sm" style="width: 170px;">
            <button type="submit" class="btn btn-primary btn-sm">Tampilkan</button>
            <a href="{{ route('admin.attendance-recap.index', ['month' => $monthStart->copy()->addMonth()->format('Y-m')]) }}" class="btn btn-outline-secondary btn-sm">&raquo;</a>
        </form>
    </div>

    <p class="text-muted">Periode tampilan: <strong>{{ $monthStart->copy()->locale('id')->translatedFormat('F Y') }}</strong></p>

    {{-- 1. Daftar kegiatan bulan ini --}}
    <div class="card mb-4">
        <div class="card-header">Kegiatan Bulan Ini ({{ $agendas->count() }})</div>
        <div class="table-responsive">
            <table class="table table-sm mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kegiatan</th>
                        <th>Tipe</th>
                        <th>Untuk</th>
                        <th>Periode</th>
                        <th class="text-center">Hadir Member</th>
                        <th class="text-center">Hadir Calon Anggota</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($agendas as $agenda)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($agenda->date)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($agenda->time)->format('H:i') }}</td>
                            <td>{{ $agenda->title }}</td>
                            <td>{{ ucfirst($agenda->type) }}</td>
                            <td>
                                @if ($agenda->target_role === 'all') Semua
                                @elseif ($agenda->target_role === 'member') Member
                                @else Calon Anggota
                                @endif
                            </td>
                            <td>{{ $agenda->period->name ?? '-' }}</td>
                            <td class="text-center">{{ $agendaStats[$agenda->id]['member'] ?? 0 }}</td>
                            <td class="text-center">{{ $agendaStats[$agenda->id]['candidate'] ?? 0 }}</td>
                            <td><a href="{{ route('admin.attendances.show', $agenda) }}" class="btn btn-sm btn-outline-info">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted">Tidak ada kegiatan pada bulan ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- 2. Matriks kehadiran per orang --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span>Kehadiran per Orang</span>
            <small class="text-muted">
                <span class="text-success fw-bold">✓</span> Hadir &nbsp;
                <span class="badge bg-info">I</span> Izin disetujui &nbsp;
                <span class="text-danger fw-bold">✗</span> Tidak hadir &nbsp;
                <span class="text-muted">·</span> Bukan untuknya &nbsp;
                (kosong) Belum berlangsung
            </small>
        </div>

        @if ($agendas->isEmpty())
            <div class="card-body text-center text-muted">Belum ada kegiatan pada bulan ini, jadi belum ada yang direkap.</div>
        @else
            <div class="table-responsive">
                <table class="table table-sm table-bordered mb-0 align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-start" style="min-width: 190px;">Nama</th>
                            <th>Tipe</th>
                            @foreach ($agendas as $agenda)
                                <th title="{{ $agenda->title }}" style="min-width: 62px;">
                                    {{ \Carbon\Carbon::parse($agenda->date)->format('d/m') }}
                                    <div class="small fw-normal text-truncate" style="max-width: 78px;">{{ $agenda->title }}</div>
                                </th>
                            @endforeach
                            <th>Hadir</th>
                            <th>%</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $row)
                            <tr>
                                <td class="text-start">{{ $row['name'] }}</td>
                                <td><span class="badge bg-secondary">{{ $row['type'] }}</span></td>
                                @foreach ($agendas as $agenda)
                                    <td>
                                        @switch($row['cells'][$agenda->id])
                                            @case('hadir') <span class="text-success fw-bold">✓</span> @break
                                            @case('izin') <span class="badge bg-info">I</span> @break
                                            @case('absen') <span class="text-danger fw-bold">✗</span> @break
                                            @case('na') <span class="text-muted">·</span> @break
                                            @default
                                        @endswitch
                                    </td>
                                @endforeach
                                <td>{{ $row['present'] }}/{{ $row['total'] }}</td>
                                <td>
                                    @if ($row['percent'] === null)
                                        <span class="text-muted">-</span>
                                    @else
                                        <span class="badge {{ $row['percent'] < $threshold ? 'bg-danger' : 'bg-success' }}">{{ $row['percent'] }}%</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="{{ count($agendas) + 4 }}" class="text-center text-muted">Belum ada anggota atau calon anggota yang bisa ditampilkan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer small text-muted">
                Persentase = jumlah hadir dibagi kegiatan yang berlaku untuk orang tersebut dan sudah berlangsung. Merah artinya di bawah {{ $threshold }}%.
            </div>
        @endif
    </div>
</div>
@endsection