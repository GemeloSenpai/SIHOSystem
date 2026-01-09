<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Encargado;

class EncargadoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ([2, 4, 6, 8, 10] as $personaId) {
            Encargado::create([
                'persona_id' => $personaId
            ]);
        }
    }
}
