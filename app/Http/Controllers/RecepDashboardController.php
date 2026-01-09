<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Paciente;
use App\Models\Expediente;
use App\Models\ConsultaDoctor;
use App\Models\RelacionPacienteEncargado;
use App\Models\Empleado;

class RecepDashboardController extends Controller
{
    public function index(Request $request)
    {
        $hoy = Carbon::today();
        
        // ==================== 1. FLUJO DE PACIENTES ====================
        
        // Pacientes nuevos hoy
        $pacientesNuevosHoy = Paciente::whereDate('created_at', $hoy)->count();
        
        // Pacientes atendidos hoy (con consulta)
        $pacientesAtendidosHoy = ConsultaDoctor::whereDate('created_at', $hoy)
            ->distinct('paciente_id')
            ->count('paciente_id');
        
        // Pacientes en espera (expedientes abiertos hoy sin consulta) - CORREGIDO
        $pacientesEnEspera = Expediente::where('estado', 'abierto')
            ->whereDate('fecha_creacion', $hoy)
            ->whereNull('consulta_id')
            ->count();
        
        // ==================== 2. AGENDA Y CITAS ====================
        
        // Citas programadas para hoy
        $citasHoy = ConsultaDoctor::whereDate('citado', $hoy)->count();
        
        // Citas cumplidas hoy
        $citasCumplidasHoy = ConsultaDoctor::whereDate('citado', $hoy)
            ->whereDate('created_at', $hoy)
            ->count();
        
        // Próximas citas (próximos 3 días)
        $proximos3Dias = Carbon::today()->addDays(3);
        $proximasCitas = ConsultaDoctor::whereDate('citado', '>', $hoy)
            ->whereDate('citado', '<=', $proximos3Dias)
            ->orderBy('citado')
            ->with('paciente.persona')
            ->limit(10)
            ->get();
        
        // ==================== 3. URGENCIAS ====================
        
        // Urgencias hoy
        $urgenciasHoy = ConsultaDoctor::where('urgencia', 'si')
            ->whereDate('created_at', $hoy)
            ->count();
        
        // Tipos de urgencia hoy
        $tiposUrgenciaHoy = ConsultaDoctor::where('urgencia', 'si')
            ->whereDate('created_at', $hoy)
            ->select('tipo_urgencia', DB::raw('COUNT(*) as total'))
            ->groupBy('tipo_urgencia')
            ->get();
        
        // ==================== 4. ESTADO DE ESPERA ====================
        
        // Tiempo promedio de espera (estimado - expedientes abiertos hace más de 30 min) - CORREGIDO
        $limiteEspera = Carbon::now()->subMinutes(30);
        $pacientesEsperaLarga = Expediente::where('estado', 'abierto')
            ->where('fecha_creacion', '<', $limiteEspera) // CORREGIDO: usar fecha_creacion
            ->whereNull('consulta_id')
            ->count();
        
        // Distribución por tipo de consulta hoy
        $tiposConsultaHoy = RelacionPacienteEncargado::whereDate('fecha_visita', $hoy)
            ->select('tipo_consulta', DB::raw('COUNT(*) as total'))
            ->groupBy('tipo_consulta')
            ->get();
        
        // ==================== 5. INFORMACIÓN ADICIONAL ====================
        
        // Médicos disponibles (activos hoy)
        $medicosActivosHoy = Empleado::whereHas('user', function($query) {
                $query->where('role', 'medico')
                      ->where('estado', 'activo');
            })
            ->count();
        
        // Pacientes por sexo hoy
        $pacientesPorSexoHoy = DB::table('personas')
            ->join('pacientes', 'personas.id_persona', '=', 'pacientes.persona_id')
            ->whereDate('pacientes.created_at', $hoy)
            ->select('personas.sexo', DB::raw('COUNT(*) as total'))
            ->groupBy('personas.sexo')
            ->get()
            ->pluck('total', 'sexo');

        return view('dashboards.RecepcionDashboard', compact(
            // Flujo de pacientes
            'pacientesNuevosHoy',
            'pacientesAtendidosHoy',
            'pacientesEnEspera',
            
            // Agenda y citas
            'citasHoy',
            'citasCumplidasHoy',
            'proximasCitas',
            
            // Urgencias
            'urgenciasHoy',
            'tiposUrgenciaHoy',
            
            // Estado de espera
            'pacientesEsperaLarga',
            'tiposConsultaHoy',
            
            // Información adicional
            'medicosActivosHoy',
            'pacientesPorSexoHoy'
        ));
    }
}