@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Formulir Pendaftaran Anggota PMR SMK N 2 Purbalingga</div>
                <div class="card-body">

                    @if (! $activePeriod)
                        <div class="alert alert-warning">Pendaftaran sedang ditutup, belum ada periode aktif.</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kelas</label>
                            <input type="text" name="class" class="form-control" value="{{ old('class') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" value="{{ old('username') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alasan Ingin Bergabung</label>
                            <textarea name="join_reason" class="form-control" rows="3">{{ old('join_reason') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" {{ $activePeriod ? '' : 'disabled' }}>
                            Daftar
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection