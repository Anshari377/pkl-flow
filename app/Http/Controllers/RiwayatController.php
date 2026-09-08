<?php

namespace App\Http\Controllers;

use App\Models\PengajuanPkl;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $riwayat = PengajuanPkl::with('position.division')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(function (PengajuanPkl $item) {
                return [
                    'id' => $item->id,
                    'status' => $item->status,
                    'created_at' => $item->created_at?->format('d M Y'),
                    'bidang' => $item->position?->division?->nama ?? 'Data tidak tersedia',
                    'posisi' => $item->position?->nama ?? 'Data tidak tersedia',
                    'instansi' => $item->position?->division?->instansi ?? 'Data tidak tersedia',
                ];
            });

        return Inertia::render('Riwayat/Index', [
            'activeNav' => 'riwayat',
            'riwayat' => $riwayat,
        ]);
    }
}
