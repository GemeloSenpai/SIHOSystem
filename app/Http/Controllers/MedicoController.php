<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\{Paciente, Persona, Receta, RelacionPacienteEncargado, SignosVitales, ConsultaDoctor, Empleado};
use Illuminate\Support\Facades\DB;
use App\Models\Expediente;
use App\Models\ExamenMedico;
use App\Models\ExpedienteExamen;
use App\Models\Categoria;
use App\Models\Examen;
use Illuminate\Support\Facades\Log;


class MedicoController extends Controller
{



    public function vistaRegistrarConsulta(Request $request)
    {
        $buscar   = trim($request->query('buscar', ''));
        $selectId = $request->query('select');

        $pacienteSeleccionado = null;
        $signos = null;
        $tipoConsulta = null;
        $ultimaFechaSignos = null;
        $encargado = null;

        // =========================
        // LISTA DE PACIENTES (TABLA)
        // =========================
        $pacientes = Paciente::query()
            ->with([
                'persona',
                // Carga la última relación con el encargado y su persona
                'ultimaRelacionConEncargado'
            ])
            ->whereHas('signosVitales')
            ->when($buscar !== '', function ($q) use ($buscar) {
                $q->whereHas('persona', function ($p) use ($buscar) {
                    $p->where('nombre', 'like', "%{$buscar}%")
                        ->orWhere('apellido', 'like', "%{$buscar}%")
                        ->orWhere('dni', 'like', "%{$buscar}%");
                });
            })
            // Subconsulta para última fecha de signos
            ->addSelect([
                'ultima_fecha_registro' => SignosVitales::select('fecha_registro')
                    ->whereColumn('paciente_id', 'pacientes.id_paciente')
                    ->orderByDesc('fecha_registro')
                    ->limit(1)
            ])
            // Subconsulta alternativa para tipo_consulta como respaldo
            ->addSelect([
                'ultimo_tipo_consulta' => DB::table('relacion_paciente_encargado')
                    ->select('tipo_consulta')
                    ->whereColumn('paciente_id', 'pacientes.id_paciente')
                    ->orderByDesc('fecha_visita')
                    ->limit(1)
            ])
            ->orderByDesc('ultima_fecha_registro')
            ->orderByDesc('pacientes.id_paciente')
            ->paginate(10)
            ->appends(['buscar' => $buscar]);

        // =========================
        // PACIENTE SELECCIONADO
        // =========================
        if ($selectId) {
            $pacienteSeleccionado = Paciente::with([
                'persona',
                'ultimaRelacionConEncargado'
            ])->findOrFail($selectId);

            $signos = SignosVitales::where('paciente_id', $pacienteSeleccionado->id_paciente)
                ->orderByDesc('fecha_registro')
                ->first();

            // Obtiene la última relación (ya viene cargada)
            $relacion = $pacienteSeleccionado->ultimaRelacionConEncargado;

            $tipoConsulta = $relacion?->tipo_consulta ?? '—';

            $ultimaFechaSignos = $signos?->fecha_registro
                ? \Carbon\Carbon::parse($signos->fecha_registro)->format('d/m/Y H:i')
                : '—';

            $encargado = $relacion?->encargado?->persona;
        }

        // =========================
        // CATEGORÍAS
        // =========================
        $categorias = Categoria::select('id_categoria', 'nombre_categoria')
            ->orderBy('nombre_categoria')
            ->get();

        // =========================
        // DEBUG: Verificar datos en los logs
        // =========================
        Log::debug('Total pacientes:', ['count' => $pacientes->count()]);

        foreach ($pacientes->take(5) as $index => $paciente) {
            Log::debug("Paciente {$index}:", [
                'id' => $paciente->id_paciente,
                'codigo' => $paciente->codigo_paciente,
                'ultima_relacion' => $paciente->ultimaRelacionConEncargado ? [
                    'tipo_consulta' => $paciente->ultimaRelacionConEncargado->tipo_consulta,
                    'fecha_visita' => $paciente->ultimaRelacionConEncargado->fecha_visita
                ] : null,
                'ultimo_tipo_consulta_subquery' => $paciente->ultimo_tipo_consulta
            ]);
        }

        // =========================
        // VISTA
        // =========================
        return view('logica.medico.registrar-consulta', compact(
            'pacientes',
            'pacienteSeleccionado',
            'signos',
            'tipoConsulta',
            'ultimaFechaSignos',
            'encargado',
            'categorias'
        ));
    }



