<?php

namespace Database\Seeders;

use App\Models\Publikasi;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun login. Ganti password lewat ADMIN_PASSWORD di .env sebelum deploy.
        User::updateOrCreate(
            ['username' => 'admin'],
            ['name' => 'Administrator', 'password' => env('ADMIN_PASSWORD', 'admin123')]
        );

        // Data contoh (judul diambil dari gambar sampul; tanggal & abstraksi hanya contoh)
        if (Publikasi::count() === 0) {
            $contoh = [
                ['Statistik Pemuda Provinsi Jawa Timur 2025', '2025-12-15', 'Cover1.webp'],
                ['Keadaan Angkatan Kerja Provinsi Jawa Timur Agustus 2025', '2025-11-05', 'Cover2.webp'],
                ['Direktori Perusahaan Pertanian (DPP) Provinsi Jawa Timur 2025', '2025-10-20', 'Cover3.webp'],
                ['Statistik Pendidikan Provinsi Jawa Timur 2025', '2025-09-30', 'Cover4.webp'],
                ['Analisis Hasil Survei Kebutuhan Data 2025', '2025-08-25', 'Cover5.webp'],
            ];

            foreach ($contoh as [$judul, $tanggal, $sampul]) {
                Publikasi::create([
                    'judul' => $judul,
                    'tanggal_rilis' => $tanggal,
                    'sampul' => $sampul,
                    'abstraksi' => 'Contoh abstraksi untuk publikasi "'.$judul.'". Ubah lewat tombol edit.',
                ]);
            }
        }
    }
}
