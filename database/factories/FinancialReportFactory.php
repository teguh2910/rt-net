<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FinancialReport>
 */
class FinancialReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $openingBalance = $this->faker->randomFloat(2, 1000000, 10000000);
        $totalIncome = $this->faker->randomFloat(2, 500000, 5000000);
        $totalExpense = $this->faker->randomFloat(2, 300000, 3000000);
        $closingBalance = $openingBalance + $totalIncome - $totalExpense;

        return [
            'month' => $this->faker->numberBetween(1, 12),
            'year' => $this->faker->numberBetween(2024, 2025),
            'opening_balance' => $openingBalance,
            'total_income' => $totalIncome,
            'total_expense' => $totalExpense,
            'closing_balance' => $closingBalance,
            'notes' => $this->faker->optional()->paragraph(),
            'created_by' => \App\Models\User::factory(),
        ];
    }
}
