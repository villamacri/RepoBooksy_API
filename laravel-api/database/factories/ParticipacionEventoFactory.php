<?php

namespace Database\Factories;

use App\Models\Evento;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ParticipacionEventoFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'fecha_inscripcion' => fake()->date(),
            'estado' => fake()->word(),
            'user_id' => User::factory(),
            'evento_id' => Evento::factory(),
        ];
    }
}
