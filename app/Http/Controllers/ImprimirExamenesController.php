<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ImprimirExamenesController extends Controller
{
    public function show($expedienteId)
    {
        // Obtener expediente usando el modelo Eloquent para tener relaciones
        $expediente = \App\Models\Expediente::with([
            'paciente.persona',
            'doctor',
            'consulta.examenesMedicos.examen.categoria'
        ])->findOrFail($expedienteId);

        // Datos del paciente
        $paciente = $expediente->paciente;
        $persona = $paciente->persona;
        
        // Preparar datos para la vista
        $edad = $persona->edad ?? 'N/A';
        $fecha_nacimiento = $persona->fecha_nacimiento;
        $telefono = $persona->telefono ?? 'No registrado';
        $codigo_paciente = $expediente->paciente->codigo_paciente ?? 'N/A';
        
        // Médico
        $medicoNombre = 'No asignado';
        if ($expediente->doctor) {
            $medicoNombre = ($expediente->doctor->nombre ?? '') . ' ' . ($expediente->doctor->apellido ?? '');
        }

        // Obtener exámenes
        $examenes = collect();
        if ($expediente->consulta) {
            $examenes = $expediente->consulta->examenesMedicos()
                ->with(['examen.categoria'])
                ->get();
        }

        // Organizar por categoría
        $porCategorias = [];
        foreach ($examenes as $examen) {
            $categoria = $examen->examen->categoria->nombre_categoria ?? 'Sin categoría';
            $nombreExamen = $examen->examen->nombre_examen;
            
            if (!isset($porCategorias[$categoria])) {
                $porCategorias[$categoria] = [];
            }
            
            if (!in_array($nombreExamen, $porCategorias[$categoria])) {
                $porCategorias[$categoria][] = $nombreExamen;
            }
        }

        // Mapeo a nombres bonitos
        $catMap = [
            'HEMATOLOGIA Y COAGULACION' => 'Hematología y Coagulación',
            'MICROBIOLOGIA' => 'Microbiología',
            'INMUNOLOGIA' => 'Inmunología',
            'QUIMICA CLINICA' => 'Química Clínica',
            'UROANALISIS PARASITOLOGIA' => 'Uroanálisis Parasitología',
            'PRUEBAS ESPECIALES' => 'Pruebas Especiales',
            'MISCELANEAS' => 'Misceláneas',
            'TOXICOLOGIA Y FARMACOS' => 'Toxicología y Fármacos',
            'PRUEBAS HORMONALES' => 'Pruebas Hormonales',
            'PRUEBAS DE ORINA 24 HORAS' => 'Pruebas de Orina 24 horas',
        ];

        $porCategoriasBonitos = [];
        foreach ($porCategorias as $cat => $items) {
            $catBonita = $catMap[$cat] ?? $cat;
            $porCategoriasBonitos[$catBonita] = $items;
        }

        $fechaPrimera = $expediente->fecha_creacion 
            ? Carbon::parse($expediente->fecha_creacion)->format('d/m/Y')
            : now()->format('d/m/Y');

        return view('logica.admin.ver-examenes', [
            'expediente' => $expediente,
            'paciente' => (object)[
                'nombre' => $persona->nombre,
                'apellido' => $persona->apellido,
                'telefono' => $telefono,
                'codigo_paciente' => $codigo_paciente,
            ],
            'edad' => $edad,
            'fecha_nacimiento' => $fecha_nacimiento,
            'telefono' => $telefono,
            'medicoNombre' => $medicoNombre,
            'fechaPrimera' => $fechaPrimera,
            'porCategorias' => $porCategoriasBonitos,
        ]);
    }
}