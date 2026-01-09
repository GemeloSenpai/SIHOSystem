<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EnsureLicensed
{
    public function handle(Request $request, \Closure $next): Response
    {
        if (app()->runningInConsole()) {
            return $next($request);
        }

        // Rutas públicas / assets
        if (
            $request->is('licencia*') ||
            $request->is('legal/*') ||
            $request->is('up') ||
            $request->is('css/*') || $request->is('js/*') ||
            $request->is('images/*') || $request->is('img/*') ||
            $request->is('build/*') || $request->is('vendor/*') ||
            $request->is('storage/*') || $request->is('favicon.ico')
        ) {
            return $next($request);
        }

        if (!Schema::hasTable('licencias')) {
            return $next($request); // dejar migrar
        }

        $nowDb = DB::raw('CURRENT_TIMESTAMP');

        // 1) Housekeeping: marcar como expirada cualquier activa vencida (reloj de la BD)
        DB::table('licencias')
            ->where('estado', 'activa')
            ->whereNotNull('expira_en')
            ->where('expira_en', '<=', $nowDb)   // ✅ sin concatenar
            ->update([
                'estado'     => 'expirada',
                'updated_at' => now(),
            ]);

        // 2) ¿Existe una licencia válida? (activa y no vencida; NULL = permanente)
        $valida = DB::table('licencias')
            ->where('estado', 'activa')
            ->where(function ($q) use ($nowDb) {
                $q->whereNull('expira_en')
                ->orWhere('expira_en', '>', $nowDb);  // ✅ sin concatenar
            })
            ->exists();

        if ($valida) {
            return $next($request);
        }

        // Bloquear
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => 'No hay una licencia válida. Active su licencia.',
                'activate_url' => route('licencia.form'),
            ], 402);
        }

        return redirect()->route('licencia.form')
            ->with('alerta', 'No hay una licencia válida o ha expirado. Por favor, active su licencia.');
    }
}
