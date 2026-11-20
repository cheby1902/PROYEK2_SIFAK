<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa - SIFAK</title>

    <link rel="stylesheet" href="{{ asset('css/dashboard/mahasiswa.css') }}">
</head>
<body>

<div class="sifak-layout">

    {{-- SIDEBAR --}}
    <aside class="sidebar" id="sidebar">

        <div class="logo">
            {{-- Ikon gedung (SVG putih) --}}
            <svg class="logo-icon" viewBox="0 0 64 64" fill="#ffffff" aria-hidden="true">
                <path d="M30 6 L44 16 V58 H16 V16 Z"/>
                <path d="M2 36 L11 30 L20 36 V58 H2 Z"/>
                <path d="M44 36 L53 30 L62 36 V58 H44 Z"/>
                <g fill="#172b4d">
                    <rect x="25" y="22" width="4" height="4"/><rect x="32" y="22" width="4" height="4"/>
                    <rect x="25" y="30" width="4" height="4"/><rect x="32" y="30" width="4" height="4"/>
                    <rect x="25" y="46" width="10" height="12"/>
                    <rect x="6" y="42" width="4" height="4"/><rect x="6" y="50" width="4" height="4"/>
                    <rect x="52" y="42" width="4" height="4"/><rect x="52" y="50" width="4" height="4"/>
                </g>
            </svg>
            <span>SIFAK</span>
        </div>

        <nav class="sidebar-menu">

            <a href="{{ route('mahasiswa.dashboard') }}"
               class="menu-item active">
                Dashboard
            </a>

            <a href="{{ url('/pengaduan/create') }}"
               class="menu-item">
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

            <div class="nama-user">
                {{ $user->mahasiswa->nama ?? $user->email }}
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf

                <button type="submit" class="logout-btn">
                    Logout
                </button>
            </form>

        </header>

        {{-- CONTENT --}}
        <main class="dashboard-content">

            <h1>Dashboard Mahasiswa</h1>

            <p class="welcome-text">
                Selamat datang di SIFAK!
            </p>

            {{-- ALERT SUKSES --}}
            @if(session('success'))
                <div class="alert-success" id="successAlert">
                    <span>{{ session('success') }}</span>
                    <button type="button" class="alert-close" id="alertClose">
                        &times;
                    </button>
                </div>
            @endif

            {{-- TOTAL PENGADUAN --}}
            <div class="total-card">

                <div class="card-label">
                    Total Pengaduan
                </div>

                <div class="card-number">
                    {{ $totalPengaduan }}
                </div>

            </div>

            {{-- RIWAYAT PENGADUAN --}}
            <section class="history-section">

                <h2>Riwayat Pengaduan</h2>

                <div class="table-container">

                    <table class="history-table">

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Fasilitas</th>
                                <th>Lokasi</th>
                                <th>Tanggal</th>
                                <th>Deskripsi</th>
                                <th>Foto</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($pengaduan as $item)

                                <tr>

                                    <td>{{ $loop->iteration }}</td>

                                    <td>{{ $item->fasilitas->nama_fasilitas ?? '-' }}</td>

                                    <td>{{ $item->lokasi ?? '-' }}</td>

                                    <td>
                                        {{ $item->tanggal_pengaduan
                                            ? $item->tanggal_pengaduan->format('d M Y')
                                            : '-' }}
                                    </td>

                                    <td class="deskripsi">
                                        {{ $item->deskripsi ?? '-' }}
                                    </td>

                                    <td>
                                        @if($item->foto)
                                            <button
                                                type="button"
                                                class="foto-btn"
                                                data-foto="{{ asset('storage/' . $item->foto) }}"
                                                data-alt="Foto pengaduan fasilitas">
                                                Foto
                                            </button>
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>

                                    <td>
                                        <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $item->status ?? 'menunggu')) }}">
                                            {{ ucfirst($item->status ?? 'Menunggu') }}
                                        </span>
                                    </td>

                                    <td class="aksi">

                                        <details class="detail-dropdown">

                                            <summary class="detail-btn">
                                                Detail
                                            </summary>

                                            <div class="detail-content">

                                                <p><strong>Fasilitas:</strong> {{ $item->fasilitas->nama_fasilitas ?? '-' }}</p>

                                                <p><strong>Lokasi:</strong> {{ $item->lokasi ?? '-' }}</p>

                                                <p>
                                                    <strong>Tanggal:</strong>
                                                    {{ $item->tanggal_pengaduan
                                                        ? $item->tanggal_pengaduan->format('d M Y')
                                                        : '-' }}
                                                </p>

                                                <p><strong>Deskripsi:</strong> {{ $item->deskripsi ?? '-' }}</p>

                                                <p><strong>Status:</strong> {{ ucfirst($item->status ?? 'Menunggu') }}</p>

                                                @if($item->alasan_penolakan)
                                                    <p>
                                                        <strong>Alasan Penolakan:</strong>
                                                        {{ $item->alasan_penolakan }}
                                                    </p>
                                                @endif

                                            </div>

                                        </details>

                                        <form
                                            action="{{ route('mahasiswa.pengaduan.destroy', $item->id_pengaduan) }}"
                                            method="POST"
                                            class="form-hapus">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="hapus-btn">
                                                Hapus
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="8" class="empty-data">
                                        <div class="empty-icon">📋</div>
                                        <p>Belum ada pengaduan.</p>
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </section>

        </main>

    </div>

</div>

{{-- MODAL PREVIEW FOTO --}}
<div class="foto-modal" id="fotoModal">
    <div class="foto-modal-content">
        <button type="button" class="foto-modal-close" id="fotoModalClose">
            &times;
        </button>

        <img src="" alt="Preview foto pengaduan" id="previewFoto">
    </div>
</div>

{{-- JAVASCRIPT --}}
<script src="{{ asset('js/mahasiswa.js') }}"></script>

</body>
</html>