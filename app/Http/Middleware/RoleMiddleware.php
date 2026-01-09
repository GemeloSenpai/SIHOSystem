<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
     /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Si no está autenticado, redirigir al login
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        // Obtener usuario autenticado
        $user = Auth::user();
        
        // Verificar si tiene uno de los roles permitidos
        if (!in_array($user->role, $roles)) {
            abort(403, 'Acceso denegado. Rol requerido: ' . implode(', ', $roles));
        }
        
        return $next($request);
    }
}