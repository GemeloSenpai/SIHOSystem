<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaTableSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            ['nombre_categoria' => 'Hematología'],
            ['nombre_categoria' => 'Bioquímica Sanguínea'],
            ['nombre_categoria' => 'Microbiología'],
            ['nombre_categoria' => 'Inmunología'],
            ['nombre_categoria' => 'Orina'],
            ['nombre_categoria' => 'Heces'],
            ['nombre_categoria' => 'Radiología'],
            ['nombre_categoria' => 'Ultrasonido'],
            ['nombre_categoria' => 'Tomografía'],
            ['nombre_categoria' => 'Electrocardiograma'],
            ['nombre_categoria' => 'Ecocardiograma'],
            ['nombre_categoria' => 'Endoscopia'],
            ['nombre_categoria' => 'Pruebas Especiales'],
            ['nombre_categoria' => 'Marcadores Tumorales'],
            ['nombre_categoria' => 'Hormonas'],
            ['nombre_categoria' => 'Coagulación'],
            ['nombre_categoria' => 'Serología'],
            ['nombre_categoria' => 'Parasitología'],
            ['nombre_categoria' => 'Citología'],
            ['nombre_categoria' => 'Histopatología'],
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }

        $this->command->info('✅ Tabla categorias poblada con 20 registros');
    }
}