<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DigitalLetter>
 */
class DigitalLetterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $letterTypes = ['domisili', 'usaha', 'tidak_mampu', 'kelahiran', 'kematian'];
        $letterType = $this->faker->randomElement($letterTypes);

        return [
            'letter_type' => $letterType,
            'letter_number' => 'SK/'.$this->faker->unique()->numerify('###').'/RT03/'.$this->faker->year(),
            'resident_id' => \App\Models\Resident::factory(),
            'letter_content' => 'Surat Keterangan '.ucfirst($letterType),
            'signature_path' => null,
            'issued_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'valid_until' => $this->faker->dateTimeBetween('now', '+6 months'),
            'pdf_path' => null,
            'issued_by' => \App\Models\User::factory(),
        ];
    }
}
