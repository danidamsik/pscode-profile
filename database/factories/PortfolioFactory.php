<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Portfolio>
 */
class PortfolioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'category' => fake()->randomElement(['website', 'mobile', 'other']),
            'client_name' => fake()->company(),
            'short_description' => fake()->sentence(12),
            'description' => fake()->paragraphs(3, true),
            'technologies' => fake()->randomElements(['Laravel', 'Vue', 'Inertia', 'Tailwind CSS', 'MySQL', 'Flutter'], 3),
            'thumbnail' => 'images/portfolio/sample-'.fake()->numberBetween(1, 6).'.jpg',
            'project_url' => fake()->optional()->url(),
            'is_featured' => fake()->boolean(35),
            'is_published' => true,
            'sort_order' => fake()->numberBetween(1, 20),
            'published_at' => now()->subDays(fake()->numberBetween(1, 60)),
        ];
    }
}
