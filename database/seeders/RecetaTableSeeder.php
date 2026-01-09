<?php

namespace Database\Seeders;

use App\Models\Receta;
use App\Models\Expediente;
use Illuminate\Database\Seeder;

class RecetaTableSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('💊 Creando recetas médicas...');
        
        $expedientes = Expediente::all();
        
        if ($expedientes->isEmpty()) {
            $this->command->warn('❌ No hay expedientes para crear recetas');
            return;
        }
        
        $recetasCreadas = 0;
        $expedientesConReceta = $expedientes->take(5); // Crear recetas para 5 expedientes
        
        foreach ($expedientesConReceta as $expediente) {
            // Verificar que no exista ya una receta para este expediente
            $existeReceta = Receta::where('expediente_id', $expediente->id_expediente)->exists();
            
            if (!$existeReceta) {
                Receta::create([
                    'expediente_id' => $expediente->id_expediente,
                    'paciente_id' => $expediente->paciente_id,
                    'doctor_id' => $expediente->doctor_id,
                    'creado_por' => $expediente->doctor->user_id ?? 1,
                    'fecha_prescripcion' => $expediente->fecha_creacion,
                    'diagnostico' => $expediente->diagnostico ?? 'Consulta general',
                    'receta' => $this->generarRecetaEjemplo(),
                    'observaciones' => 'Seguir indicaciones. Regresar en control.',
                    'edad_paciente_en_receta' => $expediente->paciente->persona->edad ?? 40,
                    'peso_paciente_en_receta' => rand(55, 85),
                    'alergias_conocidas' => 'Ninguna',
                    'estado' => 'activa',
                    'firma_digital' => null,
                ]);
                $recetasCreadas++;
            }
        }
        
        $this->command->info("✅ Tabla recetas poblada con {$recetasCreadas} registros");
    }
    
    private function generarRecetaEjemplo(): string
    {
        return "PRESCRIPCIÓN:\n\n" .
               "1. Omeprazol 20 mg - 1 tableta diaria antes del desayuno\n" .
               "2. Suplemento multivitamínico - 1 tableta diaria\n\n" .
               "Duración: 30 días\n" .
               "Reposo relativo y dieta blanda.";
    }
}