@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Tambah Foto Galeri</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.galleries.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Agenda Terkait (opsional)</label>
            <select name="agenda_id" class="form-select">
                <option value="">-- Tidak terkait agenda --</option>
                @foreach ($agendas as $agenda)
                    <option value="{{ $agenda->id }}" {{ old('agenda_id') == $agenda->id ? 'selected' : '' }}>{{ $agenda->title }} ({{ $agenda->date }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Foto (bisa pilih lebih dari satu)</label>
            <input type="file" name="photos[]" class="form-control" multiple accept="image/*">
            <small class="text-muted">Maks 2MB per foto. Tahan Ctrl (Windows) atau Cmd (Mac) untuk pilih banyak foto sekaligus.</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Caption</label>
            <input type="text" name="caption" class="form-control" value="{{ old('caption') }}">
            <small class="text-muted">Caption ini akan dipakai untuk semua foto yang diupload.</small>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.galleries.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection