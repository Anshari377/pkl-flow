<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTipeKelompok
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->tipe_pendaftaran === 'individu') {
            return redirect()->route('home')->with('error', 'Halaman ini hanya untuk pendaftaran tipe kelompok.');
        }

        return $next($request);
    }
}
