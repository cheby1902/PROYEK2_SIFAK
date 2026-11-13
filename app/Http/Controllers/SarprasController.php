<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use App\Models\Pengaduan;
use App\Models\RiwayatStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SarprasController extends Controller
{
    public const STATUS = ['Menunggu', 'Verifikasi', 'Ditolak', 'Diproses', 'Selesai'];

    public function dashboard()
    {
        $counts = Pengaduan::selectRaw('status, COUNT(*) AS total')->groupBy('status')->pluck('total', 'status');

        $stats = [
            'Total Pengaduan' => $counts->sum(),
            'Menunggu' => $counts['Menunggu'] ?? 0,
            'Verifikasi' => $counts['Verifikasi'] ?? 0,
            'Diproses' => $counts['Diproses'] ?? 0,
            'Selesai' => $counts['Selesai'] ?? 0,
            'Ditolak' => $counts['Ditolak'] ?? 0,
        ];

        $pengaduan = Pengaduan::with(['user.mahasiswa', 'fasilitas'])
            ->orderByDesc('id_pengaduan')->take(10)->get();

        return view('dashboard.sarpras', compact('stats', 'pengaduan'));
    }

    public function pengaduanIndex()
    {
        $pengaduan = Pengaduan::with(['user.mahasiswa', 'fasilitas'])->orderByDesc('id_pengaduan')->get();

        return view('pengaduan.index', compact('pengaduan'));
    }

    public function pengaduanShow(Pengaduan $pengaduan)
    {
        $pengaduan->load(['user.mahasiswa', 'fasilitas']);
        $riwayat = $pengaduan->riwayatStatus()->orderBy('tanggal_perubahan')->orderBy('id_riwayat')->get();

       return view('pengaduan.show', [
    'pengaduan' => $pengaduan,
    'riwayat' => $riwayat,
    'daftarStatus' => self::STATUS,
]);
    }

    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(self::STATUS)],
            'keterangan' => ['required_if:status,Ditolak', 'nullable', 'string', 'max:500'],
        ], [
            'status.required' => 'Pilih status terlebih dahulu.',
            'keterangan.required_if' => 'Alasan penolakan wajib diisi jika status Ditolak.',
        ]);

        DB::transaction(function () use ($pengaduan, $data, $request) {
            $pengaduan->update([
                'status' => $data['status'],
                'alasan_penolakan' => $data['status'] === 'Ditolak' ? $data['keterangan'] : null,
            ]);

            RiwayatStatus::create([
                'id_pengaduan' => $pengaduan->id_pengaduan,
                'id_user' => $request->user()->id_user,
                'status' => $data['status'],
                'keterangan' => $data['keterangan'] ?? null,
                'tanggal_perubahan' => now(),
            ]);
        });

        return back()->with('success', 'Status pengaduan berhasil diperbarui.');
    }

    public function pengaduanDestroy(Pengaduan $pengaduan)
    {
        DB::transaction(function () use ($pengaduan) {
            $pengaduan->riwayatStatus()->delete();
            if ($pengaduan->foto) {
                Storage::disk('public')->delete($pengaduan->foto);
            }
            $pengaduan->delete();
        });

        return redirect()->route('sarpras.pengaduan.index')->with('success', 'Pengaduan berhasil dihapus.');
    }

    public function fasilitasIndex()
    {
        $fasilitas = Fasilitas::withCount('pengaduan')->orderBy('id_fasilitas')->get();

        return view('fasilitas.index', compact('fasilitas'));
    }

    public function fasilitasStore(Request $request)
    {
        $data = $request->validate([
            'nama_fasilitas' => ['required', 'string', 'max:100', 'unique:fasilitas,nama_fasilitas'],
        ], [
            'nama_fasilitas.required' => 'Nama fasilitas wajib diisi.',
            'nama_fasilitas.unique' => 'Nama fasilitas sudah ada.',
        ]);

        Fasilitas::create($data);

        return back()->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function fasilitasUpdate(Request $request, Fasilitas $fasilitas)
    {
        $data = $request->validate([
            'nama_fasilitas' => [
                'required', 'string', 'max:100',
                Rule::unique('fasilitas', 'nama_fasilitas')->ignore($fasilitas->id_fasilitas, 'id_fasilitas'),
            ],
        ], [
            'nama_fasilitas.required' => 'Nama fasilitas wajib diisi.',
            'nama_fasilitas.unique' => 'Nama fasilitas sudah ada.',
        ]);

        $fasilitas->update($data);

        return back()->with('success', 'Fasilitas berhasil diperbarui.');
    }
}