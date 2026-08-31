@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Selamat datang, {{ auth()->user()->full_name }}!</h3>
    <p class="text-muted">Gunakan menu di samping untuk melihat agenda, mengajukan izin, atau mengelola profil kamu.</p>
</div>
@endsection