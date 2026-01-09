<?php

namespace Database\Factories;

use App\Models\Encargado;
use App\Models\Persona;
use Illuminate\Database\Eloquent\Factories\Factory;

class EncargadoFactory extends Factory
{
    protected $model = Encargado::class;

    public function definition(): array
    {
        return [
            'persona_id' => Persona::factory(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }
}