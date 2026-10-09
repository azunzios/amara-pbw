@extends('layouts.app')

@section('title', 'Login Daftar Publikasi BPS Jawa Timur')
@section('main-class', 'login-page')

@section('content')
<div class="login-card">

    <h2>Login</h2>

    <p class="login-subtitle">
        Silakan masuk untuk mengelola data publikasi
    </p>

    @if (session('warning'))
        <div class="pesan-warning">
            <svg class="pesan-ikon" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <span>{{ session('warning') }}</span>
        </div>
    @endif

    @if (session('error') || $errors->any())
        <div class="pesan-error">
            <svg class="pesan-ikon" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
            </svg>
            <span>
                @if (session('error'))
                    {{ session('error') }}
                @else
                    @foreach ($errors->all() as $pesan)
                        {{ $pesan }}<br>
                    @endforeach
                @endif
            </span>
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
