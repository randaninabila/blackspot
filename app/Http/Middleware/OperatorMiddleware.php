<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OperatorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role !== 'operator_kabupaten') {
            abort(403, 'Akses ditolak. Hanya Operator Kabupaten yang diizinkan.');
        }
        return $next($request);
    }
}