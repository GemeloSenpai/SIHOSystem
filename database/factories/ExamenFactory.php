<?php

namespace Database\Factories;

use App\Models\Examen;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamenFactory extends Factory
{
    protected $model = Examen::class;

    public function definition(): array
    {
        $categoria = Categoria::inRandomOrder()->first() ?? Categoria::factory()->create();
        
        $examenesPorCategoria = [
            'Hematología' => ['Hemograma Completo', 'Plaquetas', 'VSG', 'Hemoglobina', 'Hematocrito', 'Recuento de Glóbulos Blancos', 'Recuento de Glóbulos Rojos', 'Índices Hematimétricos', 'Frotis de Sangre Periférica', 'Grupo Sanguíneo y Rh'],
            'Bioquímica' => ['Glucosa', 'Colesterol Total', 'Triglicéridos', 'Creatinina', 'Ácido Úrico', 'Urea', 'TGO/AST', 'TGP/ALT', 'Fosfatasa Alcalina', 'Bilirrubina Total', 'Bilirrubina Directa', 'Proteínas Totales', 'Albumina', 'Calcio', 'Fósforo'],
            'Radiología' => ['Rayos X de Tórax', 'Rayos X Abdominal', 'Rayos X de Columna', 'Rayos X de Cráneo', 'Rayos X de Extremidades', 'Rayos X de Cadera', 'Rayos X de Rodilla'],
            'Ultrasonido' => ['US Abdominal', 'US Renal', 'US Ginecológico', 'US Mamario', 'US Tiroideo', 'US Próstata', 'US Obstétrico', 'US Doppler Color'],
            'Electrocardiograma' => ['ECG de Reposo', 'ECG de Esfuerzo', 'Holter 24h', 'Monitor de Eventos', 'Prueba de Mesa Basculante'],
            'Tomografía' => ['TAC de Cráneo', 'TAC de Tórax', 'TAC Abdominal', 'TAC de Columna', 'TAC de Extremidades'],
            'Endoscopia' => ['Endoscopia Digestiva Alta', 'Colonoscopia', 'Broncoscopia', 'Cistoscopia', 'Laringoscopia'],
            'Microbiología' => ['Urocultivo', 'Coprocultivo', 'Cultivo de Exudado', 'Antibiograma', 'Tinción de Gram'],
            'Inmunología' => ['VIH', 'Hepatitis B', 'Hepatitis C', 'VDRL/RPR', 'Factor Reumatoide', 'ANA', 'Anti-DNA'],
            'Orina' => ['Examen General de Orina', 'Urocultivo', 'Depuración de Creatinina', 'Proteinuria 24h', 'Sedimento Urinario'],
        ];
        
        $nombreCategoria = $categoria->nombre_categoria;
        
        // Si la categoría no está en el array, usar lista general
        if (!isset($examenesPorCategoria[$nombreCategoria])) {
            $listaExamenes = [
                'Examen General', 'Consulta Médica', 'Valoración Preoperatoria',
                'Control de Rutina', 'Evaluación Cardiológica', 'Chequeo Geriátrico',
                'Perfil Hepático', 'Perfil Renal', 'Perfil Lipídico', 'Perfil Tiroideo'
            ];
        } else {
            $listaExamenes = $examenesPorCategoria[$nombreCategoria];
        }
        
        // QUITAR unique() para evitar el error
        return [
            'nombre_examen' => $this->faker->randomElement($listaExamenes) . ' ' . $this->faker->word(),
            'categoria_id' => $categoria->id_categoria,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}