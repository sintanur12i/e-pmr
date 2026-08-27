@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Jabatan</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.managements.update', $management) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Anggota</label>
            <select name="member_id" class="form-select">
                @foreach ($members as $member)
                    <option value="{{ $member->id }}" {{ old('member_id', $management->member_id) == $member->id ? 'selected' : '' }}>
                        {{ $member->user->full_name }} ({{ $member->student_id }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Periode</label>
            <select name="period_id" class="form-select">
                @foreach ($periods as $period)
                    <option value="{{ $period->id }}" {{ old('period_id', $management->period_id) == $period->id ? 'selected' : '' }}>
                        {{ $period->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Jabatan</label>
            <input type="text" name="position" class="form-control" value="{{ old('position', $management->position) }}">
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" {{ old('is_active', $management->is_active) ? 'checked' : '' }}>
            <label for="is_active" class="form-check-label">Jabatan Aktif</label>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('admin.managements.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection