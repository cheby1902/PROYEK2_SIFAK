<div class="table-wrap">
<table>
    <thead>
        <tr>
            <th>No</th><th>Nama</th><th>NIM</th><th>Email</th><th>Fasilitas</th><th>Lokasi</th>
            <th>Tanggal</th><th>Deskripsi</th><th>Foto</th><th>Status</th><th>Aksi</th>
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
            <td>{{ $p->lokasi }}</td>
            <td>{{ $p->tanggal_pengaduan->translatedFormat('d F Y') }}</td>
            <td class="col-deskripsi">{{ $p->deskripsi }}</td>
            <td>
                <div class="thumb">
                    @if($p->foto)<img src="{{ asset('storage/'.$p->foto) }}" alt="Foto">@else Foto @endif
                </div>
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