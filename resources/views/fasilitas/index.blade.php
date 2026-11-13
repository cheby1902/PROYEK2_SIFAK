@extends('layouts.app')
@section('title', 'Kelola Fasilitas - SIFAK')
@section('content')
<h1>Kelola Fasilitas</h1>
<p class="sub">Kelola daftar fasilitas kampus yang dapat dipilih pada form pengaduan.</p>

<div class="toolbar-right">
    <button type="button" class="btn btn-primary" data-open-modal="modalTambah">+ Tambah Fasilitas</button>
</div>

<div class="table-wrap">
<table>
    <thead><tr><th>No</th><th>Nama Fasilitas</th><th>Jumlah Pengaduan</th><th>Aksi</th></tr></thead>
    <tbody>
    @forelse($fasilitas as $f)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $f->nama_fasilitas }}</td>
            <td>{{ $f->pengaduan_count }}</td>
            <td>
                <button type="button" class="btn btn-light"
                    data-open-modal="modalEdit"
                    data-action="{{ route('sarpras.fasilitas.update', $f) }}"
                    data-values="{{ json_encode(['nama_fasilitas' => $f->nama_fasilitas]) }}">Edit</button>
            </td>
        </tr>
    @empty
        <tr><td colspan="4" class="empty">Belum ada fasilitas.</td></tr>
    @endforelse
    </tbody>
</table>
</div>

{{-- Popup Tambah --}}
<div class="modal" id="modalTambah">
    <div class="modal-box">
        <form method="POST" action="{{ route('sarpras.fasilitas.store') }}">
            @csrf
            <h3>Tambah Fasilitas</h3>
            <p>Fasilitas baru akan muncul di pilihan pengaduan.</p>
            <label>Nama Fasilitas *</label>
            <input type="text" name="nama_fasilitas" placeholder="Contoh: Proyektor" maxlength="100" required>
            <div class="modal-actions">
                <button type="button" class="btn btn-light" data-close-modal="modalTambah">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Popup Edit --}}
<div class="modal" id="modalEdit">
    <div class="modal-box">
        <form method="POST" action="#">
            @csrf @method('PUT')
            <h3>Edit Fasilitas</h3>
            <p>Edit nama fasilitas jika ada yang salah. Perubahan akan muncul di pilihan form pengaduan.</p>
            <label>Nama Fasilitas *</label>
            <input type="text" name="nama_fasilitas" maxlength="100" required>
            <div class="modal-actions">
                <button type="button" class="btn btn-light" data-close-modal="modalEdit">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection