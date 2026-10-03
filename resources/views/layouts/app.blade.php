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
    <div id="app" class="d-flex flex-column vh-100">
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm flex-shrink-0">
        <div class="container-fluid">
            <span class="navbar-brand" href="{{ url('/') }}">
                <span class="pmr-logo">+</span>
                <span>
                    E-PMR
                    <small>SMK N 2 Purbalingga</small>
                </span>
            </span>
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
                        @php
        $unreadCount = auth()->user()->unreadNotifications()->count();
        $latestNotifications = auth()->user()->notifications()->limit(6)->get();
    @endphp
    <li class="nav-item dropdown me-2">
        <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown">
            🔔
            @if ($unreadCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                </span>
            @endif
        </a>
        <div class="dropdown-menu dropdown-menu-end p-0" style="width: 320px; max-height: 420px; overflow-y: auto;">
            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                <strong>Notifikasi</strong>
                @if ($unreadCount > 0)
                    <form action="{{ route('notifications.readAll') }}" method="POST" class="m-0">
                        @csrf
                        <button class="btn btn-link btn-sm p-0">Tandai semua dibaca</button>
                    </form>
                @endif
            </div>

            @forelse ($latestNotifications as $n)
                <a href="{{ route('notifications.read', $n->id) }}"
                class="dropdown-item px-3 py-2 {{ $n->read_at ? '' : 'bg-light fw-semibold' }}"
                style="white-space: normal;">
                    <div class="small">{{ $n->data['title'] }}</div>
                    <div class="small text-muted">{{ $n->data['message'] }}</div>
                    <div class="small text-muted">{{ $n->created_at->diffForHumans() }}</div>
                </a>
            @empty
                <div class="px-3 py-3 text-center text-muted small">Belum ada notifikasi.</div>
            @endforelse

            <a href="{{ route('notifications.index') }}" class="dropdown-item text-center small border-top py-2">Lihat semua</a>
        </div>
    </li>
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

    <div class="d-flex flex-grow-1 overflow-hidden">
        @auth
        <nav class="bg-dark text-white p-3 sidebar-pmr flex-shrink-0" style="width: 220px; overflow-y: auto;">

    <div class="sidebar-user">
        @if (auth()->user()->profile_photo)
            <img src="{{ Storage::url(auth()->user()->profile_photo) }}" class="sidebar-user-avatar-img" alt="Foto Profil">
        @else
            <div class="sidebar-user-avatar">{{ strtoupper(substr(auth()->user()->full_name, 0, 1)) }}</div>
        @endif
        <div>
            <div class="sidebar-user-name">{{ auth()->user()->full_name }}</div>
            <div class="sidebar-user-role">{{ str_replace('_', ' ', auth()->user()->role) }}</div>
        </div>
    </div>

    <ul class="nav flex-column">

        @if (auth()->user()->role === 'admin')
            <li class="nav-item mb-1"><a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><span class="nav-icon">🏠</span> Dashboard</a></li>
            <li class="nav-item mb-1"><a href="{{ route('admin.periods.index') }}" class="nav-link {{ request()->routeIs('admin.periods.*') ? 'active' : '' }}"><span class="nav-icon">🗓️</span> Periode</a></li>
            <li class="nav-item mb-1"><a href="{{ route('admin.registrations.index') }}" class="nav-link {{ request()->routeIs('admin.registrations.*') ? 'active' : '' }}"><span class="nav-icon">📝</span> Pendaftaran</a></li>
            <li class="nav-item mb-1"><a href="{{ route('admin.coaches.index') }}" class="nav-link {{ request()->routeIs('admin.coaches.*') ? 'active' : '' }}"><span class="nav-icon">🧑‍🏫</span> Pelatih</a></li>
            <li class="nav-item mb-1"><a href="{{ route('admin.units.index') }}" class="nav-link {{ request()->routeIs('admin.units.*') ? 'active' : '' }}"><span class="nav-icon">🧩</span> Unit</a></li>
            <li class="nav-item mb-1"><a href="{{ route('admin.managements.index') }}" class="nav-link {{ request()->routeIs('admin.managements.*') ? 'active' : '' }}"><span class="nav-icon">👥</span> Kepengurusan</a></li>
            <li class="nav-item mb-1"><a href="{{ route('admin.agendas.index') }}" class="nav-link {{ request()->routeIs('admin.agendas.*') ? 'active' : '' }}"><span class="nav-icon">📌</span> Agenda</a></li>
            <li class="nav-item mb-1"><a href="{{ route('admin.permissions.index') }}" class="nav-link {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}"><span class="nav-icon">📄</span> Izin</a></li>
            <li class="nav-item mb-1"><a href="{{ route('admin.attendance-recap.index') }}" class="nav-link {{ request()->routeIs('admin.attendance-recap.*') ? 'active' : '' }}"><span class="nav-icon">📊</span> Rekap Absensi</a></li>
            <li class="nav-item mb-1"><a href="{{ route('admin.member-units.index') }}" class="nav-link {{ request()->routeIs('admin.member-units.*') ? 'active' : '' }}"><span class="nav-icon">🔁</span> Pengajuan Unit</a></li>
            <li class="nav-item mb-1"><a href="{{ route('admin.materials.index') }}" class="nav-link {{ request()->routeIs('admin.materials.*') ? 'active' : '' }}"><span class="nav-icon">📚</span> Materi</a></li>
            <li class="nav-item mb-1"><a href="{{ route('admin.galleries.index') }}" class="nav-link {{ request()->routeIs('admin.galleries.*') ? 'active' : '' }}"><span class="nav-icon">🖼️</span> Galeri</a></li>
            <li class="nav-item mb-1"><a href="{{ route('admin.certificates.index') }}" class="nav-link">🎖️ Kelola Sertifikat</a></li>
            <li class="nav-item mb-1"><a href="{{ route('admin.members.index') }}" class="nav-link {{ request()->routeIs('admin.members.*') ? 'active' : '' }}"><span class="nav-icon">🗂️</span> Kelola Anggota</a></li>
            <li class="nav-item mb-1"><a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"><span class="nav-icon">👤</span> Profil Saya</a></li>
        @elseif (auth()->user()->role === 'member')
            <li class="nav-item mb-1"><a href="{{ route('member.dashboard') }}" class="nav-link {{ request()->routeIs('member.dashboard') ? 'active' : '' }}"><span class="nav-icon">🏠</span> Dashboard</a></li>
            <li class="nav-item mb-1"><a href="{{ route('agendas.index') }}" class="nav-link {{ request()->routeIs('agendas.*') ? 'active' : '' }}"><span class="nav-icon">📌</span> Agenda</a></li>
            <li class="nav-item mb-1"><a href="{{ route('member-units.index') }}" class="nav-link {{ request()->routeIs('member-units.*') ? 'active' : '' }}"><span class="nav-icon">🤝</span> Gabung Unit</a></li>
            <li class="nav-item mb-1"><a href="{{ route('materials.index') }}" class="nav-link {{ request()->routeIs('materials.*') ? 'active' : '' }}"><span class="nav-icon">📚</span> Materi</a></li>
            <li class="nav-item mb-1"><a href="{{ route('galleries.index') }}" class="nav-link {{ request()->routeIs('galleries.*') ? 'active' : '' }}"><span class="nav-icon">🖼️</span> Galeri</a></li>
            <li class="nav-item mb-1"><a href="{{ route('certificates.index') }}" class="nav-link">🎖️ Sertifikat Saya</a></li>
            <li class="nav-item mb-1"><a href="{{ route('member.exit.create') }}" class="nav-link {{ request()->routeIs('member.exit.*') ? 'active' : '' }}"><span class="nav-icon">🚪</span> Ajukan Keluar</a></li>
            <li class="nav-item mb-1"><a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"><span class="nav-icon">👤</span> Profil Saya</a></li>
        @elseif (auth()->user()->role === 'candidate_member')
            <li class="nav-item mb-1"><a href="{{ route('candidate.dashboard') }}" class="nav-link {{ request()->routeIs('candidate.dashboard') ? 'active' : '' }}"><span class="nav-icon">🏠</span> Dashboard</a></li>
            <li class="nav-item mb-1"><a href="{{ route('agendas.index') }}" class="nav-link {{ request()->routeIs('agendas.*') ? 'active' : '' }}"><span class="nav-icon">📌</span> Agenda</a></li>
            <li class="nav-item mb-1"><a href="{{ route('materials.index') }}" class="nav-link {{ request()->routeIs('materials.*') ? 'active' : '' }}"><span class="nav-icon">📚</span> Materi</a></li>
            <li class="nav-item mb-1"><a href="{{ route('galleries.index') }}" class="nav-link {{ request()->routeIs('galleries.*') ? 'active' : '' }}"><span class="nav-icon">🖼️</span> Galeri</a></li>
            <li class="nav-item mb-1"><a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"><span class="nav-icon">👤</span> Profil Saya</a></li>
        @endif

    </ul>
</nav>
        @endauth

        <main class="flex-grow-1 py-4 overflow-auto">
            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (Session::has('success'))
    <script>
    Swal.fire({
    title: 'Berhasil',
    text: "{{ Session::get('success') }}",
    icon: 'success'
    });
    </script>
    @endif

    <script>
        function confirmDelete(form) {
            Swal.fire({
                title: 'Yakin hapus?',
                text: 'Data yang dihapus tidak bisa dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        }
    </script>

    @stack('scripts')
</body>
</html>