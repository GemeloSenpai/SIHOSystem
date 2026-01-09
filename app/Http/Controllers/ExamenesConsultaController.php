<?php

namespace App\Http\Controllers;

use App\Models\Expediente;
use App\Models\ExamenMedico;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ExamenesConsultaController extends Controller
{
    public function store(Request $request, $expedienteId)
    {
        $expediente = \App\Models\Expediente::with('consulta')->findOrFail($expedienteId);

        $request->validate([
            'examen_id' => 'required|integer',
        ]);

        if (!$expediente->consulta) {
            return $request->ajax() 
                ? response()->json(['success' => false, 'message' => 'Este expediente no tiene una consulta asociada.'], 422)
                : back()->with('error', 'Este expediente no tiene una consulta asociada.');
        }

        // Evitar duplicados
        $yaExiste = $expediente->consulta->examenesMedicos()
            ->where('examen_id', $request->examen_id)
            ->exists();
        if ($yaExiste) {
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => 'Este examen ya está asignado a la consulta.'], 422)
                : back()->with('error', 'Este examen ya está asignado a la consulta.');
        }

        $consulta = $expediente->consulta;
        $doctorId = $consulta->doctor_id;

        $consulta->examenesMedicos()->create([
            'examen_id'        => (int) $request->examen_id,
            'fecha_asignacion' => now(),
            'paciente_id'      => $expediente->paciente_id,
            'doctor_id'        => $doctorId,
        ]);

        return $request->ajax()
            ? response()->json(['success' => true, 'message' => 'Examen agregado correctamente.'])
            : back()->with('success', 'Examen agregado correctamente.');
    }

    public function destroy(Request $request, $expedienteId, $examenMedicoId)
    {
        $expediente = Expediente::with('consulta.examenesMedicos')->findOrFail($expedienteId);
        if (!$expediente->consulta) {
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => 'Este expediente no tiene una consulta asociada.'], 422)
                : back()->with('error', 'Este expediente no tiene una consulta asociada.');
        }

        $expediente->consulta->examenesMedicos()
            ->where('id_examen_medico', $examenMedicoId)
            ->delete();

        return $request->ajax()
            ? response()->json(['success' => true, 'message' => 'Examen eliminado del expediente.'])
            : back()->with('success', 'Examen eliminado del expediente.');
    }

    public function lista($expedienteId)
    {
        $expediente = Expediente::with([
            'consulta.examenesMedicos.examen.categoria', 
            'consulta.examenesMedicos' => function($query) {
                $query->orderBy('fecha_asignacion', 'desc');
            }
        ])->findOrFail($expedienteId);
        
        $examenes = $expediente->consulta?->examenesMedicos ?? collect();
        
        // Pasar BOTH variables a la vista
        return view('partials.lista-examenes', compact('examenes', 'expediente'))->render();
    }

    public function verExamenes($idExpediente)
    {
        // Para depuración - quitar después
        // dd("ID recibido: " . $idExpediente);
        
        // Obtener el expediente con todas las relaciones necesarias
        $expediente = Expediente::with([
            'paciente.persona',
            'doctor',
            'consulta.examenesMedicos.examen.categoria'  // IMPORTANTE: obtener de consulta
        ])->findOrFail($idExpediente);

        // Obtener paciente
        $paciente = $expediente->paciente;
        $persona = $paciente->persona;
        
        // Edad
        $edad = $persona->edad ?? 'N/A';
        $fecha_nacimiento = $persona->fecha_nacimiento; // nuevo implementado
        // Teléfono
        $telefono = $persona->telefono ?? 'No registrado';
        
        // Médico
        $medicoNombre = 'No asignado';
        if ($expediente->doctor) {
            $medicoNombre = 'Dr. ' . ($expediente->doctor->nombre ?? '') . ' ' . ($expediente->doctor->apellido ?? '');
        }

        // Organizar exámenes por categoría (obtener de consulta, no directamente)
        $porCategorias = [];
        
        if ($expediente->consulta && $expediente->consulta->examenesMedicos) {
            foreach ($expediente->consulta->examenesMedicos as $examenMedico) {
                if ($examenMedico->examen && $examenMedico->examen->categoria) {
                    $categoria = $examenMedico->examen->categoria->nombre_categoria;
                    $nombreExamen = $examenMedico->examen->nombre_examen;
                    
                    if (!isset($porCategorias[$categoria])) {
                        $porCategorias[$categoria] = [];
                    }
                    
                    // Evitar duplicados
                    if (!in_array($nombreExamen, $porCategorias[$categoria])) {
                        $porCategorias[$categoria][] = $nombreExamen;
                    }
                }
            }
        }

        // Fecha de solicitud
        $fechaPrimera = $expediente->fecha_creacion ? 
            \Carbon\Carbon::parse($expediente->fecha_creacion)->format('d/m/Y') : 
            now()->format('d/m/Y');

        return view('logica.admin.vista-ver-examenes', [
            'expediente' => $expediente,
            'paciente' => $paciente,
            'edad' => $edad,
            'fecha_nacimiento' => $fecha_nacimiento, // nuevo implementado
            'telefono' => $telefono,
            'medicoNombre' => $medicoNombre,
            'porCategorias' => $porCategorias,
            'fechaPrimera' => $fechaPrimera,
        ]);
    }

    /**
     * Organizar exámenes por categoría
     */
    private function organizarExamenesPorCategoria($expediente)
    {
        $porCategorias = [];
        
        // Si hay exámenes médicos relacionados
        if ($expediente->examenesMedicos && $expediente->examenesMedicos->count() > 0) {
            foreach ($expediente->examenesMedicos as $examenMedico) {
                if ($examenMedico->examen && $examenMedico->examen->categoria) {
                    $categoria = $examenMedico->examen->categoria->nombre_categoria;
                    $nombreExamen = $examenMedico->examen->nombre_examen;
                    
                    if (!isset($porCategorias[$categoria])) {
                        $porCategorias[$categoria] = [];
                    }
                    
                    $porCategorias[$categoria][] = $nombreExamen;
                }
            }
        }
        
        return $porCategorias;
    }


}
