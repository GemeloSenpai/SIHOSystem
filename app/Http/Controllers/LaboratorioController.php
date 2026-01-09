<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;

class LaboratorioController extends Controller
{
    /**
     * Mostrar la vista de gestión de expedientes para laboratorio
     */
    public function gestionarExpedientes(Request $request)
    {
        $buscar = $request->query('buscar');
        
        $query = Paciente::with(['persona', 'expedientes' => function ($query) {
            $query->orderBy('fecha_creacion', 'desc');
        }]);
        
        if ($buscar) {
            $query->whereHas('persona', function ($q) use ($buscar) {
                $q->where('nombre', 'LIKE', "%{$buscar}%")
                  ->orWhere('apellido', 'LIKE', "%{$buscar}%")
                  ->orWhere('dni', 'LIKE', "%{$buscar}%");
            })->orWhere('codigo_paciente', 'LIKE', "%{$buscar}%");
        }
        
        $pacientes = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('logica.admin.gestionar-expedientes', compact('pacientes', 'buscar'));
    }
    
    /**
     * Mostrar exámenes de un expediente específico
     */
    public function verExamenesExpediente($idExpediente)
    {
        // Aquí iría la lógica para mostrar los exámenes de un expediente
        // Similar a la ruta 'admin.vista-ver-examenes'
        
        return app(\App\Http\Controllers\ExportarExamen::class)->vistaExamenes($idExpediente);
    }
}