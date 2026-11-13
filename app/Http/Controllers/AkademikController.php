<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AkademikController extends Controller
{
    // Tambahkan jurusan lain di sini jika perlu.
    public const JURUSAN = ['Teknik Informatika'];

    public function dashboard()
    {
        return view('dashboard.akademik', [
            'totalUser' => User::count(),
            'mahasiswa' => Mahasiswa::with('user')
                ->orderBy('id_mahasiswa')
                ->get(),
            'users' => User::orderBy('id_user')->get(),
            'daftarJurusan' => self::JURUSAN,
        ]);
    }

    // ---------- DATA MAHASISWA ----------

    public function mahasiswaStore(Request $request)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'npm' => ['required', 'string', 'max:20', 'unique:mahasiswa,npm'],
            'jurusan' => ['required', Rule::in(self::JURUSAN)],
            'email' => ['required', 'email', 'max:150', 'unique:user,email'],
        ]);

        DB::transaction(function () use ($data) {

            // Password awal mahasiswa otomatis menggunakan NPM.
            // Password akan di-hash otomatis oleh cast pada model User.
            $user = User::create([
                'email' => $data['email'],
                'password' => $data['npm'],
                'role' => 'mahasiswa',
            ]);

            Mahasiswa::create([
                'id_user' => $user->id_user,
                'npm' => $data['npm'],
                'nama_mahasiswa' => $data['nama'],
                'jurusan' => $data['jurusan'],
            ]);
        });

        return back()->with(
            'success',
            'Data mahasiswa dan akun login berhasil ditambahkan. Password awal menggunakan NPM.'
        );
    }

    public function mahasiswaUpdate(Request $request, Mahasiswa $mahasiswa)
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],

            'npm' => [
                'required',
                'string',
                'max:20',
                Rule::unique('mahasiswa', 'npm')
                    ->ignore($mahasiswa->id_mahasiswa, 'id_mahasiswa'),
            ],

            'jurusan' => ['required', Rule::in(self::JURUSAN)],

            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('user', 'email')
                    ->ignore($mahasiswa->id_user, 'id_user'),
            ],
        ]);

        DB::transaction(function () use ($mahasiswa, $data) {

            // Periksa apakah NPM berubah.
            $npmBerubah = $mahasiswa->npm !== $data['npm'];

            // Perbarui data mahasiswa.
            $mahasiswa->update([
                'npm' => $data['npm'],
                'nama_mahasiswa' => $data['nama'],
                'jurusan' => $data['jurusan'],
            ]);

            // Ambil akun mahasiswa.
            $akun = $mahasiswa->user;

            if ($akun) {
                $akun->email = $data['email'];

                // Jika NPM berubah, password awal ikut diperbarui.
                if ($npmBerubah) {
                    $akun->password = $data['npm'];
                }

                $akun->save();
            }
        });

        return back()->with(
            'success',
            'Data mahasiswa berhasil diperbarui.'
        );
    }

    public function mahasiswaDestroy(Mahasiswa $mahasiswa)
    {
        try {
            DB::transaction(function () use ($mahasiswa) {

                $idUser = $mahasiswa->id_user;

                $mahasiswa->delete();

                User::where('id_user', $idUser)->delete();
            });

        } catch (QueryException $e) {

            return back()->withErrors([
                'hapus' => 'Data mahasiswa tidak dapat dihapus karena masih memiliki pengaduan.',
            ]);
        }

        return back()->with(
            'success',
            'Data mahasiswa beserta akun login berhasil dihapus.'
        );
    }


    // ---------- DATA USER ----------

    public function userStore(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:150', 'unique:user,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', Rule::in(['akademik', 'sarpras'])],
        ], [
            'role.in' => 'Role harus berupa akademik atau sarpras.',
        ]);

        User::create($data);

        return back()->with(
            'success',
            'Akun staf berhasil ditambahkan.'
        );
    }

    public function userUpdate(Request $request, User $user)
    {
        $rules = [
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('user', 'email')
                    ->ignore($user->id_user, 'id_user'),
            ],

            'password' => ['nullable', 'string', 'min:6'],
        ];

        // Role akun mahasiswa dikunci.
        if ($user->role !== 'mahasiswa') {
            $rules['role'] = [
                'required',
                Rule::in(['akademik', 'sarpras']),
            ];
        }

        $data = $request->validate($rules, [
            'role.in' => 'Role harus berupa akademik atau sarpras.',
        ]);

        // Jika password kosong, password lama tidak diubah.
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return back()->with(
            'success',
            'Data user berhasil diperbarui.'
        );
    }

    public function userDestroy(Request $request, User $user)
    {
        if ($user->id_user === $request->user()->id_user) {
            return back()->withErrors([
                'hapus' => 'Anda tidak dapat menghapus akun yang sedang digunakan.',
            ]);
        }

        try {
            DB::transaction(function () use ($user) {

                Mahasiswa::where('id_user', $user->id_user)->delete();

                $user->delete();
            });

        } catch (QueryException $e) {

            return back()->withErrors([
                'hapus' => 'Akun tidak dapat dihapus karena masih terhubung dengan data lain.',
            ]);
        }

        return back()->with(
            'success',
            'Akun berhasil dihapus.'
        );
    }
}
