@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Ajukan Keluar dari Organisasi</h3>
    <p class="text-muted">Pengajuan ini akan direview oleh admin sebelum status keanggotaan kamu benar-benar dinonaktifkan.</p>

    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('member.exit.store') }}" style="max-width: 500px;">
        @csrf
        <div class="mb-3">
            <label class="form-label">Alasan</label>
            <textarea name="reason" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin mengajukan keluar?')">Kirim Pengajuan</button>
    </form>
</div>
@endsection