<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReporteFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'tipo_reporte' => fake()->word(),
            'descripcion' => fake()->text(),
            'fecha_reporte' => fake()->date(),
            'estado' => fake()->word(),
            'user_id' => User::factory(),
        ];
    }
}
