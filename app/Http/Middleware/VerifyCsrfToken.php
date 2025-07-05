<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyCsrfToken
{
    protected $except = [
    'clientes',
    'clientes/*',
    'productos',
    'productos/*',
    'ventas',
    'ventas/*',
    'reservas',
    'reservas/*',
    'reportes',
    'reportes/*',
    'api/*',
    'productos/store',
    'productos/update',
    'productos/*',


];


    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}
