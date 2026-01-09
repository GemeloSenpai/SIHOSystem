<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MedicoDashboardController extends Controller
{
    public function index(Request $request)
    {
        $medicoId = Auth::user()->empleado->id_empleado ?? null;
        
        if (!$medicoId) {
            abort(403, 'No tiene permisos para ver este dashboard');
        }
        
        $hoy = Carbon::today();
        $hace30Dias = Carbon::today()->subDays(30);
        $inicioSemana = Carbon::now()->startOfWeek();
        
        // ==================== 1. ACTIVIDAD PERSONAL ====================
        $consultasHoy = DB::table('consulta_doctor')
            ->where('doctor_id', $medicoId)
            ->whereDate('created_at', $hoy)
            ->count();
        
        $pacientesSemana = DB::table('consulta_doctor')
            ->where('doctor_id', $medicoId)
            ->where('created_at', '>=', $inicioSemana)
            ->distinct('paciente_id')
            ->count('paciente_id');
        
        // ==================== 2. SEGUIMIENTO ====================
        $pacientesSeguimiento = DB::table('consulta_doctor')
            ->where('doctor_id', $medicoId)
            ->where('resultado', 'seguimiento')
            ->whereNotNull('citado')
            ->where('citado', '>=', $hoy)
            ->distinct('paciente_id')
            ->count('paciente_id');
        
        $pacientesReferidos = DB::table('consulta_doctor')
            ->where('doctor_id', $medicoId)
            ->where('resultado', 'referido')
            ->whereDate('created_at', '>=', $hace30Dias)
            ->count();
        
        // ==================== 3. RECETAS Y EXÁMENES ====================
        $recetasHoy = DB::table('recetas')
            ->where('doctor_id', $medicoId)
            ->whereDate('fecha_prescripcion', $hoy)
            ->count();
        
        $examenesHoy = DB::table('examenes_medicos')
            ->where('doctor_id', $medicoId)
            ->whereDate('fecha_asignacion', $hoy)
            ->count();
        
        // ==================== 4. URGENCIAS ====================
        $urgenciasHoy = DB::table('consulta_doctor')
            ->where('doctor_id', $medicoId)
            ->where('urgencia', 'si')
            ->whereDate('created_at', $hoy)
            ->count();
        
        // ==================== 5. AGENDA DEL DÍA ====================
        $citasHoy = DB::table('consulta_doctor as cd')
            ->join('pacientes as p', 'cd.paciente_id', '=', 'p.id_paciente')
            ->join('personas as per', 'p.persona_id', '=', 'per.id_persona')
            ->where('cd.doctor_id', $medicoId)
            ->whereDate('cd.citado', $hoy)
            ->whereNotNull('cd.citado')
            ->select(
                'cd.id_consulta_doctor',
                'cd.citado',
                'cd.urgencia',
                'cd.resumen_clinico',
                'cd.impresion_diagnostica',
                'cd.resultado',
                'per.nombre',
                'per.apellido',
                'per.dni',
                'per.edad',
                'per.sexo'
            )
            ->orderBy('cd.citado')
            ->get();
        
        $citasProximas = DB::table('consulta_doctor as cd')
            ->join('pacientes as p', 'cd.paciente_id', '=', 'p.id_paciente')
            ->join('personas as per', 'p.persona_id', '=', 'per.id_persona')
            ->where('cd.doctor_id', $medicoId)
            ->where('cd.citado', '>', $hoy)
            ->where('cd.citado', '<=', Carbon::today()->addDays(7))
            ->whereNotNull('cd.citado')
            ->select(
                'cd.id_consulta_doctor',
                'cd.citado',
                'cd.urgencia',
                'cd.resultado',
                'per.nombre',
                'per.apellido'
            )
            ->orderBy('cd.citado')
            ->limit(5)
            ->get();
        
        $consultasRecientes = DB::table('consulta_doctor as cd')
            ->join('pacientes as p', 'cd.paciente_id', '=', 'p.id_paciente')
            ->join('personas as per', 'p.persona_id', '=', 'per.id_persona')
            ->where('cd.doctor_id', $medicoId)
            ->whereNotNull('cd.created_at')
            ->select(
                'cd.id_consulta_doctor', 
                'cd.created_at', 
                'cd.citado',
                'per.nombre', 
                'per.apellido', 
                'cd.impresion_diagnostica',
                'cd.urgencia',
                'cd.resultado'
            )
            ->orderBy('cd.created_at', 'desc')
            ->limit(5)
            ->get();
        
        // ==================== 6. ESTADÍSTICAS DE SEGUIMIENTO ====================
        $seguimientoProximo = DB::table('consulta_doctor as cd')
            ->join('pacientes as p', 'cd.paciente_id', '=', 'p.id_paciente')
            ->join('personas as per', 'p.persona_id', '=', 'per.id_persona')
            ->where('cd.doctor_id', $medicoId)
            ->where('cd.resultado', 'seguimiento')
            ->whereNotNull('cd.citado')
            ->where('cd.citado', '>=', $hoy)
            ->where('cd.citado', '<=', Carbon::today()->addDays(14))
            ->select(
                'cd.id_consulta_doctor',
                'cd.citado',
                'per.nombre',
                'per.apellido',
                'cd.impresion_diagnostica'
            )
            ->orderBy('cd.citado')
            ->limit(5)
            ->get();
        
        // ==================== 7. ESTADÍSTICAS CLÍNICAS ====================
        $diagnosticosComunes = DB::table('consulta_doctor')
            ->where('doctor_id', $medicoId)
            ->whereNotNull('impresion_diagnostica')
            ->where('impresion_diagnostica', '!=', '')
            ->select('impresion_diagnostica', DB::raw('COUNT(*) as total'))
            ->groupBy('impresion_diagnostica')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        
        $tiposUrgencia = DB::table('consulta_doctor')
            ->where('doctor_id', $medicoId)
            ->where('urgencia', 'si')
            ->whereDate('created_at', '>=', $hace30Dias)
            ->select('tipo_urgencia', DB::raw('COUNT(*) as total'))
            ->groupBy('tipo_urgencia')
            ->get();
        
        // ==================== 8. ESTADÍSTICAS DE CITAS ====================
        $citasPorResultado = DB::table('consulta_doctor')
            ->where('doctor_id', $medicoId)
            ->whereNotNull('citado')
            ->where('citado', '>=', $hace30Dias)
            ->select('resultado', DB::raw('COUNT(*) as total'))
            ->groupBy('resultado')
            ->get();
        
        $citasAtrasadas = DB::table('consulta_doctor')
            ->where('doctor_id', $medicoId)
            ->whereNotNull('citado')
            ->whereDate('citado', '<', $hoy)
            ->where(function($query) {
                $query->whereNull('created_at')
                      ->orWhereRaw('DATE(created_at) > DATE(citado)');
            })
            ->count();
        
        $inicioProximaSemana = Carbon::today()->addDays(1);
        $finProximaSemana = Carbon::today()->addDays(7);
        
        $agendaProximaSemana = DB::table('consulta_doctor')
            ->where('doctor_id', $medicoId)
            ->whereNotNull('citado')
            ->whereBetween('citado', [$inicioProximaSemana, $finProximaSemana])
            ->select(DB::raw('DATE(citado) as fecha'), DB::raw('COUNT(*) as total'))
            ->groupBy(DB::raw('DATE(citado)'))
            ->orderBy('fecha')
            ->get();
        
        // ==================== 9. PACIENTES ESPECÍFICOS ====================
        $distribucionSexo = DB::table('consulta_doctor as cd')
            ->join('pacientes as p', 'cd.paciente_id', '=', 'p.id_paciente')
            ->join('personas as per', 'p.persona_id', '=', 'per.id_persona')
            ->where('cd.doctor_id', $medicoId)
            ->whereNotNull('cd.citado')
            ->where('cd.citado', '>=', $hace30Dias)
            ->whereNotNull('per.sexo')
            ->select('per.sexo', DB::raw('COUNT(*) as total'))
            ->groupBy('per.sexo')
            ->get();
        
        $edadPromedio = DB::table('consulta_doctor as cd')
            ->join('pacientes as p', 'cd.paciente_id', '=', 'p.id_paciente')
            ->join('personas as per', 'p.persona_id', '=', 'per.id_persona')
            ->where('cd.doctor_id', $medicoId)
            ->whereNotNull('cd.citado')
            ->where('cd.citado', '>=', $hace30Dias)
            ->whereNotNull('per.edad')
            ->select(DB::raw('AVG(per.edad) as promedio'))
            ->first();

        return view('dashboards.MedicoDashboard', compact(
            // Actividad
            'consultasHoy',
            'pacientesSemana',
            
            // Seguimiento
            'pacientesSeguimiento',
            'pacientesReferidos',
            
            // Recetas y exámenes
            'recetasHoy',
            'examenesHoy',
            
            // Urgencias
            'urgenciasHoy',
            
            // Agenda y citas
            'citasHoy',
            'citasProximas',
            'consultasRecientes',
            'seguimientoProximo',
            
            // Estadísticas
            'diagnosticosComunes',
            'tiposUrgencia',
            'citasPorResultado',
            'citasAtrasadas',
            'agendaProximaSemana',
            
            // Demográficos
            'distribucionSexo',
            'edadPromedio',
            
            // Variables adicionales que necesita la vista
            'hoy'  // ← AÑADE ESTA LÍNEA
        ));
    }
}