@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Lengkapi Data Anggota — {{ $registration->full_name }}</h3>

    <form method="POST" action="{{ route('admin.registrations.approve', $registration) }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">NIS / Student ID</label>
            <input type="text" name="student_id" class="form-control @error('student_id') is-invalid @enderror" value="{{ old('student_id') }}">
            @error('student_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Kelas</label>
            <input type="text" name="class" class="form-control @error('class') is-invalid @enderror" value="{{ old('class', $registration->class) }}">
            @error('class') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Angkatan</label>
            <input type="text" name="generation" class="form-control @error('generation') is-invalid @enderror" value="{{ old('generation') }}">
            @error('generation') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">No. HP</label>
            <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number') }}">
            @error('phone_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address') }}</textarea>
            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-success">Setujui & Buat Akun Member</button>
        <a href="{{ route('admin.registrations.show', $registration) }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection