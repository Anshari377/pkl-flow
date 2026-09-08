<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class OnboardingController extends Controller
{
    public function show(Request $request)
    {
        if (! is_null($request->user()->tipe_pendaftaran)) {
            return redirect()->route('home');
        }

        return Inertia::render('Onboarding/PilihTipe');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipe' => 'required|in:individu,kelompok',
        ]);

        $request->user()->update([
            'tipe_pendaftaran' => $validated['tipe'],
        ]);

        return redirect()->route('home');
    }
}
