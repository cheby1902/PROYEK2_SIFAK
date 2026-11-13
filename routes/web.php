
<?php

use App\Http\Controllers\AkademikController;
use App\Http\Controllers\SarprasController;
use App\Http\Controllers\AuthController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Halaman utama diarahkan ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// PREVIEW DASHBOARD - KHUSUS LOKAL
if (app()->environment('local')) {
    Route::get('/preview/{role}', function (string $role) {
        abort_unless(
            in_array($role, ['sarpras', 'akademik'], true),
            404
        );

        $user = User::where('role', $role)->firstOrFail();

        Auth::login($user);

        return redirect("/{$role}/dashboard");
    })->whereIn('role', ['sarpras', 'akademik']);
}

// Halaman login
Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');

});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ---------- SARPRAS ----------
Route::middleware(['auth', 'role:sarpras'])
    ->prefix('sarpras')
    ->name('sarpras.')
    ->group(function () {

        Route::get('/dashboard', [SarprasController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/pengaduan', [SarprasController::class, 'pengaduanIndex'])
            ->name('pengaduan.index');

        Route::get('/pengaduan/{pengaduan}', [SarprasController::class, 'pengaduanShow'])
            ->name('pengaduan.show');

        Route::put('/pengaduan/{pengaduan}/status', [SarprasController::class, 'updateStatus'])
            ->name('pengaduan.status');

        Route::delete('/pengaduan/{pengaduan}', [SarprasController::class, 'pengaduanDestroy'])
            ->name('pengaduan.destroy');

        Route::get('/fasilitas', [SarprasController::class, 'fasilitasIndex'])
            ->name('fasilitas.index');

        Route::post('/fasilitas', [SarprasController::class, 'fasilitasStore'])
            ->name('fasilitas.store');

        Route::put('/fasilitas/{fasilitas}', [SarprasController::class, 'fasilitasUpdate'])
            ->name('fasilitas.update');
    });

// ---------- AKADEMIK ----------
Route::middleware(['auth', 'role:akademik'])
    ->prefix('akademik')
    ->name('akademik.')
    ->group(function () {

        Route::get('/dashboard', [AkademikController::class, 'dashboard'])
            ->name('dashboard');

        Route::post('/mahasiswa', [AkademikController::class, 'mahasiswaStore'])
            ->name('mahasiswa.store');

        Route::put('/mahasiswa/{mahasiswa}', [AkademikController::class, 'mahasiswaUpdate'])
            ->name('mahasiswa.update');

        Route::delete('/mahasiswa/{mahasiswa}', [AkademikController::class, 'mahasiswaDestroy'])
            ->name('mahasiswa.destroy');

        Route::post('/user', [AkademikController::class, 'userStore'])
            ->name('user.store');

        Route::put('/user/{user}', [AkademikController::class, 'userUpdate'])
            ->name('user.update');

        Route::delete('/user/{user}', [AkademikController::class, 'userDestroy'])
            ->name('user.destroy');
    });
