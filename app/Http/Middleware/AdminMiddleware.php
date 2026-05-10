<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log para depuración
        \Log::info('AdminMiddleware check', [
            'auth' => auth()->check(),
            'user' => auth()->user() ? auth()->user()->only(['id', 'name', 'role']) : null,
            'path' => $request->path()
        ]);

        if (!auth()->check() || !auth()->user()->isAdmin()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['message' => 'Acceso denegado. Se requieren permisos de administrador.'], 403);
            }
            return redirect()->route('dashboard')->with('error', 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
