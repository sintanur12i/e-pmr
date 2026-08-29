@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Ajukan Izin — {{ $agenda->title }}</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('permissions.store', $agenda) }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Alasan</label>
            <textarea name="reason" class="form-control" rows="3">{{ old('reason') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Bukti (opsional, foto/PDF, maks 2MB)</label>
            <input type="file" name="proof" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
        <a href="{{ route('agendas.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection