<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'file_path' => 'documents/'.$this->faker->slug().'.pdf',
            'file_type' => $this->faker->randomElement(['pdf', 'docx', 'xlsx', 'jpg', 'png']),
            'document_date' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'description' => $this->faker->paragraph(),
            'uploaded_by' => \App\Models\User::factory(),
        ];
    }
}
