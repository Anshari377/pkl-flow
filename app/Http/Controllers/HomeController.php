<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use App\Models\PengajuanPkl;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $pengajuanAktif = PengajuanPkl::with('position.division')
            ->where('user_id', $userId)
            ->whereNotIn('status', ['diterima', 'ditolak'])
            ->latest()
            ->first();

        return Inertia::render('Home', [
            'activeNav' => 'home',
            'stats' => [
                'lowongan_tersedia' => Lowongan::where('status', 'buka')->count(),
                'pendaftaran' => PengajuanPkl::where('user_id', $userId)->count(),
                'menunggu_verifikasi' => PengajuanPkl::where('user_id', $userId)
                    ->whereIn('status', ['diajukan', 'berkas_diterima', 'diverifikasi'])->count(),
                'diterima' => PengajuanPkl::where('user_id', $userId)->where('status', 'diterima')->count(),
            ],
            'pendaftaranAktif' => $pengajuanAktif ? [
                'judul' => $pengajuanAktif->position->nama,
                'instansi' => $pengajuanAktif->position->division->instansi,
                'tanggal' => $pengajuanAktif->created_at->translatedFormat('d M Y'),
                'status' => $pengajuanAktif->status,
            ] : null,
            'pengumuman' => [], // isi dari tabel pengumuman bila sudah dibuat
        ]);
    }
}
