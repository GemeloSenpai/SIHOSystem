<?php

namespace Database\Factories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriaFactory extends Factory
{
    protected $model = Categoria::class;

    public function definition(): array
    {
        $categorias = [
            'Hematología', 'Bioquímica Sanguínea', 'Microbiología', 
            'Inmunología', 'Orina', 'Heces', 'Radiología', 
            'Ultrasonido', 'Tomografía', 'Electrocardiograma',
            'Ecocardiograma', 'Endoscopia', 'Pruebas Especiales',
            'Marcadores Tumorales', 'Hormonas', 'Coagulación',
            'Serología', 'Parasitología', 'Citología', 'Histopatología'
        ];
        
        return [
            'nombre_categoria' => $this->faker->unique()->randomElement($categorias),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}