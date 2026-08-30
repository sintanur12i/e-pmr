@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Profil Saya</h3>
        <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profil</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card" style="max-width: 500px;">
        <div class="card-body">
            @if (auth()->user()->profile_photo)
                <img src="{{ Storage::url(auth()->user()->profile_photo) }}" class="rounded-circle mb-3" width="100" height="100" style="object-fit: cover;">
            @endif

            <table class="table table-borderless">
                <tr><th>Nama Lengkap</th><td>{{ auth()->user()->full_name }}</td></tr>
                <tr><th>Username</th><td>{{ auth()->user()->username }}</td></tr>
                <tr><th>Email</th><td>{{ auth()->user()->email }}</td></tr>
                <tr><th>Role</th><td>{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</td></tr>
            </table>
        </div>
    </div>
</div>
@endsection