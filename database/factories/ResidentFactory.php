<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Resident>
 */
class ResidentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nik' => $this->faker->unique()->numerify('32010123########'),
            'name' => $this->faker->name(),
            'no_kk' => $this->faker->numerify('32010101########'),
            'address' => $this->faker->address(),
            'phone_number' => $this->faker->numerify('08##########'),
            'status' => $this->faker->randomElement(['tetap', 'kontrak']),
            'is_head_of_family' => $this->faker->boolean(30),
            'photo_path' => null,
            'user_id' => null,
        ];
    }
}
