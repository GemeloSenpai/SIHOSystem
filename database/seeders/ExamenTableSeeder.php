<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Examen;
use App\Models\Categoria;

class ExamenTableSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener categorías por nombre para asignar IDs
        $categorias = Categoria::all()->keyBy('nombre_categoria');

        $examenes = [
            // Hematología
            ['nombre_examen' => 'Hemograma Completo', 'categoria' => 'Hematología'],
            ['nombre_examen' => 'Recuento de Plaquetas', 'categoria' => 'Hematología'],
            ['nombre_examen' => 'Velocidad de Sedimentación Globular (VSG)', 'categoria' => 'Hematología'],
            ['nombre_examen' => 'Frotis de Sangre Periférica', 'categoria' => 'Hematología'],
            
            // Bioquímica Sanguínea
            ['nombre_examen' => 'Glucosa en Ayunas', 'categoria' => 'Bioquímica Sanguínea'],
            ['nombre_examen' => 'Curva de Tolerancia a la Glucosa', 'categoria' => 'Bioquímica Sanguínea'],
            ['nombre_examen' => 'Colesterol Total', 'categoria' => 'Bioquímica Sanguínea'],
            ['nombre_examen' => 'Colesterol HDL', 'categoria' => 'Bioquímica Sanguínea'],
            ['nombre_examen' => 'Colesterol LDL', 'categoria' => 'Bioquímica Sanguínea'],
            ['nombre_examen' => 'Triglicéridos', 'categoria' => 'Bioquímica Sanguínea'],
            ['nombre_examen' => 'Ácido Úrico', 'categoria' => 'Bioquímica Sanguínea'],
            ['nombre_examen' => 'Creatinina', 'categoria' => 'Bioquímica Sanguínea'],
            ['nombre_examen' => 'Urea', 'categoria' => 'Bioquímica Sanguínea'],
            ['nombre_examen' => 'TGO/AST', 'categoria' => 'Bioquímica Sanguínea'],
            ['nombre_examen' => 'TGP/ALT', 'categoria' => 'Bioquímica Sanguínea'],
            ['nombre_examen' => 'Fosfatasa Alcalina', 'categoria' => 'Bioquímica Sanguínea'],
            ['nombre_examen' => 'Bilirrubina Total', 'categoria' => 'Bioquímica Sanguínea'],
            ['nombre_examen' => 'Bilirrubina Directa', 'categoria' => 'Bioquímica Sanguínea'],
            ['nombre_examen' => 'Proteínas Totales', 'categoria' => 'Bioquímica Sanguínea'],
            ['nombre_examen' => 'Albumina', 'categoria' => 'Bioquímica Sanguínea'],
            
            // Orina
            ['nombre_examen' => 'Examen General de Orina', 'categoria' => 'Orina'],
            ['nombre_examen' => 'Urocultivo', 'categoria' => 'Orina'],
            ['nombre_examen' => 'Depuración de Creatinina', 'categoria' => 'Orina'],
            ['nombre_examen' => 'Proteinuria en 24 horas', 'categoria' => 'Orina'],
            
            // Radiología
            ['nombre_examen' => 'Radiografía de Tórax PA y Lateral', 'categoria' => 'Radiología'],
            ['nombre_examen' => 'Radiografía de Columna Lumbar', 'categoria' => 'Radiología'],
            ['nombre_examen' => 'Radiografía de Columna Cervical', 'categoria' => 'Radiología'],
            ['nombre_examen' => 'Radiografía de Rodilla', 'categoria' => 'Radiología'],
            ['nombre_examen' => 'Radiografía de Cadera', 'categoria' => 'Radiología'],
            
            // Ultrasonido
            ['nombre_examen' => 'Ultrasonido Abdominal Total', 'categoria' => 'Ultrasonido'],
            ['nombre_examen' => 'Ultrasonido Renal', 'categoria' => 'Ultrasonido'],
            ['nombre_examen' => 'Ultrasonido Hepático', 'categoria' => 'Ultrasonido'],
            ['nombre_examen' => 'Ultrasonido Ginecológico', 'categoria' => 'Ultrasonido'],
            ['nombre_examen' => 'Ultrasonido Obstétrico', 'categoria' => 'Ultrasonido'],
            ['nombre_examen' => 'Ultrasonido de Próstata', 'categoria' => 'Ultrasonido'],
            ['nombre_examen' => 'Ultrasonido Mamario', 'categoria' => 'Ultrasonido'],
            ['nombre_examen' => 'Ultrasonido Tiroideo', 'categoria' => 'Ultrasonido'],
            
            // Cardiología
            ['nombre_examen' => 'Electrocardiograma (ECG)', 'categoria' => 'Electrocardiograma'],
            ['nombre_examen' => 'Prueba de Esfuerzo', 'categoria' => 'Electrocardiograma'],
            ['nombre_examen' => 'Holter de 24 horas', 'categoria' => 'Electrocardiograma'],
            ['nombre_examen' => 'Ecocardiograma Transtorácico', 'categoria' => 'Ecocardiograma'],
            ['nombre_examen' => 'Ecocardiograma Doppler', 'categoria' => 'Ecocardiograma'],
            
            // Tomografía
            ['nombre_examen' => 'Tomografía de Cráneo', 'categoria' => 'Tomografía'],
            ['nombre_examen' => 'Tomografía de Tórax', 'categoria' => 'Tomografía'],
            ['nombre_examen' => 'Tomografía Abdominal', 'categoria' => 'Tomografía'],
            ['nombre_examen' => 'Tomografía de Columna', 'categoria' => 'Tomografía'],
            
            // Endoscopia
            ['nombre_examen' => 'Endoscopia Digestiva Alta', 'categoria' => 'Endoscopia'],
            ['nombre_examen' => 'Colonoscopia', 'categoria' => 'Endoscopia'],
            ['nombre_examen' => 'Broncoscopia', 'categoria' => 'Endoscopia'],
        ];

        foreach ($examenes as $examen) {
            $categoria = $categorias[$examen['categoria']] ?? null;
            
            if ($categoria) {
                Examen::create([
                    'nombre_examen' => $examen['nombre_examen'],
                    'categoria_id' => $categoria->id_categoria,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('✅ Tabla examenes poblada con ' . count($examenes) . ' registros');
    }
}