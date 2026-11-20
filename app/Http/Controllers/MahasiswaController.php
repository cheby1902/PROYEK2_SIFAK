<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengaduan;

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

    public function destroy($id)
    {
        $data = Pengaduan::where('id_pengaduan', $id)
            ->where('id_user', Auth::user()->id_user)
            ->firstOrFail();

        $data->delete();

        return redirect()->route('mahasiswa.dashboard')
            ->with('success', 'Pengaduan berhasil dihapus.');
    }
}