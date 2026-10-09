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
    <img src="https://jatim.bps.go.id/_next/image?url=%2Fassets%2Flogo-bps.png&w=1080&q=75" alt="Logo BPS">
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
            <a class="disabled">Daftar Publikasi</a>
            <a class="disabled">Tambah Publikasi</a>
            <a class="disabled">Galeri Kegiatan</a>
            <a href="{{ route('login') }}" class="active">Login</a>
        @endauth
    </nav>
</header>

<main class="@yield('main-class')">
    @if (session('sukses'))
        <div class="pesan-sukses">{{ session('sukses') }}</div>
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
