<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        $roles = ['admin', 'recepcionista', 'enfermero', 'medico'];
        $role = $this->faker->randomElement($roles);
        
        // Formato de email: ejemplo@siho.com
        $username = strtolower($this->faker->userName());
        
        return [
            'name' => $this->faker->name(),
            'email' => "{$username}@siho.com",
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // password para todos
            'role' => $role,
            'estado' => $this->faker->randomElement(['activo', 'inactivo']),
            'remember_token' => Str::random(10),
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => now(),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
            'name' => 'Admin ' . $this->faker->lastName(),
            'email' => 'admin' . $this->faker->unique()->numberBetween(1, 10) . '@siho.com',
            'password' => Hash::make('password123'), // password123 para admin
        ]);
    }

    public function medico(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'medico',
            'name' => ($this->faker->boolean ? 'Dr. ' : 'Dra. ') . $this->faker->firstName() . ' ' . $this->faker->lastName(),
        ]);
    }

    public function enfermero(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'enfermero',
            'name' => 'Enf. ' . $this->faker->firstName() . ' ' . $this->faker->lastName(),
        ]);
    }

    public function recepcionista(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'recepcionista',
            'name' => 'Rec. ' . $this->faker->firstName() . ' ' . $this->faker->lastName(),
        ]);
    }
}