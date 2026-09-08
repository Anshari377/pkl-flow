<?php

namespace App\Http\Controllers;

use App\Models\PengajuanPkl;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StatusPendaftaranController extends Controller
{
    public function index(Request $request)
    {
        $pendaftaran = PengajuanPkl::with('position.division')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->first();

        return Inertia::render('Status/Index', [
            'activeNav' => 'pengajuan',
            'pendaftaran' => $pendaftaran,
        ]);
    }
}
