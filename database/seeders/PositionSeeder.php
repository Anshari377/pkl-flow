<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $applicationsDivision = Division::where('slug', 'aplikasi-layanan-e-government')->firstOrFail();
        $secretariatDivision = Division::where('slug', 'sekretariat')->firstOrFail();

        Position::updateOrCreate(
            ['division_id' => $applicationsDivision->id, 'nama' => 'Web Developer'],
            [
                'deskripsi' => 'Membantu tim mengembangkan dan memelihara aplikasi internal serta layanan publik berbasis web milik Diskominfo Kaltim.',
                'kuota' => 5,
                'terisi' => 5,
                'kualifikasi' => ['Menguasai HTML/CSS/JS dasar', 'Familiar dengan salah satu framework backend', 'Mampu bekerja dalam tim'],
                'jurusan' => ['Informatika', 'Rekayasa Perangkat Lunak (RPL)', 'Sistem Informasi', 'TKJ'],
            ]
        );

        Position::updateOrCreate(
            ['division_id' => $applicationsDivision->id, 'nama' => 'UI/UX Designer'],
            [
                'deskripsi' => 'Merancang wireframe dan antarmuka untuk layanan publik digital agar mudah digunakan masyarakat.',
                'kuota' => 3,
                'terisi' => 2,
                'kualifikasi' => ['Menguasai Figma', 'Memahami prinsip UX dasar'],
                'jurusan' => ['Desain Komunikasi Visual (DKV)', 'Informatika', 'Multimedia'],
            ]
        );

        Position::updateOrCreate(
            ['division_id' => $secretariatDivision->id, 'nama' => 'Administrasi'],
            [
                'deskripsi' => 'Membantu pengelolaan surat-menyurat, kearsipan, dan administrasi umum di lingkungan Sekretariat Diskominfo Kaltim.',
                'kuota' => 4,
                'terisi' => 0,
                'kualifikasi' => ['Teliti dan rapi dalam pengarsipan', 'Menguasai Microsoft Office dasar'],
                'jurusan' => ['Administrasi Perkantoran', 'Manajemen', 'Semua jurusan (terbuka umum)'],
            ]
        );
    }
}
