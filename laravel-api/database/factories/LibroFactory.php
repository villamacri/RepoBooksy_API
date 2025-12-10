<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LibroFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'titulo' => fake()->word(),
            'autor' => fake()->word(),
            'editorial' => fake()->word(),
            'anio_editorial' => fake()->numberBetween(-10000, 10000),
            'descripcion' => fake()->text(),
            'estado_fisico' => fake()->word(),
            'tipo_operacion' => fake()->word(),
            'precio' => fake()->randomFloat(2, 0, 999999.99),
            'fecha_publicacion' => fake()->date(),
            'disponibilidad' => fake()->word(),
            'categoria_id' => Categoria::factory(),
            'user_id' => User::factory(),
        ];
    }
}
