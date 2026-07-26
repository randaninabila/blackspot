<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifikatorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || (!auth()->user()->isVerifikator() && !auth()->user()->isAdmin())) {
            abort(403, 'Akses ditolak. Hanya Verifikator atau Admin yang diizinkan.');
        }
        return $next($request);
    }
}
