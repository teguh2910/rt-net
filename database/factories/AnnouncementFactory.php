<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Announcement>
 */
class AnnouncementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isPublished = $this->faker->boolean(70);

        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(3, true),
            'published_at' => $isPublished ? $this->faker->dateTimeBetween('-1 month', 'now') : now(),
            'expires_at' => $this->faker->boolean(50) ? $this->faker->dateTimeBetween('now', '+1 month') : null,
            'is_published' => $isPublished,
            'send_whatsapp' => $this->faker->boolean(30),
            'send_email' => $this->faker->boolean(30),
            'created_by' => \App\Models\User::factory(),
        ];
    }
}
