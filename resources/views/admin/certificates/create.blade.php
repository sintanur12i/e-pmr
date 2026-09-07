@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Terbitkan Sertifikat</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.certificates.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Anggota</label>
            <select name="member_id" class="form-select">
                <option value="">-- Pilih Anggota --</option>
                @foreach ($members as $member)
                    <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                        {{ $member->user->full_name }} ({{ $member->student_id }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tipe Sertifikat</label>
            <select name="type" id="type" class="form-select" onchange="toggleUnitField()">
                <option value="period_completion" {{ old('type') === 'period_completion' ? 'selected' : '' }}>Selesai Masa Keanggotaan (Periode)</option>
                <option value="unit_completion" {{ old('type') === 'unit_completion' ? 'selected' : '' }}>Selesai Korp Khusus (per Angkatan/Unit)</option>
            </select>
        </div>

        <div class="mb-3" id="unit-field">
            <label class="form-label">Unit / Korp Khusus</label>
            <select name="unit_id" class="form-select">
                <option value="">-- Pilih Unit --</option>
                @foreach ($units as $unit)
                    <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Periode / Angkatan</label>
            <select name="period_id" class="form-select">
                <option value="">-- Pilih Periode --</option>
                @foreach ($periods as $period)
                    <option value="{{ $period->id }}" {{ old('period_id') == $period->id ? 'selected' : '' }}>{{ $period->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Judul Sertifikat</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="Contoh: Sertifikat Keanggotaan PMR Periode 2025/2026">
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal Terbit</label>
            <input type="date" name="issued_at" class="form-control" value="{{ old('issued_at') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">File Sertifikat (PDF/Gambar, maks 5MB)</label>
            <input type="file" name="file" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Terbitkan</button>
        <a href="{{ route('admin.certificates.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script>
    function toggleUnitField() {
        const type = document.getElementById('type').value;
        document.getElementById('unit-field').style.display = (type === 'unit_completion') ? 'block' : 'none';
    }
    document.addEventListener('DOMContentLoaded', toggleUnitField);
</script>
@endsection