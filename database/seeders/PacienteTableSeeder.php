<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Paciente;

class PacienteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ([1, 3, 5, 7, 9] as $personaId) {
            Paciente::create([
                'persona_id' => $personaId
            ]);
        }
    }
}
