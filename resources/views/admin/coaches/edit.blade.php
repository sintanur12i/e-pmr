@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Pelatih</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.coaches.update', $coach) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $coach->name) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">No. HP</label>
            <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $coach->phone_number) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Spesialisasi</label>
            <input type="text" name="specialization" class="form-control" value="{{ old('specialization', $coach->specialization) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Asal</label>
            <select name="origin" class="form-select">
                <option value="internal" {{ old('origin', $coach->origin) === 'internal' ? 'selected' : '' }}>Internal</option>
                <option value="external" {{ old('origin', $coach->origin) === 'external' ? 'selected' : '' }}>External</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('admin.coaches.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection