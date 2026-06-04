<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class MahasiswaAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::has('mahasiswa')) {
            return redirect()->route('mahasiswa.login.form');
        }

        return $next($request);
    }
}