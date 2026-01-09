<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expediente;
use App\Models\Paciente;
use App\Models\Empleado;
use App\Models\SignosVitales;
use App\Models\ConsultaDoctor;
use App\Models\Encargado;

class ExpedienteTableSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener datos necesarios
        $pacientes = Paciente::all();
        $enfermeros = Empleado::whereHas('user', fn($q) => $q->where('role', 'enfermero'))->get();
        $medicos = Empleado::whereHas('user', fn($q) => $q->where('role', 'medico'))->get();
        $encargados = Encargado::all();
        
        if ($pacientes->isEmpty()) {
            $this->command->error('❌ No hay pacientes registrados');
            return;
        }
        
        $expedientes = [];
        $codigoBase = 'EXP-' . date('Y') . '-';
        
        foreach ($pacientes as $index => $paciente) {
            // Obtener signos vitales del paciente
            $signosVitales = SignosVitales::where('paciente_id', $paciente->id_paciente)->first();
            
            // Obtener consulta del paciente (si existe)
            $consulta = ConsultaDoctor::where('paciente_id', $paciente->id_paciente)->first();
            
            if (!$signosVitales) {
                $this->command->warn("⚠️  Paciente {$paciente->id_paciente} no tiene signos vitales, saltando...");
                continue;
            }
            
            // Asignar personal médico
            $enfermera = $enfermeros[$index % $enfermeros->count()] ?? $enfermeros->first();
            $doctor = $medicos[$index % $medicos->count()] ?? $medicos->first();
            $encargado = $encargados->isNotEmpty() ? $encargados[$index % $encargados->count()] : null;
            
            $expedientes[] = [
                'paciente_id' => $paciente->id_paciente,
                'encargado_id' => $encargado?->id_encargado,
                'enfermera_id' => $enfermera->id_empleado,
                'signos_vitales_id' => $signosVitales->id_signos_vitales,
                'doctor_id' => $doctor->id_empleado,
                'consulta_id' => $consulta?->id_consulta_doctor,
                'fecha_creacion' => now()->subDays(rand(0, 365)),
                'codigo' => $codigoBase . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'estado' => rand(0, 1) ? 'abierto' : 'cerrado',
                'motivo_ingreso' => 'Consulta ' . ['general', 'de rutina', 'especializada', 'de emergencia'][rand(0, 3)],
                'diagnostico' => ['Gastritis', 'Hipertensión', 'Diabetes', 'Ansiedad'][rand(0, 3)],
                'observaciones' => 'Paciente estable. Seguimiento programado.',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        foreach ($expedientes as $expediente) {
            Expediente::create($expediente);
        }
        
        $this->command->info('✅ Tabla expedientes poblada con ' . count($expedientes) . ' registros');
    }
}