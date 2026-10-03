@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Notifikasi</h3>

        @if (auth()->user()->unreadNotifications()->count() > 0)
            <form action="{{ route('notifications.readAll') }}" method="POST" class="m-0">
                @csrf
                <button class="btn btn-sm btn-outline-primary">Tandai semua dibaca</button>
            </form>
        @endif
    </div>

    <div class="list-group mb-3">
        @forelse ($notifications as $n)
            <a href="{{ route('notifications.read', $n->id) }}"
               class="list-group-item list-group-item-action {{ $n->read_at ? '' : 'bg-light fw-semibold' }}">
                <div>{{ $n->data['title'] }}</div>
                <div class="small text-muted">{{ $n->data['message'] }}</div>
                <div class="small text-muted">{{ $n->created_at->diffForHumans() }}</div>
            </a>
        @empty
            <p class="text-center">Belum ada notifikasi.</p>
        @endforelse
    </div>

    {{ $notifications->links() }}
</div>
@endsection