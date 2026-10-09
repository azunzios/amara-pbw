@extends('layouts.app')

@section('title', 'Login Daftar Publikasi BPS Jawa Timur')
@section('main-class', 'login-page')

@section('content')
<div class="login-card">

    <h2>Login</h2>

    <p class="login-subtitle">
        Silakan masuk untuk mengelola data publikasi
    </p>

    @if (session('error') || $errors->any())
        <div class="pesan-error">
            @if (session('error'))
                {{ session('error') }}
            @else
                @foreach ($errors->all() as $pesan)
                    {{ $pesan }}<br>
                @endforeach
            @endif
        </div>
    @endif

    <form action="{{ route('login') }}" method="post">
        @csrf

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="{{ old('username') }}"
                   placeholder="Masukkan username" required autocomplete="username">
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   placeholder="Masukkan password" required autocomplete="current-password">
        </div>

        <div class="form-ingat">
            <label for="remember">
                <input type="checkbox" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                Ingat saya
            </label>
        </div>

        <div class="login-button">
            <input type="submit" value="Login">
        </div>
    </form>

    <p class="login-subtitle login-link">
        Belum punya akun? <a href="{{ route('register') }}">Register</a>
    </p>

</div>
@endsection
