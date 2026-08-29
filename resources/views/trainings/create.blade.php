@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Tambah Riwayat Pelatihan</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('trainings.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama Pelatihan</label>
            <input type="text" name="training_name" class="form-control" value="{{ old('training_name') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Penyelenggara</label>
            <input type="text" name="organizer" class="form-control" value="{{ old('organizer') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="date" class="form-control" value="{{ old('date') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Sertifikat (foto/PDF)</label>
            <input type="file" name="certificate" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Catatan</label>
            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('trainings.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection