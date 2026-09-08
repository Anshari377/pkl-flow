<?php

namespace App\Http\Controllers;

use App\Models\Kelompok;
use Illuminate\Http\Request;
use Inertia\Inertia;

class KelompokController extends Controller
{
    public function index(Request $request)
    {
        $kelompok = Kelompok::with('anggota')
            ->firstOrCreate(['user_id' => $request->user()->id]);

        return Inertia::render('Kelompok/Index', [
            'activeNav' => 'kelompok',
            'kelompok' => $kelompok,
            'anggota' => $kelompok->anggota,
        ]);
    }

    public function storeAnggota(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'nim' => 'required|string|max:30',
        ]);

        $kelompok = Kelompok::firstOrCreate(['user_id' => $request->user()->id]);
        $kelompok->anggota()->create($validated);

        return back()->with('success', 'Anggota berhasil ditambahkan.');
    }
}
