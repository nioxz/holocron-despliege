<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckFirstLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Si no está logueado, dejar pasar (el auth middleware lo atrapará después)
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        // IMPORTANTE: Evitar bucle infinito
        // Si ya está en la ruta de cambiar password o salir, lo dejamos pasar.
        if ($request->routeIs('setup.password') || $request->routeIs('setup.terms') || $request->routeIs('logout')) {
            return $next($request);
        }

        // 1. Validación: ¿Cambió contraseña?
        if (!$user->is_password_changed) {
            return redirect()->route('setup.password');
        }

        // 2. Validación: ¿Aceptó términos?
        if (!$user->terms_accepted_at) {
            return redirect()->route('setup.terms');
        }

        return $next($request);
    }
}