
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Pengaduan - SIFAK</title>

    <link rel="stylesheet" href="{{ asset('css/buat-pengaduan.css') }}">
</head>
<body>

@php
    $user = auth()->user();
@endphp

<div class="sifak-layout">

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">

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

        {{-- MENU SIDEBAR --}}
        <nav class="sidebar-menu">

            <a href="{{ route('mahasiswa.dashboard') }}"
               class="menu-item {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
                Dashboard
            </a>

            <a href="{{ route('mahasiswa.pengaduan.create') }}"
               class="menu-item {{ request()->routeIs('mahasiswa.pengaduan.create') ? 'active' : '' }}">
                Buat Pengaduan
            </a>

        </nav>

    </aside>

    {{-- AREA UTAMA --}}
    <div class="main-area">

        {{-- HEADER --}}
        <header class="topbar">

            <button type="button" class="sidebar-toggle" id="sidebarToggle">
                ☰
            </button>

            {{-- PROFIL MAHASISWA --}}
            <div class="user-info">

                <div class="user-icon">
                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor"
                         stroke-width="2"
                         stroke-linecap="round"
                         stroke-linejoin="round">

                        <circle cx="12" cy="8" r="4"/>
                        <path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
                    </svg>
                </div>

                <div class="nama-user">
                    {{ $user->mahasiswa->nama_mahasiswa ?? $user->email }}
                </div>

            </div>

            {{-- LOGOUT --}}
            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit" class="logout-btn">
                    Logout
                </button>
            </form>

        </header>

        {{-- CONTENT --}}
        <main class="dashboard-content">

            <h1>Buat Pengaduan</h1>

            <p class="welcome-text">
                Laporkan Kerusakan Fasilitas Kampus dengan Lengkap.
            </p>

            {{-- ERROR VALIDASI --}}
            @if($errors->any())
                <div class="alert-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ALERT SUKSES --}}
            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- FORM PENGADUAN --}}
            <form action="{{ route('mahasiswa.pengaduan.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="form-pengaduan">

                @csrf

                {{-- NAMA FASILITAS --}}
                <div class="form-group">

                    <label for="id_fasilitas">
                        Nama Fasilitas 
                    </label>

                    <select name="id_fasilitas"
                            id="id_fasilitas"
                            class="form-control @error('id_fasilitas') is-invalid @enderror"
                            required>

                        <option value="" disabled
                            {{ old('id_fasilitas') ? '' : 'selected' }}>
                            Pilih fasilitas
                        </option>

                        @foreach($fasilitas as $item)
                            <option value="{{ $item->id_fasilitas }}"
                                {{ old('id_fasilitas') == $item->id_fasilitas ? 'selected' : '' }}>
                                {{ $item->nama_fasilitas }}
                            </option>
                        @endforeach

                    </select>

                    @error('id_fasilitas')
                        <div class="error-text">{{ $message }}</div>
                    @enderror

                </div>

                {{-- TANGGAL --}}
                <div class="form-group">

                    <label for="tanggal_pengaduan">
                        Tanggal 
                    </label>

                    <input type="date"
                           name="tanggal_pengaduan"
                           id="tanggal_pengaduan"
                           class="form-control @error('tanggal_pengaduan') is-invalid @enderror"
                           value="{{ old('tanggal_pengaduan') }}"
                           required>

                    @error('tanggal_pengaduan')
                        <div class="error-text">{{ $message }}</div>
                    @enderror

                </div>

                {{-- LOKASI --}}
                <div class="form-group">

                    <label for="lokasi">
                        Lokasi 
                    </label>

                    <input type="text"
                           name="lokasi"
                           id="lokasi"
                           class="form-control @error('lokasi') is-invalid @enderror"
                           placeholder="Contoh: Gedung A - R.101"
                           value="{{ old('lokasi') }}"
                           required>

                    @error('lokasi')
                        <div class="error-text">{{ $message }}</div>
                    @enderror

                </div>

                {{-- DESKRIPSI --}}
                <div class="form-group">

                    <label for="deskripsi">
                        Deskripsi Kerusakan 
                    </label>

                    <textarea name="deskripsi"
                              id="deskripsi"
                              rows="3"
                              class="form-control @error('deskripsi') is-invalid @enderror"
                              placeholder="Jelaskan kerusakan yang terjadi..."
                              required>{{ old('deskripsi') }}</textarea>

                    @error('deskripsi')
                        <div class="error-text">{{ $message }}</div>
                    @enderror

                </div>

                {{-- FOTO --}}
                <div class="form-group">

                    <label class="form-label" for="foto">
                        Foto 
                    </label>

                    <label for="foto" class="upload-box">

                        <span id="uploadText">
                            Klik untuk mengunggah foto
                        </span>

                        <img src=""
                             alt="Preview foto"
                             class="upload-preview"
                             id="uploadPreview">

                        <input type="file"
                               name="foto"
                               id="foto"
                               accept="image/*"
                               required>

                    </label>

                    @error('foto')
                        <div class="error-text">{{ $message }}</div>
                    @enderror

                </div>

                {{-- BUTTON KIRIM --}}
                <div class="form-actions">

                    <button type="submit" class="btn-submit">
                        Kirim Pengaduan
                    </button>

                </div>

            </form>

        </main>

    </div>

</div>

<script src="{{ asset('js/buat-pengaduan.js') }}"></script>

</body>
</html>
