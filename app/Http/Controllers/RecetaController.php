<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use App\Models\Expediente;
use App\Models\Paciente;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Carbon;

class RecetaController extends Controller
{

    
    /**
     * Vista para crear receta (desde expediente existente)
     */
    public function crear($expediente_id)
    {
        $expediente = Expediente::with([
            'paciente.persona',
            'doctor'
        ])->findOrFail($expediente_id);
        
        // Verificar si ya tiene receta
        if ($expediente->receta) {
            return redirect()
                ->route('medico.recetas.editar', $expediente->receta->id_receta)
                ->with('info', 'Este expediente ya tiene una receta. Puede editarla.');
        }
        
        // Obtener último peso registrado si existe
        $ultimoPeso = null;
        $signos = $expediente->signosVitales ?? null;
        if ($signos && $signos->peso) {
            $ultimoPeso = $signos->peso;
        }

        // Verificar que la edad esté disponible
        if (!$expediente->paciente || !$expediente->paciente->persona || !$expediente->paciente->persona->edad) {
            // Si no hay edad, podemos calcularla desde la fecha de nacimiento si existe
            // o solicitar que se complete el perfil del paciente
            if ($expediente->paciente && $expediente->paciente->persona && $expediente->paciente->persona->fecha_nacimiento) {
                 
            } else {
                // Si no hay edad ni fecha de nacimiento, redirigir con error
                return redirect()
                    ->route('medico.consulta.form')
                    ->with('error', 'El paciente no tiene edad registrada. Por favor, complete el perfil del paciente primero.');
            }
        } else {
            $edad = $expediente->paciente->persona->edad;
        }
        
        // Pasar la edad a la vista si es necesario
        return view('logica.medico.crear-receta', compact('expediente', 'ultimoPeso', 'edad'));
    }
    
