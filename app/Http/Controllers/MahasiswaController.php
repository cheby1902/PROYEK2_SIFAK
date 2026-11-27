<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengaduan;
use App\Models\Fasilitas;
use App\Models\RiwayatStatus;

class MahasiswaController extends Controller
{
     public function dashboard()
    {
        $user = Auth::user();

        $pengaduan = Pengaduan::with('fasilitas')
            ->where('id_user', $user->id_user)
            ->orderByDesc('id_pengaduan')
            ->get();

        $totalPengaduan = $pengaduan->count();

        return view('dashboard.mahasiswa', compact('user', 'pengaduan', 'totalPengaduan'));
    }
    public function show($id)
{
    $pengaduan = Pengaduan::with(['fasilitas', 'riwayatStatus'])
        ->where('id_pengaduan', $id)
        ->where('id_user', Auth::user()->id_user)
        ->firstOrFail();

    $pengaduan->riwayatStatus = $pengaduan->riwayatStatus
        ->sortBy('tanggal_perubahan');

    return view('PengaduanMahasiswa.DetailPengaduan', compact('pengaduan'));
}

        public function destroy($id)
    {
        $data = Pengaduan::where('id_pengaduan', $id)
            ->where('id_user', Auth::user()->id_user)
            ->firstOrFail();

        $data->delete();

        return redirect()->route('mahasiswa.dashboard')
            ->with('success', 'Pengaduan berhasil dihapus.');
    }

    public function create()
{
    $fasilitas = Fasilitas::all();

    return view('PengaduanMahasiswa.BuatPengaduan', compact('fasilitas'));
}

    public function store(Request $request)
{
    $request->validate([
        'id_fasilitas' => 'required|exists:fasilitas,id_fasilitas',
        'tanggal_pengaduan' => 'required|date',
        'lokasi' => 'required|string|max:150',
        'deskripsi' => 'required|string',
        'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $foto = $request->file('foto')->store('pengaduan', 'public');

    $pengaduan = Pengaduan::create([
        'id_user' => Auth::user()->id_user,
        'id_fasilitas' => $request->id_fasilitas,
        'lokasi' => $request->lokasi,
        'tanggal_pengaduan' => $request->tanggal_pengaduan,
        'deskripsi' => $request->deskripsi,
        'foto' => $foto,
        'status' => 'Menunggu',
        'alasan_penolakan' => null,
    ]);

    RiwayatStatus::create([
        'id_pengaduan' => $pengaduan->id_pengaduan,
        'id_user' => Auth::user()->id_user,
        'status' => 'Menunggu',
        'keterangan' => 'Pengaduan berhasil dibuat dan menunggu proses.',
        'tanggal_perubahan' => now(),
    ]);

    return redirect()->route('mahasiswa.dashboard')
        ->with('success', 'Pengaduan berhasil dibuat.');
}
}