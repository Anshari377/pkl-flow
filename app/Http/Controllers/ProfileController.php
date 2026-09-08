<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's read-only profile.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user();

        return Inertia::render('Profile/Edit', [
            'user' => [
                'name' => $user?->name,
                'email' => $user?->email,
                'avatar' => $user?->avatar,
            ],
            'activeNav' => 'profile',
        ]);
    }
}