    /**
     * Almacenar nueva receta
     */
    public function store(Request $request, $expediente_id)
    {
        $validator = Validator::make($request->all(), [
            'diagnostico' => 'nullable|string|min:10|max:2000',
            'receta' => 'nullable|string|min:20|max:5000',
            'observaciones' => 'nullable|string|max:1000',
            'peso_paciente_en_receta' => 'nullable|numeric|min:0|max:300',
            'alergias_conocidas' => 'nullable|string|max:500',
            'edad_paciente_en_receta' => 'nullable|integer|min:0|max:150'
        ]);
        
        if ($validator->fails()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'ok' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Error de validación'
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }
        
        $expediente = Expediente::with(['paciente.persona'])->findOrFail($expediente_id);
        $doctor = Empleado::where('user_id', Auth::id())->first();
        
        if (!$doctor) {
            $message = 'No se encontró información del médico';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['ok' => false, 'message' => $message], 422);
            }
            return back()->withErrors(['error' => $message]);
        }
        
        // OBTENER LA EDAD - FORMA SEGURA
    $edadPaciente = null;
    
    // Opción 1: Del formulario (si se agregó el campo)
    if ($request->filled('edad_paciente_en_receta')) {
        $edadPaciente = (int)$request->edad_paciente_en_receta;
    }
    // Opción 2: De la tabla personas
    else if ($expediente->paciente && $expediente->paciente->persona) {
        $edadPaciente = (int)$expediente->paciente->persona->edad;
    }
    
    // Si aún no tenemos edad, usar un valor por defecto
    if ($edadPaciente === null) {
        $edadPaciente = 0; // Valor por defecto
    }
        
        DB::beginTransaction();
        try {
            $receta = Receta::create([
                'expediente_id' => $expediente->id_expediente,
                'paciente_id' => $expediente->paciente_id,
                'doctor_id' => $doctor->id_empleado,
                'fecha_prescripcion' => now(),
                'diagnostico' => $request->diagnostico,
                'receta' => $request->receta,
                'observaciones' => $request->observaciones,
                'edad_paciente_en_receta' => $edadPaciente, // ← ESTO YA NO SERÁ NULL
                'peso_paciente_en_receta' => $request->peso_paciente_en_receta,
                'alergias_conocidas' => $request->alergias_conocidas,
                'estado' => 'activa',
                'creado_por' => Auth::id()
            ]);
            
            DB::commit();
            
            $pacienteNombre = $expediente->paciente->persona->nombre . ' ' . $expediente->paciente->persona->apellido;
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'ok' => true,
                    'receta_id' => $receta->id_receta,
                    'paciente' => $pacienteNombre,
                    'message' => 'Receta creada exitosamente para ' . $pacienteNombre . '.'
                ]);
            }
            
            return redirect()
                ->route('medico.recetas.ver', $receta->id_receta)
                ->with('success', 'Receta creada exitosamente para ' . $pacienteNombre . '.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            $message = 'Error al crear la receta: ' . $e->getMessage();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['ok' => false, 'message' => $message], 422);
            }
            
            return back()->withErrors(['error' => $message])->withInput();
        }
    }
    
    /**
     * Vista para editar receta
     */
     public function editar($id)
    {
        $receta = Receta::with([
            'expediente.paciente.persona',
            'doctor',
            'creador'
        ])->findOrFail($id);
        
        // ¡SIN verificación! El middleware 'role:admin,medico' ya lo hace
        return view('logica.medico.editar-receta', compact('receta'));
    }

    
    /**
     * Actualizar receta existente
     */
    public function update(Request $request, $id)
    {
        $receta = Receta::findOrFail($id);
        
        // Verificar permisos
        if (Auth::user()->role !== 'admin' && 
            $receta->doctor_id !== (Auth::user()->empleado->id_empleado ?? null)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'ok' => false,
                    'message' => 'No tiene permisos para editar esta receta'
                ], 403);
            }
            abort(403, 'No tiene permisos para editar esta receta');
        }
        
        $validator = Validator::make($request->all(), [
            'diagnostico' => 'nullable|string|min:10|max:2000',
            'receta' => 'nullable|string|min:20|max:5000',
            'observaciones' => 'nullable|string|max:1000',
            'peso_paciente_en_receta' => 'nullable|numeric|min:0|max:300',
            'alergias_conocidas' => 'nullable|string|max:500',
            'estado' => 'nullable|in:activa,completada,suspendida,cancelada'
        ]);
        
        if ($validator->fails()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'ok' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Error de validación'
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }
        
        try {
            $receta->update([
                'diagnostico' => $request->diagnostico,
                'receta' => $request->receta,
                'observaciones' => $request->observaciones,
                'peso_paciente_en_receta' => $request->peso_paciente_en_receta,
                'alergias_conocidas' => $request->alergias_conocidas,
                'estado' => $request->estado
            ]);
            
            $pacienteNombre = $receta->expediente->paciente->persona->nombre_completo ?? 'Paciente';
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'ok' => true,
                    'receta_id' => $receta->id_receta,
                    'paciente' => $pacienteNombre,
                    'message' => 'Receta actualizada exitosamente.'
                ]);
            }
            
            // Redirección según el rol del usuario
            if (Auth::user()->role === 'admin') {
                return redirect()
                    ->route('admin.recetas.ver', $receta->id_receta)
                    ->with('success', 'Receta actualizada exitosamente.');
            } else {
                return redirect()
                    ->route('medico.recetas.ver', $receta->id_receta)
                    ->with('success', 'Receta actualizada exitosamente.');
            }
                
        } catch (\Exception $e) {
            $message = 'Error al actualizar la receta: ' . $e->getMessage();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['ok' => false, 'message' => $message], 422);
            }
            return back()->withErrors(['error' => $message])->withInput();
        }
    }
    
    /* Mostrar receta */
    public function show($id)
    {
        $receta = Receta::with([
            'expediente.paciente.persona',
            'doctor',
            'creador'
        ])->findOrFail($id);
        
        // ¡SIN verificación! El middleware ya lo hace
        return view('logica.medico.ver-receta', compact('receta'));
    }
    
    /**
     * Eliminar receta
     */
    public function destroy(Request $request, $id)
    {
        $receta = Receta::findOrFail($id);
        
        // Verificar permisos
        if (Auth::user()->role !== 'admin' && 
            $receta->doctor_id !== (Auth::user()->empleado->id_empleado ?? null)) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'ok' => false,
                    'message' => 'No tiene permisos para eliminar esta receta'
                ], 403);
            }
            abort(403, 'No tiene permisos para eliminar esta receta');
        }
        
        try {
            $pacienteNombre = $receta->expediente->paciente->persona->nombre_completo ?? 'Paciente';
            $receta->delete();
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'ok' => true,
                    'message' => 'Receta eliminada exitosamente.'
                ]);
            }
            
             // Redirección CORREGIDA según el rol
            if (Auth::user()->role === 'admin') {
                return redirect()
                    ->route('admin.expedientes.index') // Ruta que SÍ existe
                    ->with('success', 'Receta eliminada exitosamente.');
            } else {
                return redirect()
                    ->route('expedientes.index') // Ruta compartida que SÍ existe
                    ->with('success', 'Receta eliminada exitosamente.');
            }
                
        } catch (\Exception $e) {
            $message = 'Error al eliminar la receta: ' . $e->getMessage();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['ok' => false, 'message' => $message], 422);
            }
            return back()->withErrors(['error' => $message]);
        }
    }
    
    /*
      Vista para imprimir receta   */
     
    public function imprimir($id)
    {
        $receta = Receta::with([
            'expediente.paciente.persona',
            'doctor',
            'creador'
        ])->findOrFail($id);
        
        // ¡SIN verificación! El middleware ya lo hace
        return view('logica.medico.imprimir-receta', compact('receta'));
    }
    

    
    /**
     * Buscar recetas por paciente (API JSON)
     */
    public function buscarPorPaciente(Request $request)
    {
        $request->validate(['paciente_id' => 'required|exists:pacientes,id_paciente']);
        
        $recetas = Receta::with(['doctor', 'expediente'])
            ->where('paciente_id', $request->paciente_id)
            ->orderByDesc('fecha_prescripcion')
            ->get();
            
        return response()->json([
            'ok' => true,
            'recetas' => $recetas
        ]);
    }
    
    /**
     * Obtener receta por expediente (API JSON)
     */
    public function porExpediente($expediente_id)
    {
        $receta = Receta::with(['doctor', 'expediente.paciente.persona'])
            ->where('expediente_id', $expediente_id)
            ->first();
            
        return response()->json([
            'ok' => true,
            'receta' => $receta
        ]);
    }
}