    public function buscarPacientes(Request $request)
    {
        $request->validate(['buscar' => 'required|string|min:2']);

        $query = $request->input('buscar');

        $pacientes = Paciente::with(['persona', 'relacionesConEncargado'])
            ->whereHas('persona', function ($q) use ($query) {
                $q->where('nombre', 'like', "%$query%")
                    ->orWhere('dni', 'like', "%$query%");
            })
            ->orderByDesc(
                RelacionPacienteEncargado::select('fecha_visita')
                    ->whereColumn('paciente_id', 'pacientes.id_paciente')
                    ->orderByDesc('fecha_visita')
                    ->limit(1)
            )
            ->paginate(10);

        return view('logica.medico.registrar-consulta', compact('pacientes'));
    }

    public function mostrarFormularioConsulta($id_paciente)
    {
        $paciente = Paciente::with('persona')->findOrFail($id_paciente);

        $relacion = RelacionPacienteEncargado::where('paciente_id', $id_paciente)
            ->orderByDesc('fecha_visita')
            ->first();

        $encargado = optional($relacion?->encargado?->persona);
        $signos = SignosVitales::where('paciente_id', $id_paciente)->orderByDesc('fecha_registro')->first();

        return view('logica.medico.formulario-consulta', compact('paciente', 'relacion', 'encargado', 'signos'));
    }

    public function buscarSignosVitales($paciente_id)
    {
        $signos = SignosVitales::where('paciente_id', $paciente_id)->orderByDesc('fecha_registro')->first();
        return response()->json($signos);
    }

