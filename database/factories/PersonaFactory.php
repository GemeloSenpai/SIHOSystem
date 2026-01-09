<?php

namespace Database\Factories;

use App\Models\Persona;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonaFactory extends Factory
{
    protected $model = Persona::class;

    public function definition(): array
    {
        $gender = $this->faker->randomElement(['M', 'F']);
        $firstName = $gender === 'M' ? $this->faker->firstNameMale() : $this->faker->firstNameFemale();
        $birthDate = $this->faker->dateTimeBetween('-80 years', '-18 years');
        $age = now()->diff($birthDate)->y;
        
        // Teléfono: código área (2-4 dígitos) - número (6-8 dígitos)
        $codigoArea = $this->faker->randomElement(['2', '3', '7', '8']) . $this->faker->numerify('##');
        $numero = $this->faker->numerify('#######');
        $telefono = "{$codigoArea}-{$numero}";
        
        return [
            'nombre' => $firstName,
            'apellido' => $this->faker->lastName(),
            'edad' => $age,
            'fecha_nacimiento' => $birthDate,
            'dni' => $this->generateDNI13(), // 13 dígitos único
            'sexo' => $gender,
            'direccion' => $this->faker->address(),
            'telefono' => $telefono,
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => now(),
        ];
    }
    
    private function generateDNI13(): string
    {
        // Generar DNI de 13 dígitos: 001-08011990-1234A
        $departamento = '001'; // Fijo para simplicidad
        $fechaNac = $this->faker->date('dmY'); // DDMMYYYY
        $correlativo = str_pad($this->faker->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT);
        $letra = $this->faker->randomElement(['A', 'B', 'C', 'D', 'E', 'F']);
        
        return "{$departamento}{$fechaNac}{$correlativo}{$letra}";
    }

    public function masculino(): static
    {
        return $this->state(fn (array $attributes) => [
            'sexo' => 'M',
            'nombre' => $this->faker->firstNameMale(),
        ]);
    }

    public function femenino(): static
    {
        return $this->state(fn (array $attributes) => [
            'sexo' => 'F',
            'nombre' => $this->faker->firstNameFemale(),
        ]);
    }
}