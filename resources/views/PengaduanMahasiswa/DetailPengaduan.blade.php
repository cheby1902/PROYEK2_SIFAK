
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengaduan - SIFAK</title>

    <link rel="stylesheet" href="{{ asset('css/detail-pengaduan.css') }}">
</head>
<body>

@php
    $user = auth()->user();

    $status = $pengaduan->status ?? 'Menunggu';
    $statusClass = 'status-' . strtolower(str_replace(' ', '-', $status));

    $riwayat = $pengaduan->riwayatStatus ?? collect();
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

        <nav class="sidebar-menu">

            <a href="{{ route('mahasiswa.dashboard') }}" class="menu-item active">
                Dashboard
            </a>

            <a href="{{ route('mahasiswa.pengaduan.create') }}" class="menu-item">
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

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>

        </header>

        {{-- CONTENT --}}
        <main class="dashboard-content">

            {{-- TOMBOL KEMBALI --}}
            <a href="{{ route('mahasiswa.dashboard') }}" class="detail-btn">
                ← Kembali ke Dashboard
            </a>

            <h1>Detail Pengaduan</h1>

            {{-- KARTU DETAIL --}}
            <section class="detail-card">

                <div class="detail-foto">
                    @if($pengaduan->foto)
                        <a href="{{ asset('storage/' . $pengaduan->foto) }}" target="_blank">
                            <img
                                src="{{ asset('storage/' . $pengaduan->foto) }}"
                                alt="Foto pengaduan"
                            >
                        </a>
                    @else
                        <span>Foto tidak tersedia</span>
                    @endif
                </div>

                <div class="detail-info">

                    <div class="detail-title">
                        <h2>
                            {{ $pengaduan->fasilitas->nama_fasilitas ?? '-' }}
                        </h2>

                        <span class="status-badge {{ $statusClass }}">
                            {{ $status }}
                        </span>
                    </div>

                    <p>
                        <strong>Lokasi:</strong>
                        {{ $pengaduan->lokasi ?? '-' }}
                    </p>

                    <p>
                        <strong>Tanggal:</strong>
                        {{ $pengaduan->tanggal_pengaduan
                            ? \Carbon\Carbon::parse($pengaduan->tanggal_pengaduan)->translatedFormat('d F Y')
                            : '-' }}
                    </p>

                    <p>
                        <strong>Deskripsi:</strong>
                        {{ $pengaduan->deskripsi ?? '-' }}
                    </p>

                    @if($pengaduan->alasan_penolakan)
                        <div class="alasan-box">
                            <strong>Alasan Penolakan:</strong>
                            {{ $pengaduan->alasan_penolakan }}
                        </div>
                    @endif

                </div>

            </section>

            {{-- RIWAYAT STATUS --}}
            <section class="riwayat-section">

                <h3>Riwayat Status</h3>

                <ul class="timeline">

                    @forelse($riwayat as $r)
                        <li class="timeline-item">

                            <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $r->status ?? 'menunggu')) }}">
                                {{ $r->status ?? '-' }}
                            </span>

                            <div class="timeline-date">
                                {{ $r->tanggal_perubahan
                                    ? \Carbon\Carbon::parse($r->tanggal_perubahan)->translatedFormat('d F Y, H:i')
                                    : '-' }}
                            </div>

                            @if($r->keterangan)
                                <div class="timeline-note">
                                    {{ $r->keterangan }}
                                </div>
                            @endif

                        </li>
                    @empty
                        <li class="timeline-empty">
                            Belum ada riwayat status.
                        </li>
                    @endforelse

                </ul>

            </section>

        </main>

    </div>

</div>

<script src="{{ asset('js/detail-pengaduan.js') }}"></script>

</body>
</html>