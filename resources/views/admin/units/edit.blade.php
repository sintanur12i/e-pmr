@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Unit</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.units.update', $unit) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Unit</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $unit->name) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $unit->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Pelatih Penanggung Jawab</label>
            <select name="coach_id" class="form-select">
                @foreach ($coaches as $coach)
                    <option value="{{ $coach->id }}" {{ old('coach_id', $unit->coach_id) == $coach->id ? 'selected' : '' }}>
                        {{ $coach->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('admin.units.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection