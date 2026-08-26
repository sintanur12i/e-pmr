@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Tambah Periode</h3>

    <form method="POST" action="{{ route('admin.periods.store') }}">
        @csrf
        @include('admin.periods._form')

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.periods.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection