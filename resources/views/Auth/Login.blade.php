
@extends('auth.layouts.app')

@section('title', 'Login - SIFAK')

@section('content')

<div class="login-wrapper">

    <div class="login-card">

        <div class="login-header">

            <div class="logo">
                <i class="bi bi-building"></i>
            </div>

            <h2>SIFAK</h2>

            <p>Sistem Informasi Pengaduan Fasilitas Kampus</p>

        </div>

        <div class="login-body">

            <h4>Selamat Datang!</h4>

            <p class="subtitle">
                Silakan login untuk melanjutkan
            </p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.process') }}" method="POST">

                @csrf

                <div class="mb-3">

                    <label for="email" class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-control"
                        placeholder="Masukkan email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    >

                </div>

                <div class="mb-4">

                    <label for="password" class="form-label">
                        Password
                    </label>

                    <div class="password-wrapper">

                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control"
                            placeholder="Masukkan password"
                            required
                        >

                        <button
                            type="button"
                            class="toggle-password"
                            id="togglePassword"
                            aria-label="Tampilkan password"
                        >
                            <i class="bi bi-eye"></i>
                        </button>

                    </div>

                </div>

                <button type="submit" class="btn-login">
                    Login
                </button>

            </form>

        </div>

        <div class="login-footer">
            &copy; {{ date('Y') }} SIFAK
        </div>

    </div>

</div>

@endsection
