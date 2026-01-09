<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LicenciaController extends Controller
{
    public function form()
    {
        // Licencia activa (la más vigente)
        $lic = \Illuminate\Support\Facades\DB::table('licencias')
            ->where('estado','activa')
            ->where(function($q){
                $q->whereNull('expira_en')->orWhere('expira_en','>', now());
            })
            ->orderByDesc('expira_en')
            ->first();

        // Últimos 4 de la clave (si existe y se puede descifrar)
        $terminaEn = null;
        if ($lic && $lic->clave_enc) {
            try {
                $plain = \Illuminate\Support\Facades\Crypt::decryptString($lic->clave_enc);
                $terminaEn = \Illuminate\Support\Str::of($plain)->substr(-1);
            } catch (\Throwable $e) {
                // noop
            }
        }

        // Trial (si usas trial como licencia en la misma tabla)
        $trial = \Illuminate\Support\Facades\DB::table('licencias')
            ->where('tipo','trial')
            ->where('estado','activa')
            ->orderByDesc('id_licencias')
            ->first();

        // Valores seguros para la vista (null-safe)
        $trialVivo = false;
        $trialDays = 0;
        if ($trial && $trial->expira_en) {
            $trialVivo = \Carbon\Carbon::now()->lessThan(\Carbon\Carbon::parse($trial->expira_en));
            $trialDays = $trialVivo
                ? \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($trial->expira_en))
                : 0;
        }

        return view('licencia.activar', [
            'lic'        => $lic,
            'terminaEn'  => $terminaEn,
            'trial'      => $trial,
            'trialVivo'  => $trialVivo,
            'trialDays'  => $trialDays,
        ]);
    }


    public function activar(\Illuminate\Http\Request $request)
{
    // 1) Validación y normalización (manteniendo GUIONES)
    $raw = strtoupper(trim((string) $request->input('clave')));

    // Acepta AAAA-BBBB-CCCC (4–6 por bloque, con guiones)
    if (!preg_match('/^[A-Z0-9]{4,6}(-[A-Z0-9]{4,6}){2}$/', $raw)) {
        return back()
            ->withErrors(['clave' => 'Formato inválido. Use AAAA-BBBB-CCCC.'])
            ->withInput();
    }

    // 2) Resolver tipo según hashes maestros del .env (con guiones)
    $candidatos = [
        'trial'      => [trim((string) env('LICENSE_TRIAL_HASH')), (int) env('LICENSE_TRIAL_YEARS', 1)],
        'pro'        => [trim((string) env('LICENSE_PRO_HASH')),   (int) env('LICENSE_PRO_YEARS',   2)],
        'enterprise' => [trim((string) env('LICENSE_ENT_HASH')),   (int) env('LICENSE_ENT_YEARS',   4)],
        'demo'       => [trim((string) env('LICENSE_DEMO_HASH')),  (int) env('LICENSE_DEMO_YEARS',  0)], // 0 = permanente
    ];

    $tipo = null; $years = null;
    foreach ($candidatos as $t => [$hashMaestro, $yrs]) {
        if ($hashMaestro && \Illuminate\Support\Facades\Hash::check($raw, $hashMaestro)) { // <= con GUIONES
            $tipo = $t; $years = $yrs; break;
        }
    }

    if (!$tipo) {
        return back()
            ->withErrors(['clave' => 'Clave de licencia incorrecta.'])
            ->withInput();
    }

    // 3) Preparar datos seguros (todo usando la clave CON GUIONES)
    $ahora      = now();
    $expiraEn   = $years > 0 ? (clone $ahora)->addYears($years) : null;
    $claveSha   = hash('sha256', $raw);                                         // huella rápida (con guiones)
    $claveEnc   = \Illuminate\Support\Facades\Crypt::encryptString($raw);       // cifrado reversible (con guiones)
    $claveHash  = \Illuminate\Support\Facades\Hash::make($raw);                 // hash lento (bcrypt/argon)
    $dominio    = request()->getHost();
    $hostname   = gethostname();

    // 4) Persistir (transacción)
    \Illuminate\Support\Facades\DB::beginTransaction();
    try {
        // Política: expirar cualquier activa previa (una activa a la vez)
        \Illuminate\Support\Facades\DB::table('licencias')
            ->where('estado', 'activa')
            ->update([
                'estado'     => 'expirada',   // o 'suspendida' si prefieres
                'updated_at' => $ahora,
            ]);

        // Upsert por huella para no duplicar la misma clave (con guiones)
        \Illuminate\Support\Facades\DB::table('licencias')->updateOrInsert(
            ['clave_fp' => $claveSha],
            [
                'tipo'        => $tipo,
                'estado'      => 'activa',
                'clave'       => null,        // nunca guardes texto plano
                'clave_enc'   => $claveEnc,
                'clave_hash'  => $claveHash,
                'dominio'     => $dominio,
                'hostname'    => $hostname,
                'activada_en' => $ahora,
                'expira_en'   => $expiraEn,   // null => permanente
                'updated_at'  => $ahora,
                'created_at'  => $ahora,
            ]
        );

        \Illuminate\Support\Facades\DB::commit();
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\DB::rollBack();
        \Log::error('Error activando licencia', ['e' => $e->getMessage()]);
        return back()->with('alerta', 'No se pudo activar la licencia. Intente nuevamente.');
    }

    // 5) Feedback
    \Illuminate\Support\Facades\Cache::forget('licencia_valida');

    $msgDur = $expiraEn ? ('Válida hasta: ' . $expiraEn->format('Y-m-d H:i') . '.') : 'Sin fecha de vencimiento.';
    return redirect()->route('licencia.form')->with('ok', 'Activación exitosa. '.$msgDur);
}




}
