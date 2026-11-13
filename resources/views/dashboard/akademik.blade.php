
@extends('layouts.app')

@section('title', 'Dashboard Akademik - SIFAK')

@section('content')
<h1>Dashboard Akademik</h1>
<p class="sub">Kelola data mahasiswa pengguna SIFAK.</p>

<div class="cards cards-single">
    <div class="card">
        <small>Total Pengguna SIFAK</small>
        <b>{{ $totalUser }}</b>
    </div>
</div>

{{-- ============ DATA MAHASISWA ============ --}}
<div class="section-head">
    <h2>Data Mahasiswa</h2>
    <button type="button" class="btn btn-primary" data-open-modal="modalTambahMhs">
        + Tambah Data
    </button>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>NPM</th>
                <th>Jurusan</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($mahasiswa as $m)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $m->nama_mahasiswa }}</td>
                    <td>{{ $m->user?->email }}</td>
                    <td>{{ $m->npm }}</td>
                    <td>{{ $m->jurusan }}</td>
                    <td>{{ ucfirst($m->user?->role ?? 'mahasiswa') }}</td>

                    <td>
                        <div class="actions">
                            <button
                                type="button"
                                class="btn btn-navy"
                                data-open-modal="modalEditMhs"
                                data-action="{{ route('akademik.mahasiswa.update', $m) }}"
                                data-values="{{ json_encode([
                                    'nama' => $m->nama_mahasiswa,
                                    'npm' => $m->npm,
                                    'jurusan' => $m->jurusan,
                                    'email' => $m->user?->email
                                ]) }}"
                            >
                                Edit
                            </button>

                            <form
                                method="POST"
                                action="{{ route('akademik.mahasiswa.destroy', $m) }}"
                                data-confirm="Hapus data mahasiswa beserta akun loginnya?"
                            >
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty">
                        Belum ada data mahasiswa.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


{{-- ============ DATA USER ============ --}}
<div class="section-head">
    <h2>Data User</h2>
    <button type="button" class="btn btn-primary" data-open-modal="modalTambahUser">
        + Tambah Data
    </button>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Email</th>
                <th>Password</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse($users as $u)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $u->email }}</td>
                    <td>••••••••</td>
                    <td>{{ ucfirst($u->role) }}</td>

                    <td>
                        <div class="actions">
                            <button
                                type="button"
                                class="btn btn-navy"
                                data-open-modal="modalEditUser"
                                data-action="{{ route('akademik.user.update', $u) }}"
                                data-lock-role="{{ $u->role === 'mahasiswa' ? '1' : '0' }}"
                                data-values="{{ json_encode([
                                    'email' => $u->email,
                                    'role' => $u->role
                                ]) }}"
                            >
                                Edit
                            </button>

                            <form
                                method="POST"
                                action="{{ route('akademik.user.destroy', $u) }}"
                                data-confirm="Hapus akun ini?"
                            >
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn btn-danger">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="empty">
                        Belum ada data user.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


{{-- ============ POPUP: TAMBAH MAHASISWA ============ --}}
<div class="modal" id="modalTambahMhs">
    <div class="modal-box">
        <form method="POST" action="{{ route('akademik.mahasiswa.store') }}">
            @csrf

            <h3>Tambah Data Mahasiswa</h3>
            <p>Isi data mahasiswa baru. Password awal otomatis menggunakan NPM.</p>

            <label>Nama</label>
            <input
                type="text"
                name="nama"
                placeholder="Nama lengkap"
                maxlength="100"
                required
            >

            <label>NPM</label>
            <input
                type="text"
                name="npm"
                placeholder="Contoh: 714250013"
                maxlength="20"
                required
            >

            <label>Jurusan</label>
            <select name="jurusan" required>
                @foreach($daftarJurusan as $j)
                    <option value="{{ $j }}">{{ $j }}</option>
                @endforeach
            </select>

            <label>Email</label>
            <input
                type="email"
                name="email"
                placeholder="nama@sifak.ac.id"
                maxlength="150"
                required
            >

            <div class="modal-actions">
                <button
                    type="button"
                    class="btn btn-light"
                    data-close-modal="modalTambahMhs"
                >
                    Batal
                </button>

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ============ POPUP: EDIT MAHASISWA ============ --}}
<div class="modal" id="modalEditMhs">
    <div class="modal-box">
        <form method="POST" action="#">
            @csrf
            @method('PUT')

            <h3>Edit Data Mahasiswa</h3>
            <p>Ubah data mahasiswa. Jika NPM berubah, password awal juga akan mengikuti NPM baru.</p>

            <label>Nama</label>
            <input
                type="text"
                name="nama"
                maxlength="100"
                required
            >

            <label>NPM</label>
            <input
                type="text"
                name="npm"
                maxlength="20"
                required
            >

            <label>Jurusan</label>
            <select name="jurusan" required>
                @foreach($daftarJurusan as $j)
                    <option value="{{ $j }}">{{ $j }}</option>
                @endforeach
            </select>

            <label>Email</label>
            <input
                type="email"
                name="email"
                maxlength="150"
                required
            >

            <div class="modal-actions">
                <button
                    type="button"
                    class="btn btn-light"
                    data-close-modal="modalEditMhs"
                >
                    Batal
                </button>

                <button type="submit" class="btn btn-primary">
                    Simpan perubahan
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ============ POPUP: TAMBAH USER ============ --}}
<div class="modal" id="modalTambahUser">
    <div class="modal-box">
        <form method="POST" action="{{ route('akademik.user.store') }}">
            @csrf

            <h3>Tambah Data User</h3>
            <p>Isi data akun staf baru.</p>

            <label>Email</label>
            <input
                type="email"
                name="email"
                placeholder="nama@sifak.ac.id"
                maxlength="150"
                required
            >

            <label>Password</label>
            <input
                type="password"
                name="password"
                placeholder="Minimal 6 karakter"
                minlength="6"
                required
            >

            <label>Role</label>
            <input
                type="text"
                name="role"
                list="daftarRole"
                placeholder="Contoh: Akademik, Sarpras"
                required
            >

            <datalist id="daftarRole">
                <option value="akademik">
                <option value="sarpras">
            </datalist>

            <div class="modal-actions">
                <button
                    type="button"
                    class="btn btn-light"
                    data-close-modal="modalTambahUser"
                >
                    Batal
                </button>

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ============ POPUP: EDIT USER ============ --}}
<div class="modal" id="modalEditUser">
    <div class="modal-box">
        <form method="POST" action="#">
            @csrf
            @method('PUT')

            <h3>Edit Data User</h3>
            <p>Ubah data lalu simpan perubahan. Role akun Mahasiswa tidak dapat diubah di sini.</p>

            <label>Email</label>
            <input
                type="email"
                name="email"
                maxlength="150"
                required
            >

            <label>Password</label>
            <input
                type="password"
                name="password"
                placeholder="Kosongkan jika tidak diubah"
                minlength="6"
            >

            <label>Role</label>
            <input
                type="text"
                name="role"
                list="daftarRole"
                required
            >

            <div class="modal-actions">
                <button
                    type="button"
                    class="btn btn-light"
                    data-close-modal="modalEditUser"
                >
                    Batal
                </button>

                <button type="submit" class="btn btn-primary">
                    Simpan perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
