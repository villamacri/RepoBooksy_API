<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EventoFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->word(),
            'descripcion' => fake()->text(),
            'fecha_evento' => fake()->date(),
            'ubicacion' => fake()->word(),
            'capacidad_maxima' => fake()->numberBetween(-10000, 10000),
        ];
    }
}
