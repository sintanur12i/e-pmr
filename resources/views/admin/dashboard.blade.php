@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dashboard Admin</h1>
    <p>Selamat datang, {{ Auth::user()->full_name }}!</p>
</div>
@endsection