@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Riwayat Pelatihan</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('trainings.update', $training) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Pelatihan</label>
            <input type="text" name="training_name" class="form-control" value="{{ old('training_name', $training->training_name) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Penyelenggara</label>
            <input type="text" name="organizer" class="form-control" value="{{ old('organizer', $training->organizer) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="date" class="form-control" value="{{ old('date', $training->date) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Sertifikat (kosongkan jika tidak ingin ganti)</label>
            <input type="file" name="certificate" class="form-control">
            <small class="text-muted">File saat ini: <a href="{{ Storage::url($training->certificate) }}" target="_blank">Lihat</a></small>
        </div>

        <div class="mb-3">
            <label class="form-label">Catatan</label>
            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $training->notes) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('trainings.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection