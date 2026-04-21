<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class SuperAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::id() !== 1 && Auth::user()?->email !== 'admin@gmail.com') {
            abort(403, 'Akses Ditolak: Fitur Manajemen Petugas khusus untuk Super Admin.');
        }

        return $next($request);
    }
}
