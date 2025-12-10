<?php

namespace Database\Factories;

use App\Models\Libro;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'puntuacion' => fake()->numberBetween(-10000, 10000),
            'comentario' => fake()->text(),
            'fecha_feedback' => fake()->date(),
            'user_id' => User::factory(),
            'libro_id' => Libro::factory(),
        ];
    }
}
