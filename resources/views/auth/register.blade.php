@extends('layouts.app')

@section('title', 'Register BPS Jawa Timur')
@section('main-class', 'login-page')

@section('content')
<div class="login-card">

    <h2>Register</h2>

    <p class="login-subtitle">
        Buat akun untuk mengelola data publikasi
    </p>

    @if ($errors->any())
        <div class="pesan-error">
            @foreach ($errors->all() as $pesan)
                {{ $pesan }}<br>
            @endforeach
        </div>
    @endif

    <form action="{{ route('register') }}" method="post">
        @csrf

        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}"
                   placeholder="Masukkan nama lengkap" required autocomplete="name">
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="{{ old('username') }}"
                   placeholder="Masukkan username" required autocomplete="username">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   placeholder="Minimal 6 karakter" required autocomplete="new-password">
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                   placeholder="Ulangi password" required autocomplete="new-password">
        </div>

        <div class="login-button">
            <input type="submit" value="Register">
        </div>
    </form>

    <p class="login-subtitle login-link">
        Sudah punya akun? <a href="{{ route('login') }}">Login</a>
    </p>

</div>
@endsection
