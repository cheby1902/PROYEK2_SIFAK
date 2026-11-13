@extends('layouts.app')
@section('title', 'Detail Pengaduan - SIFAK')
@section('content')
<h1>Detail Pengaduan</h1>
<p class="sub"><a href="{{ route('sarpras.pengaduan.index') }}" class="back-link">&larr; Kembali</a></p>

{{-- 1. Data laporan --}}
<div class="detail">
    <div class="photo">
        @if($pengaduan->foto)
            <img src="{{ asset('storage/'.$pengaduan->foto) }}" alt="Foto">
        @else
            Foto
        @endif
    </div>
    <div>
        <p><b>{{ $pengaduan->fasilitas?->nama_fasilitas ?? '-' }}</b> @include('partials.status-badge', ['status' => $pengaduan->status])</p>
        <p>Pelapor: {{ $pengaduan->user?->mahasiswa?->nama_mahasiswa ?? '-' }}</p>
        <p>NIM: {{ $pengaduan->user?->mahasiswa?->npm ?? '-' }}</p>
        <p>Email: {{ $pengaduan->user?->email ?? '-' }}</p>
        <p>Lokasi: {{ $pengaduan->lokasi }}</p>
        <p>Tanggal: {{ $pengaduan->tanggal_pengaduan->translatedFormat('d F Y') }}</p>
        <p>Deskripsi: {{ $pengaduan->deskripsi }}</p>
    </div>
</div>

{{-- 2. Ubah status --}}
<div class="section-head"><h2>Ubah Status</h2></div>
<form method="POST" action="{{ route('sarpras.pengaduan.status', $pengaduan) }}">
    @csrf @method('PUT')

    <label for="status">Status</label>
    <select name="status" id="status" required>
        <option value="">Pilih status</option>
        @foreach($daftarStatus as $s)
            <option value="{{ $s }}" @selected($pengaduan->status === $s)>{{ $s }}</option>
        @endforeach
    </select>

    <label for="keterangan">Keterangan (wajib diisi jika status Ditolak)</label>
    <textarea name="keterangan" id="keterangan" rows="3">{{ old('keterangan') }}</textarea>

    <div class="modal-actions">
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form>

{{-- 3. Riwayat status --}}
<div class="section-head"><h2>Riwayat Status</h2></div>
<div class="table-wrap">
<table>
    <thead><tr><th>Tanggal</th><th>Status</th><th>Keterangan</th></tr></thead>
    <tbody>
    @forelse($riwayat as $r)
        <tr>
            <td>{{ $r->tanggal_perubahan->translatedFormat('d F Y, H:i') }}</td>
            <td>@include('partials.status-badge', ['status' => $r->status])</td>
            <td>{{ $r->keterangan ?? '-' }}</td>
        </tr>
    @empty
        <tr><td colspan="3" class="empty">Belum ada riwayat.</td></tr>
    @endforelse
    </tbody>
</table>
</div>
@endsection