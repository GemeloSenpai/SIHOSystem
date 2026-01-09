<?php

namespace Database\Factories;

use App\Models\ExamenMedico;
use App\Models\Paciente;
use App\Models\Empleado;
use App\Models\ConsultaDoctor;
use App\Models\Examen;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamenMedicoFactory extends Factory
{
    protected $model = ExamenMedico::class;

    public function definition(): array
    {
        return [
            'paciente_id' => Paciente::inRandomOrder()->first() ?? Paciente::factory(),
            'doctor_id' => Empleado::whereHas('user', fn($q) => $q->where('role', 'medico'))
                            ->inRandomOrder()->first() ?? Empleado::factory()->medico(),
            'consulta_id' => ConsultaDoctor::inRandomOrder()->first() ?? ConsultaDoctor::factory(),
            'examen_id' => Examen::inRandomOrder()->first() ?? Examen::factory(),
            'fecha_asignacion' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }
}