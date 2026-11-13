<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIFAK')</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body>
@php $role = auth()->user()->role; @endphp
<aside class="sidebar">
    <div class="logo">
        <svg width="34" height="34" viewBox="0 0 24 24" fill="#fff"><path d="M3 21V9l4-3v15H3zm5 0V4l4-2 4 2v17h-3v-4h-2v4H8zm9 0V6l4 3v12h-4z"/></svg>
        SIFAK
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

<div class="main">
    <header class="topbar">
        <strong>{{ \Illuminate\Support\Str::before(auth()->user()->email, '@') }}</strong>
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