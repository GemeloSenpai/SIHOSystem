<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Expediente;
use App\Models\Empleado;

class ExportarExamen extends Controller
{
    public function show($expediente_id)
    {
        $expediente = Expediente::with(['paciente.persona'])->findOrFail($expediente_id);

        // Médico solicitante
        $doctor = Empleado::find($expediente->doctor_id);
        $medicoNombre = trim(($doctor->nombre ?? '').' '.($doctor->apellido ?? ''));

        $paciente = $expediente->paciente?->persona;

        // *** OJO: tabla puente correcta: expediente_examen ***
        $examenes = DB::table('expediente_examen as ee')
            ->join('examenes_medicos as em', 'em.id_examen_medico', '=', 'ee.examen_medico_id')
            ->join('examenes as ex', 'ex.id_examen', '=', 'em.examen_id')
            ->leftJoin('categorias as c', 'c.id_categoria', '=', 'ex.categoria_id')
            ->where('ee.expediente_id', $expediente_id)
            ->select(
                'ex.id_examen',
                'ex.nombre_examen',
                DB::raw('COALESCE(c.nombre_categoria, "Sin categoría") as nombre_categoria'),
                'em.fecha_asignacion'
            )
            ->orderBy('c.nombre_categoria')
            ->orderBy('ex.nombre_examen')
            ->get();

        // Fallback por si aún no existieran filas en la tabla puente:
        if ($examenes->isEmpty() && $expediente->consulta_id) {
            $examenes = DB::table('examenes_medicos as em')
                ->join('examenes as ex', 'ex.id_examen', '=', 'em.examen_id')
                ->leftJoin('categorias as c', 'c.id_categoria', '=', 'ex.categoria_id')
                ->where('em.consulta_id', $expediente->consulta_id)
                ->select(
                    'ex.id_examen',
                    'ex.nombre_examen',
                    DB::raw('COALESCE(c.nombre_categoria, "Sin categoría") as nombre_categoria'),
                    'em.fecha_asignacion'
                )
                ->orderBy('c.nombre_categoria')
                ->orderBy('ex.nombre_examen')
                ->get();
        }

        $primeraFechaRaw = $examenes->min('fecha_asignacion');
        $fechaPrimera = $primeraFechaRaw
            ? Carbon::parse($primeraFechaRaw)->format('d/m/Y')
            : Carbon::now()->format('d/m/Y');

        $porCategorias = $examenes
            ->groupBy('nombre_categoria')
            ->map(fn($g) => $g->pluck('nombre_examen')->unique()->values()->all())
            ->toArray();

        return view('logica.admin.ver-examenes', [
            'expediente'    => $expediente,
            'paciente'      => $paciente,
            'medicoNombre'  => $medicoNombre ?: '—',
            'telefono'      => $paciente->telefono ?? '—',
            'edad'          => $paciente->edad ?? '—',
            'fecha_nacimiento' => $paciente->fecha_nacimiento ?? '—',
            'fechaPrimera'  => $fechaPrimera,
            'porCategorias' => $porCategorias,
        ]);
    }

    public function vistaExamenes($expediente_id)
    {
        $expediente = Expediente::with(['paciente.persona'])->findOrFail($expediente_id);

        // Médico solicitante
        $doctor = Empleado::find($expediente->doctor_id);
        $medicoNombre = trim(($doctor->nombre ?? '').' '.($doctor->apellido ?? ''));

        $paciente = $expediente->paciente?->persona;

        // *** OJO: tabla puente correcta: expediente_examen ***
        $examenes = DB::table('expediente_examen as ee')
            ->join('examenes_medicos as em', 'em.id_examen_medico', '=', 'ee.examen_medico_id')
            ->join('examenes as ex', 'ex.id_examen', '=', 'em.examen_id')
            ->leftJoin('categorias as c', 'c.id_categoria', '=', 'ex.categoria_id')
            ->where('ee.expediente_id', $expediente_id)
            ->select(
                'ex.id_examen',
                'ex.nombre_examen',
                DB::raw('COALESCE(c.nombre_categoria, "Sin categoría") as nombre_categoria'),
                'em.fecha_asignacion'
            )
            ->orderBy('c.nombre_categoria')
            ->orderBy('ex.nombre_examen')
            ->get();

        // Fallback por si aún no existieran filas en la tabla puente:
        if ($examenes->isEmpty() && $expediente->consulta_id) {
            $examenes = DB::table('examenes_medicos as em')
                ->join('examenes as ex', 'ex.id_examen', '=', 'em.examen_id')
                ->leftJoin('categorias as c', 'c.id_categoria', '=', 'ex.categoria_id')
                ->where('em.consulta_id', $expediente->consulta_id)
                ->select(
                    'ex.id_examen',
                    'ex.nombre_examen',
                    DB::raw('COALESCE(c.nombre_categoria, "Sin categoría") as nombre_categoria'),
                    'em.fecha_asignacion'
                )
                ->orderBy('c.nombre_categoria')
                ->orderBy('ex.nombre_examen')
                ->get();
        }

        $primeraFechaRaw = $examenes->min('fecha_asignacion');
        $fechaPrimera = $primeraFechaRaw
            ? Carbon::parse($primeraFechaRaw)->format('d/m/Y')
            : Carbon::now()->format('d/m/Y');

        $porCategorias = $examenes
            ->groupBy('nombre_categoria')
            ->map(fn($g) => $g->pluck('nombre_examen')->unique()->values()->all())
            ->toArray();

        return view('logica.admin.vista-ver-examenes', [
            'expediente'    => $expediente,
            'paciente'      => $paciente,
            'medicoNombre'  => $medicoNombre ?: '—',
            'telefono'      => $paciente->telefono ?? '—',
            'edad'          => $paciente->edad ?? '—',
            'fecha_nacimiento' => $paciente->fecha_nacimiento ?? '—',
            'fechaPrimera'  => $fechaPrimera,
            'porCategorias' => $porCategorias,
        ]);
    }
}
