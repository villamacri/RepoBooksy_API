<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $transactionType = $this->faker->randomElement(['sale', 'exchange']);
        
        return [
            'transaction_type' => $transactionType,
            'status' => $this->faker->randomElement(['pending', 'completed', 'cancelled', 'in_progress']),
            'transaction_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'amount' => $transactionType === 'sale' ? $this->faker->randomFloat(2, 5, 50) : null,
            'payment_method' => $transactionType === 'sale' ? $this->faker->randomElement(['credit_card', 'paypal', 'bank_transfer', 'cash']) : null,
            'book_id' => Book::query()->inRandomOrder()->first()?->id ?? Book::factory(),
            'buyer_id' => User::query()->inRandomOrder()->first()?->id ?? User::factory(),
            'seller_id' => User::query()->inRandomOrder()->first()?->id ?? User::factory(),
        ];
    }

    /**
     * Indicate that the transaction is a completed sale.
     */
    public function completedSale(): static
    {
        return $this->state(fn(array $attributes) => [
            'transaction_type' => 'sale',
            'status' => 'completed',
            'amount' => $this->faker->randomFloat(2, 5, 50),
            'payment_method' => $this->faker->randomElement(['credit_card', 'paypal', 'bank_transfer', 'cash']),
        ]);
    }

    /**
     * Indicate that the transaction is a pending exchange.
     */
    public function pendingExchange(): static
    {
        return $this->state(fn(array $attributes) => [
            'transaction_type' => 'exchange',
            'status' => 'pending',
            'amount' => null,
            'payment_method' => null,
        ]);
    }

    /**
     * Indicate that the transaction is a completed exchange.
     */
    public function completedExchange(): static
    {
        return $this->state(fn(array $attributes) => [
            'transaction_type' => 'exchange',
            'status' => 'completed',
            'amount' => null,
            'payment_method' => null,
        ]);
    }
}
