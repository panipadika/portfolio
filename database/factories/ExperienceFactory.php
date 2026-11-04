<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Experience>
 */
class ExperienceFactory extends Factory
{
    protected $model = Experience::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(['education', 'experience']),
            'institution' => fake()->company(),
            'role_or_major' => fake()->jobTitle(),
            'period' => '2023 - 2025',
            'description' => fake()->sentence(),
            'sort_order' => fake()->numberBetween(1, 10),
        ];
    }

    /**
     * Indicate that the experience is an education entry.
     */
    public function education(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'education',
        ]);
    }

    /**
     * Indicate that the experience is a work/organization experience entry.
     */
    public function experience(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'experience',
        ]);
    }
}
