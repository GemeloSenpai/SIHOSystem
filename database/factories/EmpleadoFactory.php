<?php

namespace Database\Factories;

use App\Models\Empleado;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmpleadoFactory extends Factory
{
    protected $model = Empleado::class;

    public function definition(): array
    {
        $gender = $this->faker->randomElement(['M', 'F']);
        
        // Teléfono: código área (2-4 dígitos) - número (6-8 dígitos)
        $codigoArea = $this->faker->randomElement(['2', '3', '7', '8']) . $this->faker->numerify('##');
        $numero = $this->faker->numerify('#######');
        $telefono = "{$codigoArea}-{$numero}";
        
        return [
            'user_id' => User::factory(),
            'nombre' => $this->faker->firstName(),
            'apellido' => $this->faker->lastName(),
            'edad' => $this->faker->numberBetween(22, 65),
            'fecha_nacimiento' => $this->faker->dateTimeBetween('-65 years', '-22 years'),
            'dni' => $this->generateDNIEmpleado(),
            'sexo' => $gender,
            'direccion' => $this->faker->address(),
            'telefono' => $telefono,
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => now(),
        ];
    }
    
    private function generateDNIEmpleado(): string
    {
        $correlativo = str_pad($this->faker->unique()->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT);
        return "EMP-001-{$correlativo}";
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Empleado $empleado) {
            // Asegurar que el email sea @siho.com
            $username = strtolower($empleado->nombre . '.' . $empleado->apellido);
            $empleado->user->email = str_replace(' ', '.', $username) . '@siho.com';
        });
    }

    // AÑADE ESTOS MÉTODOS:
    public function medico(): static
    {
        return $this->state(fn (array $attributes) => [
            'dni' => 'MED-' . str_pad($this->faker->unique()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT),
        ])->afterCreating(function (Empleado $empleado) {
            $empleado->user->update(['role' => 'medico']);
        });
    }

    public function enfermero(): static
    {
        return $this->state(fn (array $attributes) => [
            'dni' => 'ENF-' . str_pad($this->faker->unique()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT),
        ])->afterCreating(function (Empleado $empleado) {
            $empleado->user->update(['role' => 'enfermero']);
        });
    }

    // ¡ESTE ES EL MÉTODO QUE FALTA!
    public function recepcionista(): static
    {
        return $this->state(fn (array $attributes) => [
            'dni' => 'REC-' . str_pad($this->faker->unique()->numberBetween(1, 999), 3, '0', STR_PAD_LEFT),
        ])->afterCreating(function (Empleado $empleado) {
            $empleado->user->update(['role' => 'recepcionista']);
        });
    }
}