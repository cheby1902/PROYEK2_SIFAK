<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIFAK')</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
@php $role = auth()->user()->role; @endphp

{{-- SIDEBAR --}}
<aside class="sidebar">
    <div class="logo">
        <svg class="logo-icon" viewBox="0 0 64 64" fill="#ffffff" aria-hidden="true">
            <path d="M30 6 L44 16 V58 H16 V16 Z"/>
            <path d="M2 36 L11 30 L20 36 V58 H2 Z"/>
            <path d="M44 36 L53 30 L62 36 V58 H44 Z"/>
            <g fill="#172b4d">
                <rect x="25" y="22" width="4" height="4"/>
                <rect x="32" y="22" width="4" height="4"/>
                <rect x="25" y="30" width="4" height="4"/>
                <rect x="32" y="30" width="4" height="4"/>
                <rect x="25" y="46" width="10" height="12"/>
                <rect x="6" y="42" width="4" height="4"/>
                <rect x="6" y="50" width="4" height="4"/>
                <rect x="52" y="42" width="4" height="4"/>
                <rect x="52" y="50" width="4" height="4"/>
            </g>
        </svg>
        <span>SIFAK</span>
    </div>

    <nav class="menu">
        @if($role === 'sarpras')
            <a href="{{ route('sarpras.dashboard') }}" class="{{ request()->routeIs('sarpras.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('sarpras.pengaduan.index') }}" class="{{ request()->routeIs('sarpras.pengaduan.*') ? 'active' : '' }}">Kelola Pengaduan</a>
            <a href="{{ route('sarpras.fasilitas.index') }}" class="{{ request()->routeIs('sarpras.fasilitas.*') ? 'active' : '' }}">Kelola Fasilitas</a>
        @elseif($role === 'akademik')
            <a href="{{ route('akademik.dashboard') }}" class="{{ request()->routeIs('akademik.dashboard') ? 'active' : '' }}">Dashboard</a>
        @endif
    </nav>
</aside>

{{-- AREA UTAMA --}}
<div class="main">
    <header class="topbar">
        <div class="user-info">
            <div class="user-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
                </svg>
            </div>
            <div class="nama-user">{{ \Illuminate\Support\Str::before(auth()->user()->email, '@') }}</div>
        </div>

        <form method="POST" action="{{ Route::has('logout') ? route('logout') : '#' }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </header>

    <main class="content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-error">
                <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}"></script>
</body>
</html>