<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Jika sudah login sebagai dosen, redirect ke dashboard dosen
                if (Auth::user() && Auth::user()->role === 'dosen') {
                    return redirect()->route('dosen.dashboard');
                }
                // Jika sudah login sebagai mahasiswa (tapi mahasiswa pakai session sendiri)
                return redirect('/');
            }
        }

        return $next($request);
    }
}