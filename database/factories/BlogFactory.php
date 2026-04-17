<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(5);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'excerpt' => fake()->sentence(18),
            'content' => fake()->paragraphs(6, true),
            'thumbnail' => 'images/blog/post-'.fake()->numberBetween(1, 6).'.jpg',
            'images' => [
                'images/blog/gallery-'.fake()->numberBetween(1, 8).'.jpg',
                'images/blog/gallery-'.fake()->numberBetween(1, 8).'.jpg',
            ],
            'is_published' => true,
            'published_at' => now()->subDays(fake()->numberBetween(1, 45)),
        ];
    }
}
