@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Agenda</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.agendas.update', $agenda) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $agenda->title) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $agenda->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Tipe Agenda</label>
            <select name="type" class="form-select">
                <option value="general" {{ old('type', $agenda->type) === 'general' ? 'selected' : '' }}>Umum</option>
                <option value="unit" {{ old('type', $agenda->type) === 'unit' ? 'selected' : '' }}>Unit</option>
                <option value="training" {{ old('type', $agenda->type) === 'training' ? 'selected' : '' }}>Pelatihan</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Ditujukan Untuk</label>
            <select name="target_role" class="form-select">
                <option value="all" {{ old('target_role', $agenda->target_role) === 'all' ? 'selected' : '' }}>Semua (Member & Calon Anggota)</option>
                <option value="member" {{ old('target_role', $agenda->target_role) === 'member' ? 'selected' : '' }}>Khusus Member</option>
                <option value="candidate_member" {{ old('target_role', $agenda->target_role) === 'candidate_member' ? 'selected' : '' }}>Khusus Calon Anggota</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Unit (isi kalau tipe = Unit)</label>
            <select name="unit_id" class="form-select">
                <option value="">-- Tidak ada --</option>
                @foreach ($units as $unit)
                    <option value="{{ $unit->id }}" {{ old('unit_id', $agenda->unit_id) == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Pemateri / Pelatih (opsional)</label>
            <select name="coach_id" class="form-select">
                <option value="">-- Tidak ada --</option>
                @foreach ($coaches as $coach)
                    <option value="{{ $coach->id }}" {{ old('coach_id', $agenda->coach_id) == $coach->id ? 'selected' : '' }}>{{ $coach->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Periode</label>
            <select name="period_id" class="form-select">
                @foreach ($periods as $period)
                    <option value="{{ $period->id }}" {{ old('period_id', $agenda->period_id) == $period->id ? 'selected' : '' }}>{{ $period->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="date" class="form-control" value="{{ old('date', $agenda->date) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Waktu</label>
            <input type="time" name="time" class="form-control" value="{{ old('time', $agenda->time) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Lokasi</label>
            <input type="text" name="location" class="form-control" value="{{ old('location', $agenda->location) }}">
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('admin.agendas.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection