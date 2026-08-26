@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Edit Periode</h3>

    <form method="POST" action="{{ route('admin.periods.update', $period) }}">
        @csrf
        @method('PUT')
        @include('admin.periods._form')

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('admin.periods.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection