<div class="table-wrap">
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NIM</th>
            <th>Email</th>
            <th>Fasilitas</th>
            <th class="col-lokasi">Lokasi</th>
            <th>Tanggal</th>
            <th class="col-deskripsi">Deskripsi</th>
            <th>Foto</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
    @forelse($pengaduan as $p)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $p->user?->mahasiswa?->nama_mahasiswa ?? '-' }}</td>
            <td>{{ $p->user?->mahasiswa?->npm ?? '-' }}</td>
            <td>{{ $p->user?->email ?? '-' }}</td>
            <td>{{ $p->fasilitas?->nama_fasilitas ?? '-' }}</td>
            <td class="col-lokasi">{{ $p->lokasi }}</td>
            <td>{{ $p->tanggal_pengaduan->translatedFormat('d M Y') }}</td>
            <td class="col-deskripsi">
            <div class="deskripsi-clamp" title="{{ $p->deskripsi }}">{{ $p->deskripsi }}</div>
            </td>
            <td>
                @if($p->foto)
                    <button type="button" class="btn-foto"
                        data-open-modal="modalFoto"
                        data-foto="{{ asset('storage/'.$p->foto) }}">Foto</button>
                @else
                    <span class="btn-foto btn-foto-kosong">-</span>
                @endif
            </td>
            <td>@include('partials.status-badge', ['status' => $p->status])</td>
            <td>
                <div class="actions">
                    <a href="{{ route('sarpras.pengaduan.show', $p) }}" class="btn btn-navy">Detail</a>
                    <form method="POST" action="{{ route('sarpras.pengaduan.destroy', $p) }}" data-confirm="Yakin ingin menghapus pengaduan ini?">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <tr><td colspan="11" class="empty">Belum ada pengaduan.</td></tr>
    @endforelse
    </tbody>
</table>
</div>

{{-- Popup foto --}}
<div class="modal" id="modalFoto">
    <div class="modal-box modal-foto">
        <img src="" alt="Foto pengaduan">
        <div class="modal-actions">
            <button type="button" class="btn btn-light" data-close-modal="modalFoto">Tutup</button>
        </div>
    </div>
</div>