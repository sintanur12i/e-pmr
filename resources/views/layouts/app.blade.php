<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto"></ul>

                <ul class="navbar-nav ms-auto">
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->full_name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="d-flex">
        @auth
        <nav class="bg-dark text-white p-3" style="width: 220px; min-height: calc(100vh - 56px);">
            <ul class="nav flex-column">

                @if (auth()->user()->role === 'admin')
                    <li class="nav-item mb-1"><a href="{{ route('admin.dashboard') }}" class="nav-link text-white">Dashboard</a></li>
                    <li class="nav-item mb-1"><a href="{{ route('admin.periods.index') }}" class="nav-link text-white">Periode</a></li>
                    <li class="nav-item mb-1"><a href="{{ route('admin.registrations.index') }}" class="nav-link text-white">Pendaftaran</a></li>
                    <li class="nav-item mb-1"><a href="{{ route('admin.coaches.index') }}" class="nav-link text-white">Pelatih</a></li>
                    <li class="nav-item mb-1"><a href="{{ route('admin.units.index') }}" class="nav-link text-white">Unit</a></li>
                    <li class="nav-item mb-1"><a href="{{ route('admin.managements.index') }}" class="nav-link text-white">Kepengurusan</a></li>
                    <li class="nav-item mb-1"><a href="{{ route('admin.agendas.index') }}" class="nav-link text-white">Agenda</a></li>
                    <li class="nav-item mb-1"><a href="{{ route('admin.permissions.index') }}" class="nav-link text-white">Izin</a></li>
                    <li class="nav-item mb-1"><a href="{{ route('admin.member-units.index') }}" class="nav-link text-white">Pengajuan Unit</a></li>
                @elseif (auth()->user()->role === 'member')
                    <li class="nav-item mb-1"><a href="{{ route('member.dashboard') }}" class="nav-link text-white">Dashboard</a></li>
                    <li class="nav-item mb-1"><a href="{{ route('agendas.index') }}" class="nav-link text-white">Agenda</a></li>
                    <li class="nav-item mb-1"><a href="{{ route('member-units.index') }}" class="nav-link text-white">Gabung Unit</a></li>
                @elseif (auth()->user()->role === 'candidate_member')
                    <li class="nav-item mb-1"><a href="{{ route('candidate.dashboard') }}" class="nav-link text-white">Dashboard</a></li>
                    <li class="nav-item mb-1"><a href="{{ route('agendas.index') }}" class="nav-link text-white">Agenda</a></li>
                @endif

            </ul>
        </nav>
        @endauth

        <main class="flex-grow-1 py-4">
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
