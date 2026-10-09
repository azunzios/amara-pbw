# web-bps-api (Laravel + Bootstrap) — BPS Provinsi Jawa Timur

Versi Laravel dari proyek PHP `webbps` (Modul 12): login, daftar, tambah, edit, hapus publikasi, pencarian judul, dan galeri.

## Menjalankan di lokal (XAMPP)
1. Nyalakan MySQL di XAMPP, buat database `webbps_laravel` (sudah sesuai `.env`).
2. `composer install` dan `npm install`
3. `php artisan migrate:fresh --seed`   (membuat tabel `users` + `publikasis`, akun admin, dan 5 data contoh)
4. `php artisan serve` lalu di terminal lain `npm run dev`
5. Buka http://localhost:8000 — login: `admin` / `admin123`

## Deploy
Set `APP_ENV=production`, `APP_DEBUG=false`, isi kredensial DB, dan `ADMIN_PASSWORD` di `.env` sebelum `php artisan migrate --seed --force`.
Jalankan `npm run build` (bukan `npm run dev`) lalu arahkan document root ke folder `public/`.
