<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        Division::updateOrCreate(
            ['slug' => 'aplikasi-layanan-e-government'],
            [
                'nama' => 'Aplikasi dan Layanan E-Government',
                'kategori' => 'Teknologi Informasi',
                'instansi' => 'Dinas Komunikasi dan Informatika',
                'deskripsi' => 'Bertanggung jawab atas pengembangan, pemeliharaan, dan peningkatan layanan aplikasi digital serta sistem elektronik pemerintahan (e-Government) untuk mendukung pelayanan publik yang efisien dan transparan.',
            ]
        );

        Division::updateOrCreate(
            ['slug' => 'sekretariat'],
            [
                'nama' => 'Sekretariat',
                'kategori' => 'Administrasi',
                'instansi' => 'Dinas Komunikasi dan Informatika',
                'deskripsi' => 'Menangani administrasi umum, kepegawaian, tata usaha, dan dukungan operasional internal dinas.',
            ]
        );
    }
}
