<?php

namespace Database\Factories;

use App\Models\Libro;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransaccionFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'tipo_transaccion' => fake()->word(),
            'fecha_transaccion' => fake()->date(),
            'monto' => fake()->randomFloat(2, 0, 999999.99),
            'metodo_pago' => fake()->word(),
            'estado' => fake()->word(),
            'libro_id' => Libro::factory(),
            'comprador_id' => User::factory(),
            'vendedor_id' => User::factory(),
        ];
    }
}
