<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Finance>
 */
class FinanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['pemasukan', 'pengeluaran']);

        $categories = [
            'pemasukan' => ['iuran', 'donasi', 'lainnya'],
            'pengeluaran' => ['kebersihan', 'perbaikan', 'kegiatan', 'lainnya'],
        ];

        return [
            'user_id' => \App\Models\User::factory(),
            'type' => $type,
            'category' => $this->faker->randomElement($categories[$type]),
            'description' => $this->faker->sentence(),
            'amount' => $this->faker->randomFloat(2, 10000, 500000),
            'transaction_date' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }
}
