<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->word(),
            'apellidos' => fake()->word(),
            'email' => fake()->safeEmail(),
            'password' => fake()->password(),
            'telefono' => fake()->word(),
            'role' => fake()->randomElement(["admin","user"]),
            'reputacion' => fake()->word(),
            'preferencias_categoria' => fake()->text(),
            'nivel_acceso' => fake()->word(),
            'area_responsabilidad' => fake()->text(),
            'fecha_registro' => fake()->date(),
            'estado' => fake()->word(),
        ];
    }
}
