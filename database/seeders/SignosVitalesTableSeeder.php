<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Paciente;
use App\Models\Empleado;
use App\Models\SignosVitales;

class SignosVitalesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener enfermeros disponibles (usuarios con rol enfermero)
        $enfermeros = Empleado::whereHas('user', function($query) {
            $query->where('role', 'enfermero');
        })->get();
        
        if ($enfermeros->isEmpty()) {
            $this->command->error('❌ No hay enfermeros registrados');
            return;
        }
        
        // Obtener pacientes
        $pacientes = Paciente::all();
        
        if ($pacientes->isEmpty()) {
            $this->command->error('❌ No hay pacientes registrados');
            return;
        }
        
        $signos = [];
        
        foreach ($pacientes as $index => $paciente) {
            // Asignar enfermero alternando entre los disponibles
            $enfermero = $enfermeros[$index % $enfermeros->count()];
            
            $signos[] = [
                'paciente_id' => $paciente->id_paciente,
                'enfermera_id' => $enfermero->id_empleado,
                'presion_arterial' => $this->generarPresionAleatoria(),
                'fc' => rand(60, 100), // Frecuencia cardíaca
                'fr' => rand(12, 20),  // Frecuencia respiratoria
                'temperatura' => round(36 + (rand(0, 10) / 10), 1), // 36.0 - 37.0
                'so2' => rand(95, 100), // Saturación de oxígeno
                'peso' => round(50 + (rand(0, 50) / 2), 1), // 50.0 - 75.0 kg
                'glucosa' => round(70 + (rand(0, 50) / 2), 1), // 70.0 - 95.0 mg/dL
                'fecha_registro' => now()->subDays(rand(0, 30)),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        foreach ($signos as $signo) {
            SignosVitales::create($signo);
        }
        
        $this->command->info('✅ Tabla signos_vitales poblada con ' . count($signos) . ' registros');
    }
    
    private function generarPresionAleatoria(): string
    {
        $sistolica = rand(100, 140);
        $diastolica = rand(60, 90);
        return "{$sistolica}/{$diastolica}";
    }
}