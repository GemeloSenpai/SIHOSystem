<?php

namespace Database\Seeders;

use App\Models\ExamenMedico;
use App\Models\ConsultaDoctor;
use App\Models\Examen;
use Illuminate\Database\Seeder;

class ExamenMedicoTableSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🔬 Creando exámenes médicos...');
        
        $consultas = ConsultaDoctor::all();
        $examenes = Examen::all();
        
        if ($consultas->isEmpty() || $examenes->isEmpty()) {
            $this->command->warn('❌ No hay consultas o exámenes para asignar');
            return;
        }
        
        $examenesMedicosCreados = 0;
        $maxExamenes = min(20, $consultas->count() * 2); // Máximo 20 exámenes médicos
        
        for ($i = 0; $i < $maxExamenes; $i++) {
            $consulta = $consultas->random();
            $examen = $examenes->random();
            
            // Verificar que no exista ya esta combinación
            $existe = ExamenMedico::where([
                'paciente_id' => $consulta->paciente_id,
                'consulta_id' => $consulta->id_consulta_doctor,
                'examen_id' => $examen->id_examen
            ])->exists();
            
            if (!$existe) {
                ExamenMedico::create([
                    'paciente_id' => $consulta->paciente_id,
                    'doctor_id' => $consulta->doctor_id,
                    'consulta_id' => $consulta->id_consulta_doctor,
                    'examen_id' => $examen->id_examen,
                    'fecha_asignacion' => $consulta->created_at ?? now(),
                ]);
                $examenesMedicosCreados++;
            }
        }
        
        $this->command->info("✅ Tabla examenes_medicos poblada con {$examenesMedicosCreados} registros");
    }
}