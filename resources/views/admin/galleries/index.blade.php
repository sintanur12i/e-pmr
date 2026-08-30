@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Kelola Galeri</h3>
        <a href="{{ route('admin.galleries.create') }}" class="btn btn-primary">+ Tambah Foto</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @forelse ($galleries as $groupKey => $photos)
            @php
                $agendaTitle = $photos->first()->agenda->title ?? 'Tanpa Kegiatan Terkait';
                $modalId = 'modal-' . $groupKey;
                $carouselId = 'carousel-' . $groupKey;
            @endphp

            <div class="col-md-3 mb-4">
                <div class="card" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#{{ $modalId }}">
                    <img src="{{ Storage::url($photos->first()->photo) }}" class="card-img-top" style="height: 160px; object-fit: cover;">
                    <div class="card-body">
                        <h6 class="card-title mb-1">{{ $agendaTitle }}</h6>
                        <p class="card-text small text-muted mb-0">{{ $photos->count() }} foto</p>
                    </div>
                </div>
            </div>

            <!-- Modal berisi carousel, baru muncul kalau card di atas diklik -->
            <div class="modal fade" id="{{ $modalId }}" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content bg-dark">
                        <div class="modal-header border-0">
                            <h5 class="modal-title text-white">{{ $agendaTitle }}</h5>
                            <div>
                                @if ($groupKey !== 'tanpa_agenda')
                                    <form action="{{ route('admin.galleries.destroyByAgenda', $groupKey) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus SEMUA {{ $photos->count() }} foto pada kegiatan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus Semua Foto</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.galleries.destroyWithoutAgenda') }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus SEMUA {{ $photos->count() }} foto ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Hapus Semua Foto</button>
                                    </form>
                                @endif
                                <button type="button" class="btn-close btn-close-white ms-2" data-bs-dismiss="modal"></button>
                            </div>
                        </div>
                        <div class="modal-body p-0">
                            <div id="{{ $carouselId }}" class="carousel slide" data-bs-ride="false">
                                <div class="carousel-indicators">
                                    @foreach ($photos as $index => $photo)
                                        <button type="button" data-bs-target="#{{ $carouselId }}" data-bs-slide-to="{{ $index }}"
                                                class="{{ $index === 0 ? 'active' : '' }}"></button>
                                    @endforeach
                                </div>

                                <div class="carousel-inner" style="height: 500px;">
                                    @foreach ($photos as $index => $photo)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" style="height: 500px;">
                                            <img src="{{ Storage::url($photo->photo) }}" class="d-block w-100 h-100" style="object-fit: contain;">
                                            <div class="carousel-caption bg-dark bg-opacity-75 rounded p-2">
                                                <p class="mb-2">{{ $photo->caption }}</p>
                                                <form action="{{ route('admin.galleries.destroy', $photo) }}" method="POST" onsubmit="return confirm('Yakin hapus foto ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Hapus Foto Ini</button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button class="carousel-control-prev" type="button" data-bs-target="#{{ $carouselId }}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#{{ $carouselId }}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">Belum ada foto galeri.</p>
        @endforelse
    </div>
</div>
@endsection