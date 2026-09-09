@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Tambah Periode</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.periods.store') }}">
        @csrf
        @include('admin.periods._form')

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.periods.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection