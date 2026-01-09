<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Encargado;
use App\Models\Persona;
use App\Models\RelacionPacienteEncargado;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule; 


class RecepcionController extends Controller
{
    // Método storePaciente (ya tienes este, solo lo mantengo por referencia)
    public function storePaciente(Request $request)
    {
        $request->validate([
            // Paciente
            'nombre'    => 'nullable|string|max:100|regex:/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/',
            'apellido'  => 'nullable|string|max:100|regex:/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/',
            'edad'      => 'nullable|integer|min:0|max:120',
            'fecha_nacimiento' => 'nullable|date|before:today',
            'dni'       => 'nullable|string|max:13|regex:/^[0-9]{13}$/',
            'sexo'      => 'nullable|in:M,F',
            'direccion' => 'nullable|string|max:255',
            'telefono'  => 'nullable|string|max:15|regex:/^[0-9+()\-\s]+$/',
            'codigo_paciente' => 'nullable|string|max:50|unique:pacientes,codigo_paciente',

            // Encargado
            'encargado_nombre'    => 'nullable|string|max:100|regex:/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/',
            'encargado_apellido'  => 'nullable|string|max:100|regex:/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/',
            'encargado_edad'      => 'nullable|integer|min:0|max:120',
            'encargado_fecha_nacimiento' => 'nullable|date|before:today',
            'encargado_dni'       => 'nullable|string|regex:/^[0-9]{13}$/',
            'encargado_sexo'      => 'nullable|in:M,F',
            'encargado_direccion' => 'nullable|string|max:255',
            'encargado_telefono'  => 'nullable|string|max:15|regex:/^[0-9+()\-\s]+$/',

            'tipo_consulta' => 'required|in:general,especializada',
        ]);

        DB::beginTransaction();
        
        try {
            // Persona (paciente)
            $personaPaciente = Persona::create([
                'nombre'    => $request->nombre,
                'apellido'  => $request->apellido,
                'edad'      => $request->edad,
                'fecha_nacimiento' => $request->fecha_nacimiento ?: null,
                'dni'       => $request->dni,
                'sexo'      => $request->sexo,
                'direccion' => $request->direccion,
                'telefono'  => $request->telefono ?: null,
            ]);

            // Paciente - si no viene código, generarlo automáticamente
            $codigoPaciente = $request->codigo_paciente ?: Paciente::generarCodigoUnico();
            
            $paciente = Paciente::create([
                'persona_id' => $personaPaciente->id_persona,
                'codigo_paciente' => $codigoPaciente,
            ]);

            $encargado = null;

            // Encargado (solo si viene nombre y apellido)
            if ($request->encargado_nombre && $request->encargado_apellido) {
                $personaEncargado = Persona::create([
                    'nombre'    => $request->encargado_nombre,
                    'apellido'  => $request->encargado_apellido,
                    'edad'      => $request->encargado_edad ?? 0,
                    'fecha_nacimiento' => $request->encargado_fecha_nacimiento ?: null,
                    'dni'       => $request->encargado_dni,
                    'sexo'      => $request->encargado_sexo ?? 'M',
                    'direccion' => $request->encargado_direccion ?? 'N/A',
                    'telefono'  => $request->encargado_telefono ?: null,
                ]);

                $encargado = Encargado::create([
                    'persona_id' => $personaEncargado->id_persona,
                ]);
            }

            // Relación
            RelacionPacienteEncargado::create([
                'paciente_id'   => $paciente->id_paciente,
                'encargado_id'  => $encargado?->id_encargado,
                'tipo_consulta' => $request->tipo_consulta,
            ]);

            DB::commit();

            $nombreCompleto = $personaPaciente->nombre . ' ' . $personaPaciente->apellido;
            
            return redirect()
                ->back()
                ->with('success', 'Paciente "' . $nombreCompleto . '" registrado correctamente. Código: ' . $codigoPaciente);

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()
                ->back()
                ->with('error', 'Error al registrar el paciente: ' . $e->getMessage())
                ->withInput();
        }
    }

    // new 
    // Método para mostrar todos los pacientes con búsqueda
    public function verPacientes(Request $request)
    {
        $busqueda = $request->input('busqueda', '');
        
        $pacientes = Paciente::with(['persona', 'relacionPacienteEncargado' => function($q) {
            $q->latest('fecha_visita')->take(1);
        }])
        ->when($busqueda, function ($query, $busqueda) {
            // Buscar por código de paciente (parcial o exacto)
            $query->where(function($q) use ($busqueda) {
                $q->where('codigo_paciente', 'LIKE', "%{$busqueda}%")
                  ->orWhereHas('persona', function($q2) use ($busqueda) {
                      $q2->where('nombre', 'LIKE', "%{$busqueda}%")
                         ->orWhere('apellido', 'LIKE', "%{$busqueda}%")
                         ->orWhere('dni', 'LIKE', "%{$busqueda}%");
                  });
            });
        })
        ->orderBy('id_paciente', 'desc')
        ->paginate(15);
        
        return view('logica.recepcion.ver-pacientes', compact('pacientes', 'busqueda'));
    }

    public function buscar(Request $request)
    {
        $query = $request->input('query');

        $resultados = Paciente::with('persona')
            ->whereHas('persona', function ($q) use ($query) {
                $q->where('nombre', 'LIKE', '%' . $query . '%');
            })
            ->orderBy('id_paciente', 'desc')
            ->take(10)
            ->get();

        return redirect()->back()->with('resultados', $resultados);
    }

    // También actualiza el método de búsqueda individual
    // Método de búsqueda individual (ya lo tienes bien)
    public function buscarPaciente(Request $request)
    {
        $query = $request->input('query');

        $paciente = Paciente::where(function($q) use ($query) {
                $q->where('codigo_paciente', $query) // Buscar por código exacto
                  ->orWhereHas('persona', function ($q2) use ($query) {
                      $q2->where('dni', $query);
                  });
            })
            ->with('persona')
            ->first();

        if ($paciente) {
            return redirect()->back()->with('paciente_encontrado', $paciente);
        } else {
            return redirect()->back()->with('no_encontrado', 'No se encontró un paciente con ese DNI o código.');
        }
    }

    // Muestra formulario para asignar encargado a paciente
    public function agregarEncargado($id)
    {
        $paciente = Paciente::with('persona')->findOrFail($id);
        return view('logica.recepcion.agregar-encargado', compact('paciente'));
    }

    public function guardarRelacion(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id_paciente',
            'fecha_visita' => 'required|date',
            'tipo_consulta' => 'required|string',
        ]);

        $relacion = new RelacionPacienteEncargado();
        $relacion->paciente_id = $request->paciente_id;
        $relacion->encargado_id = $request->encargado_id ?? null; // null si no viene
        $relacion->fecha_visita = $request->fecha_visita;
        $relacion->tipo_consulta = $request->tipo_consulta;
        $relacion->save();

        return redirect()->route('recepcion.verPacientes')->with('success', 'Visita registrada correctamente.');
    }

    // Guarda la relación paciente - encargado con fecha visita y tipo consulta
    public function guardarRelacionEncargado(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id_paciente',
            'encargado_id' => 'required|exists:encargados,id_encargado',
            'fecha_visita' => 'required|date',
            'tipo_consulta' => 'required|string|max:255',
        ]);

        // Verificar que no exista ya la relación para esa fecha y paciente
        $existeRelacion = RelacionPacienteEncargado::where('paciente_id', $request->paciente_id)
            ->where('encargado_id', $request->encargado_id)
            ->where('fecha_visita', $request->fecha_visita)
            ->exists();

        if ($existeRelacion) {
            return redirect()->back()->with('error', 'La relación paciente - encargado ya existe para esa fecha.');
        }

        RelacionPacienteEncargado::create([
            'paciente_id' => $request->paciente_id,
            'encargado_id' => $request->encargado_id,
            'fecha_visita' => Carbon::parse($request->fecha_visita),
            'tipo_consulta' => $request->tipo_consulta,
        ]);

        return redirect()->route('recepcion.verPacientes', $request->paciente_id)
        ->with('success', 'Encargado asignado correctamente al paciente.');
    
    }

    // Busca encargados por DNI o nombre para mostrar en tabla
    public function buscarEncargados(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id_paciente',
            'query'       => 'nullable|string',
            'page'        => 'nullable|integer|min:1',
        ]);

        $paciente = Paciente::with('persona')->findOrFail($request->paciente_id);
        $query = (string) $request->input('query', '');

        $encargados = Encargado::whereHas('persona', function ($q) use ($query) {
                if ($query !== '') {
                    $q->where('dni', 'LIKE', "%{$query}%")
                    ->orWhere('nombre', 'LIKE', "%{$query}%")
                    ->orWhere('apellido', 'LIKE', "%{$query}%");
                }
            })
            ->with('persona')
            ->orderBy('id_encargado', 'desc')
            ->paginate(10);

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $encargados->map(function ($e) {
                    return [
                        'id_encargado' => $e->id_encargado,
                        'nombre'       => $e->persona->nombre,
                        'apellido'     => $e->persona->apellido,
                        'dni'          => $e->persona->dni,
                        'edad'         => $e->persona->edad,
                        'fecha_nacimiento' => $e->persona->fecha_nacimiento, // campo nuevo implementado
                        'telefono'     => $e->persona->telefono,
                    ];
                }),
                'meta' => [
                    'current_page' => $encargados->currentPage(),
                    'last_page'    => $encargados->lastPage(),
                    'per_page'     => $encargados->perPage(),
                    'total'        => $encargados->total(),
                ],
            ]);
        }

        // Fallback (si alguien entra sin JS): renderiza la vista completa.
        return view('logica.recepcion.agregar-encargado', compact('paciente', 'encargados'));
    }


    public function mostrarFormularioEncargado($id, Request $request)
    {
        $encargados = Encargado::with('persona')->get();
        $id_encargado = $request->query('encargado');
        $encargadoSeleccionado = null;

        if ($id_encargado) {
            $encargadoSeleccionado = Encargado::with('persona')->find($id_encargado);
        }

        return view('logica.recepcion.asignar-encargado', compact('encargados', 'encargadoSeleccionado', 'id'));
    }

    public function mostrarAsignarEncargado($id_paciente)
    {
        $encargados = Encargado::with('persona')->get(); // o los que apliquen

        return view('logica.recepcion.asignar-encargado', compact('encargados', 'id_paciente'));
    }

    public function formAgregarEncargado($idPaciente, Request $request)
    {
        $paciente = Paciente::with('persona')->findOrFail($idPaciente);
        $id_encargado = $request->query('encargado');
        $sinEncargado = $request->query('sin_encargado');

    $encargadoSeleccionado = null;

        if ($id_encargado) {
            $encargadoSeleccionado = Encargado::with('persona')->find($id_encargado);
        }

        return view('logica.recepcion.agregar-encargado', compact('paciente', 'encargadoSeleccionado', 'sinEncargado'));
    }

    public function crearEncargadoYRelacion(Request $request)
    {
        $data = $request->validate([
            'paciente_id'          => ['required','exists:pacientes,id_paciente'],

            'encargado_nombre'     => ['nullable','string','max:100','regex:/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/'],
            'encargado_apellido'   => ['nullable','string','max:100','regex:/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/'],
            'encargado_edad'       => ['nullable','integer','min:0','max:120'],
            // Nota: si tus DNIs no siempre son 13 dígitos, cambia esta regla a 'max:20' sin regex:
            // 'encargado_dni' => ['required','string','unique:personas,dni','max:13','regex:/^[0-9]{13}$/'],
            'encargado_fecha_nacimiento' => ['nullable','date','before:today'], // campo nuevo implementado
            'encargado_dni' => ['nullable','string','max:13','regex:/^[0-9]{13}$/'],
            'encargado_sexo'       => ['nullable','in:M,F'],
            'encargado_direccion'  => ['nullable','string','max:255'],
            'encargado_telefono'   => ['nullable','string','max:15','regex:/^[0-9+()\-\s]+$/'],

            'fecha_visita'         => ['required','date'],
            'tipo_consulta'        => ['required','in:general,especializada'],
        ]);

        DB::transaction(function () use ($data) {
            // Crear Persona del encargado
            $persona = Persona::create([
                'nombre'    => $data['encargado_nombre'],
                'apellido'  => $data['encargado_apellido'],
                'edad'      => $data['encargado_edad'] ?? null, // campo nuevo implementando
                'fecha_nacimiento' => $data['encargado_fecha_nacimiento'] ?? null, // campo nuevo implementando
                'dni'       => $data['encargado_dni'],
                'sexo'      => $data['encargado_sexo'],
                'direccion' => $data['encargado_direccion'],
                'telefono'  => $data['encargado_telefono'] ?? null,
            ]);

            // Crear Encargado
            $encargado = Encargado::create([
                'persona_id' => $persona->id_persona,
            ]);

            // Crear relación (visita)
            RelacionPacienteEncargado::create([
                'paciente_id'   => $data['paciente_id'],
                'encargado_id'  => $encargado->id_encargado,
                'tipo_consulta' => $data['tipo_consulta'],
                'fecha_visita'  => $data['fecha_visita'],
            ]);
        });

        return redirect()
            ->route('recepcion.verPacientes')
            ->with('success', 'Encargado creado y visita registrada correctamente.');
    }

    public function editarPacienteForm($id)
    {
        $paciente = Paciente::with('persona')->findOrFail($id);
        return view('logica.recepcion.editar-paciente', compact('paciente'));
    }

    public function actualizarPaciente(Request $request, $id)
    {
        $paciente = Paciente::with('persona')->findOrFail($id);

        $request->validate([
            'nombre'    => 'nullable|string|max:100|regex:/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/',
            'apellido'  => 'nullable|string|max:100|regex:/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/',
            'edad'      => 'nullable|integer|min:0|max:120',
            //'dni'       => 'required|string|max:13|regex:/^[0-9]{13}$/|unique:personas,dni,'
            //              .$paciente->persona->id_persona.',id_persona',
            'fecha_nacimiento' => 'nullable|date|before:today', // campo nuevo implementando
            'dni'       => 'nullable|string|max:13|regex:/^[0-9]{13}$/',
            'sexo'      => 'nullable|in:M,F',
            'direccion' => 'nullable|string|max:255',
            // Teléfono ahora es OPCIONAL también al editar
            'telefono'  => 'nullable|string|max:15|regex:/^[0-9+()\-\s]+$/',
        ]);

        $persona = $paciente->persona;
        $persona->nombre    = $request->nombre;
        $persona->apellido  = $request->apellido;
        $persona->edad      = $request->edad;
        $persona->fecha_nacimiento      = $request->fecha_nacimiento; // campo nuevo implementado
        $persona->dni       = $request->dni;
        $persona->sexo      = $request->sexo;
        $persona->direccion = $request->direccion;
        // guarda null si viene vacío
        $persona->telefono  = $request->filled('telefono') ? $request->telefono : null;
        $persona->save();

        return redirect()
            ->route('recepcion.verPacientes')
            ->with('success', 'Datos del paciente actualizados correctamente.');
    }

    public function vistaBuscar()
    {
        return view('logica.recepcion.buscar'); // solo renderiza la vista, todo lo demás es AJAX
    }

    /**
     * API AJAX: buscar pacientes (nombre, apellido, DNI) con paginación.
     */
    public function apiPacientes(Request $request)
    {
        $request->validate([
            'q'        => 'nullable|string',
            'page'     => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:5|max:100',
        ]);

        $q = trim((string) $request->input('q', ''));
        $perPage = (int) ($request->input('per_page', 25));

        $pacientes = Paciente::query()
            ->with('persona')
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function($query) use ($q) {
                    // Buscar por código (parcial o exacto)
                    $query->where('codigo_paciente', 'LIKE', "%{$q}%");
                    
                    // O buscar en datos de la persona
                    $query->orWhereHas('persona', function ($p) use ($q) {
                        $p->where('nombre', 'LIKE', "%{$q}%")
                          ->orWhere('apellido', 'LIKE', "%{$q}%")
                          ->orWhere('dni', 'LIKE', "%{$q}%");
                    });
                });
            })
            ->orderBy('id_paciente', 'desc')
            ->paginate($perPage);

        return response()->json([
            'data' => $pacientes->map(function ($p) {
                return [
                    'id_paciente' => $p->id_paciente,
                    'codigo_paciente' => $p->codigo_paciente,
                    'nombre'      => $p->persona->nombre,
                    'apellido'    => $p->persona->apellido,
                    'dni'         => $p->persona->dni,
                    'edad'        => $p->persona->edad,
                    'fecha_nacimiento' => $p->persona->fecha_nacimiento,
                    'telefono'    => $p->persona->telefono,
                ];
            }),
            'meta' => [
                'current_page' => $pacientes->currentPage(),
                'last_page'    => $pacientes->lastPage(),
                'per_page'     => $pacientes->perPage(),
                'total'        => $pacientes->total(),
            ],
        ]);
    }

    /**
     * API AJAX: buscar encargados (nombre, apellido, DNI) con paginación.
     */
    public function apiEncargados(Request $request)
    {
        $request->validate([
            'q'        => 'nullable|string',
            'page'     => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:5|max:100',
        ]);

        $q = trim((string) $request->input('q', ''));
        $perPage = (int) ($request->input('per_page', 25));

        $encargados = \App\Models\Encargado::query()
            ->with('persona')
            ->when($q !== '', function ($qq) use ($q) {
                $qq->whereHas('persona', function ($p) use ($q) {
                    $p->where('nombre', 'LIKE', "%{$q}%")
                    ->orWhere('apellido', 'LIKE', "%{$q}%")
                    ->orWhere('dni', 'LIKE', "%{$q}%");
                });
            })
            ->orderBy('id_encargado', 'desc')
            ->paginate($perPage);

        return response()->json([
            'data' => $encargados->map(function ($e) {
                return [
                    'id_encargado' => $e->id_encargado,
                    'nombre'       => $e->persona->nombre,
                    'apellido'     => $e->persona->apellido,
                    'dni'          => $e->persona->dni,
                    'telefono'     => $e->persona->telefono,
                    'direccion'    => $e->persona->direccion,
                    'edad'         => $e->persona->edad,
                    'fecha_nacimiento' => $e->persona->fecha_nacimiento, // campo nuevo implementado
                    'sexo'         => $e->persona->sexo,
                ];
            }),
            'meta' => [
                'current_page' => $encargados->currentPage(),
                'last_page'    => $encargados->lastPage(),
                'per_page'     => $encargados->perPage(),
                'total'        => $encargados->total(),
            ],
        ]);
    }

    /**
     * API AJAX: edición rápida de Encargado (teléfono/dirección).
     */
    public function apiEncargadoQuickUpdate(Request $request, $id)
    {
        $data = $request->validate([
            'telefono'  => ['nullable','string','max:15','regex:/^[0-9+()\-\s]+$/'],
            'direccion' => ['nullable','string','max:255'],
        ]);

        $encargado = \App\Models\Encargado::with('persona')->findOrFail($id);

        $persona = $encargado->persona;
        if (array_key_exists('telefono', $data)) {
            $persona->telefono = $data['telefono'];
        }
        if (array_key_exists('direccion', $data)) {
            $persona->direccion = $data['direccion'];
        }
        $persona->save();

        return response()->json([
            'id_encargado' => (int) $id,
            'ok' => true,
            'msg' => 'Datos actualizados',
        ]);
    }

    /**
     * API AJAX: edición completa de Encargado (todos los campos de Persona).
     */
    public function apiEncargadoUpdate(Request $request, $id)
    {
        $encargado = \App\Models\Encargado::with('persona')->findOrFail($id);
        $persona   = $encargado->persona;

        $data = $request->validate([
            'nombre'    => ['nullable','string','max:100','regex:/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/'],
            'apellido'  => ['nullable','string','max:100','regex:/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/'],
            // 'dni'       => ['nullable','string','max:13','regex:/^[0-9]{13}$/',Rule::unique('personas','dni')->ignore($persona->id_persona, 'id_persona'),],
            'dni'       => ['nullable','string','max:13','regex:/^[0-9]{13}$/'],
            'edad'      => ['nullable','integer','min:0','max:120'],
            'fecha_nacimiento' => ['nullable','date','before:today'], // campo nuevo implementado
            'sexo'      => ['nullable','in:M,F'],
            'direccion' => ['nullable','string','max:255'],
            'telefono'  => ['nullable','string','max:15','regex:/^[0-9+()\-\s]+$/'],
        ]);

        // Asignar solo los campos presentes
        foreach (['nombre','apellido','dni','edad', 'fecha_nacimiento','sexo','direccion','telefono'] as $f) {
            if ($request->has($f)) {
                $persona->{$f} = $data[$f];
            }
        }
        $persona->save();

        return response()->json([
            'id_encargado' => (int) $id,
            'nombre'       => $persona->nombre,
            'apellido'     => $persona->apellido,
            'dni'          => $persona->dni,
            'telefono'     => $persona->telefono,
            'direccion'    => $persona->direccion,
            'edad'         => $persona->edad,
            'fecha_nacimiento' => $persona->fecha_nacimiento, // campo nuevo implementado
            'sexo'         => $persona->sexo,
        ]);
    }

    public function apiEncargadoUpdateFull(Request $request, $id)
    {
        // Trae encargado + persona (para validar unique de DNI ignorando su propia persona)
        $encargado = \App\Models\Encargado::with('persona')->findOrFail($id);
        $persona   = $encargado->persona;

        $data = $request->validate([
            'nombre'    => ['nullable','string','max:100','regex:/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/'],
            'apellido'  => ['nullable','string','max:100','regex:/^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/'],
            // ignora unicidad contra su misma persona (columna PK: id_persona)
            //'dni'       => ['nullable','string','max:13','regex:/^[0-9]{13}$/','unique:personas,dni,'.$persona->id_persona.',id_persona'],
            'dni'       => ['nullable','string','max:13','regex:/^[0-9]{13}$/'],
            'edad'      => ['nullable','integer','min:0','max:120'],
            'fecha_nacimiento' => ['nullable','date','before:today'], // campo nuevo implementado
            'sexo'      => ['nullable','in:M,F'],
            'telefono'  => ['nullable','string','max:15','regex:/^[0-9+()\-\s]+$/'],
            'direccion' => ['nullable','string','max:255'],
        ]);

        // Actualiza persona
        $persona->nombre    = $data['nombre'];
        $persona->apellido  = $data['apellido'];
        $persona->dni       = $data['dni'];
        $persona->edad      = $data['edad']      ?? $persona->edad;
        $persona->fecha_nacimiento = $data['fecha_nacimiento'] ?? $persona->fecha_nacimiento;
        $persona->sexo      = $data['sexo']      ?? $persona->sexo;
        $persona->telefono  = $data['telefono']  ?? $persona->telefono;
        $persona->direccion = $data['direccion'] ?? $persona->direccion;
        $persona->save();

        // Respuesta con el mismo shape que usa la tabla
        return response()->json([
            'id_encargado' => $encargado->id_encargado,
            'nombre'       => $persona->nombre,
            'apellido'     => $persona->apellido,
            'dni'          => $persona->dni,
            'telefono'     => $persona->telefono,
            'direccion'    => $persona->direccion,
            'edad'         => $persona->edad,
            'fecha_nacimiento' => $persona->fecha_nacimiento,
            'sexo'         => $persona->sexo,
        ]);
    }
}

