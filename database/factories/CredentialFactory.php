<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Credential;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Credential>
 */
class CredentialFactory extends Factory
{
    protected $model = Credential::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category' => fake()->randomElement(['certificate', 'achievement', 'tech_stack']),
            'title' => fake()->words(2, true),
            'issuer_or_level' => fake()->company(),
            'year' => (string) fake()->year(),
            'image_or_icon' => '/assets/images/certificates/sample.webp',
            'credential_url' => fake()->url(),
            'sort_order' => fake()->numberBetween(1, 10),
        ];
    }

    /**
     * Indicate that the credential is a certificate.
     */
    public function certificate(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'certificate',
        ]);
    }

    /**
     * Indicate that the credential is an achievement.
     */
    public function achievement(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'achievement',
        ]);
    }

    /**
     * Indicate that the credential is a tech stack item.
     */
    public function techStack(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'tech_stack',
        ]);
    }
}
