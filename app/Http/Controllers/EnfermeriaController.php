<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Models\SignosVitales;
use App\Models\Empleado;
use Illuminate\Support\Facades\DB;
use App\Models\RelacionPacienteEncargado;

class EnfermeriaController extends Controller
{
    public function formRegistrarSignos(Request $request)
    {
        $enfermeraEmpleadoId = Empleado::where('user_id', auth()->id())->value('id_empleado');

        if ($request->has('id')) {
            $paciente = Paciente::with(['persona', 'relacionConEncargado.encargado.persona'])
                ->where('id_paciente', $request->id)
                ->firstOrFail();

            return view('logica.enfermero.registrar-signosvitales', compact('paciente', 'enfermeraEmpleadoId'));
        }

        $recientes = RelacionPacienteEncargado::with(['paciente.persona'])
            ->orderByDesc('fecha_visita')
            ->take(10)
            ->get();

        return view('logica.enfermero.registrar-signosvitales', compact('recientes', 'enfermeraEmpleadoId'));
    }

            
    public function buscarPacienteForm(Request $request)
    {
        $request->validate([
            'buscar' => 'required|string',
        ]);

        $enfermeraEmpleadoId = Empleado::where('user_id', auth()->id())->value('id_empleado');

        $busqueda = $request->input('buscar');

        $pacientes = Paciente::with([
            'persona',
            'relacionConEncargado' => function ($q) {
                $q->select('id_relacion', 'paciente_id', 'tipo_consulta', 'fecha_visita');
            }
        ])
        ->whereHas('persona', function ($q) use ($busqueda) {
            $q->where('nombre', 'like', "%$busqueda%")
            ->orWhere('dni', 'like', "%$busqueda%");
        })
        ->get();

        return view('logica.enfermero.registrar-signosvitales', compact('pacientes', 'enfermeraEmpleadoId'));
    }


    public function guardarSignos(Request $request)
    {
        $validated = $request->validate([
            'paciente_id'       => 'required|exists:pacientes,id_paciente',
            'enfermera_id'      => 'required|exists:empleados,id_empleado', // <- aquí el cambio
            'presion_arterial'  => ['nullable', 'regex:/^\d{2,3}\/\d{2,3}$/'],
            'fc'                => 'nullable|integer|min:10|max:300',
            'fr'                => 'nullable|integer|min:10|max:100',
            'temperatura'       => 'nullable|numeric|min:20|max:70',
            'so2'               => 'nullable|integer|min:10|max:200',
            'peso'              => 'nullable|numeric|min:1|max:1500',
            'glucosa'           => 'nullable|numeric|min:10|max:800',
        ]);

        // Sello de tiempo
        $validated['fecha_registro'] = now();

        // (Opcional pero recomendado) Escudo anti doble-submit: si llega dos veces en ~5s con los mismos datos, lo ignoramos
        $yaExiste = SignosVitales::where('paciente_id', $validated['paciente_id'])
            ->where('enfermera_id', $validated['enfermera_id'])
            ->where('presion_arterial', $validated['presion_arterial'])
            ->where('fc', $validated['fc'])
            ->where('fr', $validated['fr'])
            ->where('temperatura', $validated['temperatura'])
            ->where('so2', $validated['so2'])
            ->where('peso', $validated['peso'])
            ->where('glucosa', $validated['glucosa'])
            ->where('fecha_registro', '>=', now()->subSeconds(5))
            ->exists();

        if ($yaExiste) {
            return redirect()->route('enfermero.signosvitales.form')
                ->with('success', 'Los signos ya se habían registrado (se evitó un duplicado por doble envío).');
        }

        // Crear solo UNA vez
        SignosVitales::create($validated);

        $paciente = Paciente::with('persona')->find($validated['paciente_id']);
        $nombre = $paciente->persona->nombre . ' ' . $paciente->persona->apellido;

        return redirect()->route('enfermero.signosvitales.form')
            ->with('success', 'Se registraron los signos vitales de ' . $nombre . ' correctamente.');
    }

    public function historial($pacienteId)
    {
        $paciente = Paciente::with('persona')->findOrFail($pacienteId);

        $registros = SignosVitales::where('paciente_id', $pacienteId)
            ->orderByDesc('fecha_registro')
            ->orderByDesc('id_signos_vitales')
            ->paginate(10);

        return view('logica.enfermero.signosvitales-historial', compact('paciente', 'registros'));
    }

    public function actualizar(Request $request, $id)
    {
        $signo = SignosVitales::findOrFail($id);

        $validated = $request->validate([
            'presion_arterial'  => ['nullable', 'regex:/^\d{2,3}\/\d{2,3}$/'],
            'fc'                => 'nullable|integer|min:10|max:300',
            'fr'                => 'nullable|integer|min:10|max:100',
            'temperatura'       => 'nullable|numeric|min:20|max:70',
            'so2'               => 'nullable|integer|min:10|max:200',
            'peso'              => 'nullable|numeric|min:1|max:1500',
            'glucosa'           => 'nullable|numeric|min:10|max:800',
        ]);

        $signo->update($validated);

        // Asegura volver al historial de ESTE paciente con toast de éxito
        return redirect()
            ->route('enfermeria.signos.historial', $signo->paciente_id) // <- ajusta el name si tu ruta difiere
            ->with('success', 'Signos vitales actualizados correctamente.');
    }


}
