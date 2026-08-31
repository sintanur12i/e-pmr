@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Materi</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.materials.update', $material) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $material->title) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $material->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <input type="text" name="category" class="form-control" value="{{ old('category', $material->category) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Penyusun (Pelatih)</label>
            <select name="uploaded_by" class="form-select">
                @foreach ($coaches as $coach)
                    <option value="{{ $coach->id }}" {{ old('uploaded_by', $material->uploaded_by) == $coach->id ? 'selected' : '' }}>{{ $coach->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="date" class="form-control" value="{{ old('date', $material->date) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">File Materi (kosongkan jika tidak ingin ganti file)</label>
            <input type="file" name="file" class="form-control">
            <small class="text-muted">File saat ini: <a href="{{ Storage::url($material->file) }}" target="_blank">Lihat</a></small>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('admin.materials.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection