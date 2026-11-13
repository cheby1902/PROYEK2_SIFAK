# PROYEK2_SIFAK

Sistem Informasi Pengaduan Fasilitas Kampus (SIFAK).

Aplikasi berbasis web untuk mengelola pengaduan kerusakan fasilitas kampus.

## Teknologi
- Laravel 13
- PHP 8.5
- MySQL 8.4
- Blade

## Persiapan Instalasi

1. Clone repository:
   ```bash
   git clone https://github.com/cheby1902/PROYEK2_SIFAK.git
   ```

2. Masuk ke folder proyek:
   ```bash
   cd PROYEK2_SIFAK
   ```

3. Install dependency:
   ```bash
   composer install
   ```

4. Salin `.env.example` menjadi `.env`.

5. Atur konfigurasi database MySQL pada `.env`.

6. Generate application key:
   ```bash
   php artisan key:generate
   ```

7. Impor database `db_sifak` melalui phpMyAdmin.

8. Jalankan aplikasi:
   ```bash
   php artisan serve
   ```

## Catatan Database

Database SIFAK menggunakan lima tabel:
- user
- mahasiswa
- fasilitas
- pengaduan
- riwayat_status

**Jangan menjalankan migration bawaan Laravel** pada database SIFAK yang sudah diimpor.

## Lisensi

Proyek ini dikembangkan untuk keperluan Proyek 2.