    public function guardarConsultaMedica(Request $request)
    {
        DB::beginTransaction();

        try {
            // validaciones de las entradas
            $request->validate([
                'paciente_id'       => 'required|exists:pacientes,id_paciente',
                'signos_vitales_id' => 'required|exists:signos_vitales,id_signos_vitales',
                'resumen_clinico'   => 'required',
                'impresion_diagnostica' => 'required',
                'indicaciones'      => 'required',
                'urgencia'          => 'required|in:si,no',
                'tipo_urgencia'     => 'required|in:medica,pediatrica,quirurgico,gineco obstetrica',
                'resultado'         => 'required|in:alta,seguimiento,referido',
                'citado'            => 'nullable|date',
                'firma_sello'       => 'required|in:si,no',
            ]);

            $doctor = Empleado::where('user_id', Auth::id())->firstOrFail();

            $consulta = ConsultaDoctor::create([
                'paciente_id'       => $request->paciente_id,
                'doctor_id'         => $doctor->id_empleado,
                'signos_vitales_id' => $request->signos_vitales_id,
                'resumen_clinico'   => $request->resumen_clinico,
                'impresion_diagnostica' => $request->impresion_diagnostica,
                'indicaciones'      => $request->indicaciones,
                'urgencia'          => $request->urgencia,
                'tipo_urgencia'     => $request->tipo_urgencia,
                'resultado'         => $request->resultado,
                'citado'            => $request->citado,
                'firma_sello'       => $request->firma_sello,
            ]);

            $signos = SignosVitales::findOrFail($request->signos_vitales_id);

            $relacion = RelacionPacienteEncargado::where('paciente_id', $request->paciente_id)
                ->orderByDesc('fecha_visita')
                ->first();

            $encargado_id = optional($relacion)->encargado_id;
            $enfermera_id = $signos->enfermera_id;

            DB::commit();

            $paciente = Paciente::with('persona')->findOrFail($request->paciente_id);
            $nombreCompleto = $paciente->persona->nombre . ' ' . $paciente->persona->apellido;

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'ok'           => true,
                    'consulta_id'  => $consulta->id_consulta_doctor,
                    'doctor_id'    => $doctor->id_empleado,
                    'enfermera_id' => $enfermera_id,
                    'paciente'     => $nombreCompleto,
                    'message'      => 'Consulta médica registrada para el paciente ' . $nombreCompleto . '.',
                ]);
            }

            return redirect()->route('medico.expediente.vista', [
                'paciente_id'       => $request->paciente_id,
                'consulta_id'       => $consulta->id_consulta_doctor,
                'doctor_id'         => $doctor->id_empleado,
                'signos_vitales_id' => $signos->id_signos_vitales,
                'encargado_id'      => $encargado_id,
                'enfermera_id'      => $enfermera_id
            ])->with('success', 'Consulta médica registrado para ' . $nombreCompleto . '.');
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'ok'      => false,
                    'message' => 'Error al guardar consulta: ' . $e->getMessage(),
                ], 422);
            }

            return back()->withErrors(['error' => 'Error al guardar consulta: ' . $e->getMessage()]);
        }
    }

    /*
    public function guardarExpediente(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'paciente_id'       => 'required|exists:pacientes,id_paciente',
                'signos_vitales_id' => 'required|exists:signos_vitales,id_signos_vitales',
                'doctor_id'         => 'required|exists:empleados,id_empleado',
                'consulta_id'       => 'required|exists:consulta_doctor,id_consulta_doctor',
                'encargado_id'      => 'nullable|exists:encargados,id_encargado',
                'enfermera_id'      => 'required|exists:empleados,id_empleado',
                'estado'            => 'required|in:abierto,cerrado',
                'motivo_ingreso'    => 'nullable',
                'diagnostico'       => 'nullable',
                'observaciones'     => 'nullable',
                'crear_receta'      => 'nullable|in:0,1', // NUEVO: flag opcional
            ]);

            $ultimo = Expediente::orderByDesc('id_expediente')->first();
            $nuevoCodigo = 'EXP-' . str_pad(($ultimo->id_expediente ?? 0) + 1, 3, '0', STR_PAD_LEFT);

            $expediente = Expediente::create([
                'paciente_id'       => $request->paciente_id,
                'encargado_id'      => $request->encargado_id,
                'enfermera_id'      => $request->enfermera_id,
                'signos_vitales_id' => $request->signos_vitales_id,
                'doctor_id'         => $request->doctor_id,
                'consulta_id'       => $request->consulta_id,
                'codigo'            => $nuevoCodigo,
                'estado'            => $request->estado,
                'motivo_ingreso'    => $request->motivo_ingreso,
                'diagnostico'       => $request->diagnostico,
                'observaciones'     => $request->observaciones,
                'fecha_creacion'    => now(),
            ]);

            DB::commit();

            $paciente = Paciente::with('persona')->findOrFail($request->paciente_id);
            $nombre = $paciente->persona->nombre . ' ' . $paciente->persona->apellido;

            if ($request->wantsJson() || $request->ajax()) {
                // Para peticiones AJAX, devolver JSON con información de redirección
                $response = [
                    'ok'            => true,
                    'expediente_id' => $expediente->id_expediente,
                    'codigo'        => $expediente->codigo,
                    'paciente'      => $nombre,
                    'message'       => 'Expediente ' . $expediente->codigo . ' creado para ' . $nombre . '.',
                ];

                // Si se solicitó crear receta, agregar flag de redirección
                if ($request->has('crear_receta') && $request->crear_receta == '1') {
                    $response['crear_receta'] = true;
                    $response['redirect_url'] = route('medico.recetas.crear', $expediente->id_expediente);
                }

                return response()->json($response);
            }

            // ========== LÓGICA DE REDIRECCIÓN PARA PETICIONES NORMALES ==========
            // Si el usuario marcó "crear_receta", redirigir a creación de receta
            if ($request->has('crear_receta') && $request->crear_receta == '1') {
                return redirect()
                    ->route('medico.recetas.crear', $expediente->id_expediente)
                    ->with('success', '✅ Expediente ' . $expediente->codigo . ' creado. Ahora puedes crear la receta médica.');
            }

            // Si NO quiere receta, continuar con el flujo original (asignar exámenes)
            return redirect()
                ->route('medico.expediente.asignar', $expediente->id_expediente)
                ->with('success', 'Expediente ' . $expediente->codigo . ' creado para ' . $nombre . '.');
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'ok'      => false,
                    'message' => 'Error al guardar expediente: ' . $e->getMessage(),
                ], 422);
            }

            return back()->withErrors(['error' => 'Error al guardar expediente: ' . $e->getMessage()]);
        }
    }
        */
    public function guardarExpediente(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'paciente_id'       => 'required|exists:pacientes,id_paciente',
                'signos_vitales_id' => 'required|exists:signos_vitales,id_signos_vitales',
                'doctor_id'         => 'required|exists:empleados,id_empleado',
                'consulta_id'       => 'required|exists:consulta_doctor,id_consulta_doctor',
                'encargado_id'      => 'nullable|exists:encargados,id_encargado',
                'enfermera_id'      => 'required|exists:empleados,id_empleado',
                'estado'            => 'required|in:abierto,cerrado',
                'motivo_ingreso'    => 'nullable',
                'diagnostico'       => 'nullable',
                'observaciones'     => 'nullable',
                'crear_receta'      => 'nullable|in:0,1',
            ]);

            $ultimo = Expediente::orderByDesc('id_expediente')->first();
            $nuevoCodigo = 'EXP-' . str_pad(($ultimo->id_expediente ?? 0) + 1, 3, '0', STR_PAD_LEFT);

            // ========== CREAR EXPEDIENTE ==========
            $expediente = Expediente::create([
                'paciente_id'       => $request->paciente_id,
                'encargado_id'      => $request->encargado_id,
                'enfermera_id'      => $request->enfermera_id,
                'signos_vitales_id' => $request->signos_vitales_id,
                'doctor_id'         => $request->doctor_id,
                'consulta_id'       => $request->consulta_id,
                'codigo'            => $nuevoCodigo,
                'estado'            => $request->estado,
                'motivo_ingreso'    => $request->motivo_ingreso,
                'diagnostico'       => $request->diagnostico,
                'observaciones'     => $request->observaciones,
                'fecha_creacion'    => now(),
            ]);

            // ========== CREAR RECETA AUTOMÁTICAMENTE ==========
            $consulta = ConsultaDoctor::find($request->consulta_id);

            if ($consulta) {
                // Obtener el paciente para datos adicionales
                $paciente = Paciente::with('persona')->find($request->paciente_id);

                // Crear receta automática
                Receta::create([
                    'expediente_id' => $expediente->id_expediente,
                    'paciente_id' => $expediente->paciente_id,
                    'doctor_id' => $expediente->doctor_id,
                    'fecha_prescripcion' => now(),
                    'receta' => $consulta->indicaciones, // ← SOLO ESTE CAMPO SE LLENA DE CONSULTA
                    'diagnostico' => null, // ← NULL
                    'observaciones' => null, // ← NULL
                    'edad_paciente_en_receta' => $paciente->persona->edad ?? null, // ← Edad del paciente si existe
                    'peso_paciente_en_receta' => null, // ← NULL (se puede llenar después si se quiere)
                    'alergias_conocidas' => null, // ← NULL
                    'estado' => 'activa',
                    'creado_por' => Auth::id()
                ]);
            }

            DB::commit();

            $paciente = Paciente::with('persona')->findOrFail($request->paciente_id);
            $nombre = $paciente->persona->nombre . ' ' . $paciente->persona->apellido;

            if ($request->wantsJson() || $request->ajax()) {
                // Para peticiones AJAX, devolver JSON con información de redirección
                $response = [
                    'ok'            => true,
                    'expediente_id' => $expediente->id_expediente,
                    'codigo'        => $expediente->codigo,
                    'paciente'      => $nombre,
                    'message'       => 'Expediente ' . $expediente->codigo . ' creado para ' . $nombre . '. Receta creada automáticamente.',
                ];

                // IMPORTANTE: Ya NO redirigir a creación de receta
                // Solo confirmar que se creó automáticamente
                $response['receta_creada'] = true;
                $response['message'] .= ' Receta creada automáticamente.';

                return response()->json($response);
            }

            // ========== REDIRECCIÓN PARA PETICIONES NORMALES ==========
            // Ya NO redirigir a creación de receta, redirigir a gestión de expedientes
            // Cambiar la redirección final:
            return redirect()->route('expedientes.index')
                ->with('success', '✅ Expediente ' . $expediente->codigo . ' creado. Receta generada automáticamente.');
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'ok'      => false,
                    'message' => 'Error al guardar expediente: ' . $e->getMessage(),
                ], 422);
            }

            return back()->withErrors(['error' => 'Error al guardar expediente: ' . $e->getMessage()]);
        }
    }


    public function vistaAsignarExamenes(Request $request, $expediente_id)
    {
        $expediente = Expediente::with('paciente.persona')->findOrFail($expediente_id);
        $categorias = Categoria::orderBy('nombre_categoria')->get();

        $examenes = Examen::with('categoria')
            ->when($request->input('buscar'), function ($q, $buscar) {
                $q->where('nombre_examen', 'like', '%' . $buscar . '%');
            })
            ->when($request->input('categoria_id'), function ($q, $cat) {
                $q->where('categoria_id', $cat);
            })
            ->orderBy('nombre_examen')
            ->paginate(50)
            ->withQueryString();

        return view('logica.medico.asignar-examenes', compact('expediente', 'categorias', 'examenes'))
            ->with('examenesSeleccionados', session('examenes_seleccionados', []));
    }

    public function vistaRegistrarExpediente(Request $request)
    {
        return view('logica.medico.registrar-expediente', [
            'paciente_id'       => $request->paciente_id,
            'consulta_id'       => $request->consulta_id,
            'doctor_id'         => $request->doctor_id,
            'signos_vitales_id' => $request->signos_vitales_id,
            'encargado_id'      => $request->encargado_id,
            'enfermera_id'      => $request->enfermera_id
        ]);
    }

    public function guardarExamenes(Request $request, $expediente_id)
    {
        $accion = $request->input('accion');

        // No asignar exámenes
        if ($accion === 'no_asignar') {
            $expediente = Expediente::with('paciente.persona')->findOrFail($expediente_id);
            $nombre = $expediente->paciente->persona->nombre . ' ' . $expediente->paciente->persona->apellido;
            $codigo = $expediente->codigo;

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'ok'         => true,
                    'no_asignar' => true,
                    'codigo'     => $codigo,
                    'paciente'   => $nombre,
                    'message'    => 'ℹ️ Expediente ' . $codigo . ' del paciente ' . $nombre . ' sin exámenes.',
                ]);
            }

            return redirect()->route('medico.consulta.form')
                ->with('success', 'ℹ️ Expediente ' . $codigo . ' del paciente ' . $nombre . ' sin exámenes.');
        }

        // Asignar exámenes
        DB::beginTransaction();

        try {
            $request->validate([
                'paciente_id' => 'required|exists:pacientes,id_paciente',
                'consulta_id' => 'required|exists:consulta_doctor,id_consulta_doctor',
                'examenes'    => 'required|array|min:1',
            ]);

            $doctor = Empleado::where('user_id', Auth::id())->firstOrFail();

            foreach ((array) $request->examenes as $id_examen) {
                $examenMedico = ExamenMedico::create([
                    'paciente_id'      => $request->paciente_id,
                    'doctor_id'        => $doctor->id_empleado,
                    'consulta_id'      => $request->consulta_id,
                    'examen_id'        => $id_examen,
                    'fecha_asignacion' => now()
                ]);

                ExpedienteExamen::create([
                    'expediente_id'     => $expediente_id,
                    'examen_medico_id'  => $examenMedico->id_examen_medico
                ]);
            }

            DB::commit();

            $expediente = Expediente::with('paciente.persona')->findOrFail($expediente_id);
            $nombre   = $expediente->paciente->persona->nombre . ' ' . $expediente->paciente->persona->apellido;
            $codigo   = $expediente->codigo;
            $cantidad = count($request->examenes);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'ok'       => true,
                    'cantidad' => $cantidad,
                    'codigo'   => $codigo,
                    'paciente' => $nombre,
                    'message'  => '' . $cantidad . ' examen(es) asignados al expediente ' . $codigo . '.',
                ]);
            }

            return redirect()
                ->route('medico.consulta.form')
                ->with('success', '' . $cantidad . ' examen(es) asignados al expediente ' . $codigo . '.');
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'ok'      => false,
                    'message' => 'Error al asignar exámenes: ' . $e->getMessage(),
                ], 422);
            }

            return back()->withErrors(['error' => 'Error al asignar exámenes: ' . $e->getMessage()]);
        }
    }

    // En MedicoController.php
    public function guardarExamenesDinamico(Request $request, $expediente_id)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'paciente_id' => 'required|exists:pacientes,id_paciente',
                'consulta_id' => 'required|exists:consulta_doctor,id_consulta_doctor',
                'examenes'    => 'required|array|min:1',
                'accion'      => 'required|in:guardar,terminar'
            ]);

            $doctor = Empleado::where('user_id', Auth::id())->firstOrFail();
            $examenesAsignados = [];

            foreach ((array) $request->examenes as $id_examen) {
                $examenMedico = ExamenMedico::create([
                    'paciente_id'      => $request->paciente_id,
                    'doctor_id'        => $doctor->id_empleado,
                    'consulta_id'      => $request->consulta_id,
                    'examen_id'        => $id_examen,
                    'fecha_asignacion' => now()
                ]);

                ExpedienteExamen::create([
                    'expediente_id'     => $expediente_id,
                    'examen_medico_id'  => $examenMedico->id_examen_medico
                ]);

                $examenesAsignados[] = $id_examen;
            }

            DB::commit();

            $expediente = Expediente::with('paciente.persona')->findOrFail($expediente_id);
            $cantidad = count($request->examenes);

            // Siempre devolver JSON para AJAX
            return response()->json([
                'ok'       => true,
                'accion'   => $request->accion,
                'cantidad' => $cantidad,
                'message'  => '' . $cantidad . ' examen(es) asignados correctamente.',
                'examenes_asignados' => $examenesAsignados
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'ok'      => false,
                'message' => 'Error al asignar exámenes: ' . $e->getMessage(),
            ], 422);
        }
    }

    // === JSON para el buscador integrado de exámenes ===
    public function buscarExamenes(Request $request)
    {
        $query = Examen::with('categoria');

        if ($request->filled('buscar')) {
            $query->where('nombre_examen', 'like', '%' . $request->buscar . '%');
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        $resultados = $query->orderBy('nombre_examen')->get()->map(function ($ex) {
            return [
                'id_examen'        => $ex->id_examen,
                'nombre_examen'    => $ex->nombre_examen,
                'nombre_categoria' => $ex->categoria?->nombre_categoria ?? 'Sin categoría'
            ];
        });

        return response()->json($resultados);
    }
}
