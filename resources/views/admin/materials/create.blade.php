@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Tambah Materi</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.materials.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="date" class="form-control" value="{{ old('date') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">File Materi (PDF/DOC/PPT, maks 5MB)</label>
            <input type="file" name="file" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.materials.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection