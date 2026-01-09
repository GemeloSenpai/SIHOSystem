<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\Persona;
use App\Models\Encargado;
use App\Models\RelacionPacienteEncargado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PacienteController extends Controller
{
    public function verPacientes(Request $request)
{
    $busqueda = $request->input('busqueda', '');

    $pacientes = Paciente::with([
            'persona',
            'relacionPacienteEncargado' => function($query) {
                $query->latest('fecha_visita')
                      ->with(['encargado.persona'])
                      ->select('*', DB::raw('DATE(fecha_visita) as fecha_visita_formateada'));
            }
        ])
        ->when($busqueda, function($query) use ($busqueda) {
            $query->where(function($q) use ($busqueda) {
                // Buscar por código de paciente
                $q->where('codigo_paciente', 'like', "%$busqueda%")
                  // O buscar por datos de la persona
                  ->orWhereHas('persona', function($personaQuery) use ($busqueda) {
                      $personaQuery->where('nombre', 'like', "%$busqueda%")
                                   ->orWhere('apellido', 'like', "%$busqueda%")
                                   ->orWhere('dni', 'like', "%$busqueda%");
                  });
            });
        })
        ->orderBy('id_paciente', 'desc')
        ->paginate(15);

    return view('logica.recepcion.ver-pacientes', compact('pacientes', 'busqueda'));
}

    public function detallesPaciente($id)
    {
        $paciente = Paciente::with([
            'persona',
            'relacionPacienteEncargado' => function($query) {
                $query->orderBy('fecha_visita', 'desc')
                      ->with(['encargado.persona']);
            }
        ])->findOrFail($id);

        return view('logica.recepcion.detalles-paciente', compact('paciente'));

    }

    public function editarPaciente($id)
    {
        $paciente = Paciente::with([
            'persona',
            'relacionPacienteEncargado.encargado.persona'
        ])->findOrFail($id);

        return response()->json($paciente);
    }

    public function actualizarPaciente(Request $request, $id)
    {
        DB::transaction(function() use ($request, $id) {
            $paciente = Paciente::findOrFail($id);
            
            // Actualizar datos de persona
            $paciente->persona->update([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'edad' => $request->edad,
                'fecha_nacimiento'=> $request->fecha_nacimiento, // campo nuevo implementado
                'dni' => $request->dni,
                'sexo' => $request->sexo,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono
            ]);

            // Actualizar datos de encargado si existe posible cambio
            if ($request->has('encargado_id')) {
                $relacion = RelacionPacienteEncargado::where('paciente_id', $id)->first();
                if ($relacion) {
                    $relacion->encargado->persona->update([
                        'nombre' => $request->encargado_nombre,
                        'apellido' => $request->encargado_apellido,
                        'dni' => $request->encargado_dni,
                        'telefono' => $request->encargado_telefono
                    ]);
                }
            }
        });

        return response()->json(['success' => 'Paciente actualizado correctamente']);
    }

    public function eliminarPaciente($id)
    {
        DB::transaction(function() use ($id) {
            $paciente = Paciente::findOrFail($id);
            $persona = $paciente->persona;
            
            RelacionPacienteEncargado::where('paciente_id', $id)->delete();
            $paciente->delete();
            $persona->delete();
        });

        return response()->json(['success' => 'Paciente eliminado correctamente']);
    }
}