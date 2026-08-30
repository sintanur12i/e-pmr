@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Profil</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" style="max-width: 500px;">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="full_name" class="form-control" value="{{ old('full_name', auth()->user()->full_name) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Foto Profil (kosongkan jika tidak ingin ganti)</label>
            <input type="file" name="profile_photo" class="form-control">
            @if (auth()->user()->profile_photo)
                <small class="text-muted">Foto saat ini: <a href="{{ Storage::url(auth()->user()->profile_photo) }}" target="_blank">Lihat</a></small>
            @endif
        </div>

        <hr>
        <p class="text-muted">Kosongkan bagian ini jika tidak ingin ganti password:</p>

        <div class="mb-3">
            <label class="form-label">Password Baru</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('profile.show') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection