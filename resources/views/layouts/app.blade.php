<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BPS Provinsi Jawa Timur')</title>
    {{-- Bootstrap (Modul 12.4.4) + myCSS.css milik webbps PHP, digabung di app.scss --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>

<header>
    <img src="{{ asset('images/logo-bps.svg') }}" alt="Logo BPS">
    <div class="judulweb">BPS PROVINSI JAWA TIMUR</div>

    <nav>
        @auth
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('publikasi.index') }}" class="{{ request()->routeIs('publikasi.index') ? 'active' : '' }}">Daftar Publikasi</a>
            <a href="{{ route('publikasi.create') }}" class="{{ request()->routeIs('publikasi.create') ? 'active' : '' }}">Tambah Publikasi</a>
            <a href="{{ route('galeri') }}" class="{{ request()->routeIs('galeri') ? 'active' : '' }}">Galeri Kegiatan</a>
            <a href="#" onclick="event.preventDefault(); document.getElementById('form-logout').submit();">Logout</a>
            <form id="form-logout" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
        @else
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('publikasi.index') }}" class="{{ request()->routeIs('publikasi.index') ? 'active' : '' }}">Daftar Publikasi</a>
            <a href="{{ route('publikasi.create') }}" class="{{ request()->routeIs('publikasi.create') ? 'active' : '' }}">Tambah Publikasi</a>
            <a href="{{ route('galeri') }}" class="{{ request()->routeIs('galeri') ? 'active' : '' }}">Galeri Kegiatan</a>
            <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'active' : '' }}">Login</a>
        @endauth
    </nav>
</header>

<main class="@yield('main-class')">
    @if (session('sukses'))
        <div class="pesan-sukses">
            <svg class="pesan-ikon" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('sukses') }}</span>
        </div>
    @endif

    @if (session('warning') && !request()->routeIs('login', 'register'))
        <div class="pesan-warning">
            <svg class="pesan-ikon" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('warning') }}</span>
        </div>
    @endif

    @if (session('error') && !request()->routeIs('login', 'register'))
        <div class="pesan-error">
            <svg class="pesan-ikon" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @yield('content')
</main>

<footer>
    <address>
        &copy; 2026 BPS Provinsi Jawa Timur &mdash; Created by Amara Qoshirotu (222413498@stis.ac.id)
    </address>
</footer>

@stack('scripts')
</body>
</html>
