<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LowonganController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Lowongan/Index', [
            'activeNav' => 'lowongan',
            'divisions' => $this->divisions($request),
            'filters' => $request->only('search', 'instansi', 'bidang'),
        ]);
    }

    public function show(string $division)
    {
        $division = Division::with('positions')
            ->where('slug', $division)
            ->firstOrFail();

        return Inertia::render('Lowongan/Show', [
            'activeNav' => 'lowongan',
            'division' => $division,
        ]);
    }

    private function divisions(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        return Division::query()
            ->with('positions')
            ->when($request->filled('instansi'), fn ($query) => $query->where('instansi', $request->input('instansi')))
            ->when($request->filled('bidang'), fn ($query) => $query->where('nama', $request->input('bidang')))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nama', 'like', "%{$search}%")
                        ->orWhere('instansi', 'like', "%{$search}%")
                        ->orWhereHas('positions', fn ($position) => $position->where('nama', 'like', "%{$search}%"));
                });
            })
            ->get()
            ->map(function (Division $division) {
                $division->setAttribute('kuota_total', $division->positions->sum('kuota'));
                $division->setAttribute('terisi_total', $division->positions->sum('terisi'));
                $division->setAttribute('posisi_tersedia', $division->positions->filter(fn ($position) => $position->terisi < $position->kuota)->count());
                $division->setAttribute('penuh', $division->positions->isNotEmpty() && $division->positions->every(fn ($position) => $position->terisi >= $position->kuota));

                return $division;
            });
    }
}
