<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Expediente;
use App\Models\ExamenMedico;

class ExpedienteExamenTableSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('📋 Relacionando expedientes con exámenes médicos...');
        
        // Obtener IDs directamente para optimizar
        $expedienteIds = DB::table('expedientes')->pluck('id_expediente')->toArray();
        $examenMedicoIds = DB::table('examenes_medicos')->pluck('id_examen_medico')->toArray();
        
        if (empty($expedienteIds) || empty($examenMedicoIds)) {
            $this->command->warn('❌ No hay expedientes o exámenes médicos para relacionar');
            return;
        }
        
        $relacionesCreadas = 0;
        $batch = [];
        $relacionesExistentes = []; // Para evitar duplicados
        
        // Para cada expediente, asignar 2-4 exámenes médicos
        foreach ($expedienteIds as $expedienteId) {
            // Obtener paciente de este expediente
            $expediente = DB::table('expedientes')
                ->where('id_expediente', $expedienteId)
                ->first();
            
            if (!$expediente) continue;
            
            // Buscar exámenes médicos del MISMO paciente
            $examenesDelPaciente = DB::table('examenes_medicos')
                ->where('paciente_id', $expediente->paciente_id)
                ->pluck('id_examen_medico')
                ->toArray();
            
            // Si no hay exámenes de este paciente, tomar algunos aleatorios
            if (empty($examenesDelPaciente)) {
                $examenesDelPaciente = array_rand($examenMedicoIds, min(3, count($examenMedicoIds)));
                if (!is_array($examenesDelPaciente)) {
                    $examenesDelPaciente = [$examenesDelPaciente];
                }
                $examenesDelPaciente = array_map(fn($idx) => $examenMedicoIds[$idx], $examenesDelPaciente);
            }
            
            // Tomar 2-4 exámenes para este expediente
            $numExamenes = min(rand(2, 4), count($examenesDelPaciente));
            $examenesSeleccionados = array_rand($examenesDelPaciente, $numExamenes);
            
            if (!is_array($examenesSeleccionados)) {
                $examenesSeleccionados = [$examenesSeleccionados];
            }
            
            foreach ($examenesSeleccionados as $examenIdx) {
                $examenMedicoId = $examenesDelPaciente[$examenIdx];
                
                // Clave única para evitar duplicados
                $clave = "{$expedienteId}-{$examenMedicoId}";
                
                if (!isset($relacionesExistentes[$clave])) {
                    $batch[] = [
                        'expediente_id' => $expedienteId,
                        'examen_medico_id' => $examenMedicoId,
                        'fecha_registro' => now()->subDays(rand(0, 30)),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    
                    $relacionesExistentes[$clave] = true;
                    $relacionesCreadas++;
                    
                    // Insertar en lotes de 500
                    if (count($batch) >= 500) {
                        DB::table('expediente_examen')->insert($batch);
                        $batch = [];
                        
                        if ($relacionesCreadas % 1000 == 0) {
                            $this->command->info("   📊 {$relacionesCreadas} relaciones creadas");
                        }
                    }
                }
            }
        }
        
        // Insertar último lote
        if (!empty($batch)) {
            DB::table('expediente_examen')->insert($batch);
        }
        
        $this->command->info("✅ Tabla expediente_examen poblada con {$relacionesCreadas} relaciones");
        $this->command->info("   📈 Promedio: " . round($relacionesCreadas / count($expedienteIds), 2) . " exámenes por expediente");
    }
}