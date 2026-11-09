<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->randomNumber(4),
            'description' => fake()->paragraph(),
            'image_url' => '/assets/images/projects/sample.webp',
            'tech_tags' => ['React', 'Laravel', 'Tailwind CSS'],
            'demo_url' => fake()->url(),
            'repo_url' => fake()->url(),
            'is_featured' => true,
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
