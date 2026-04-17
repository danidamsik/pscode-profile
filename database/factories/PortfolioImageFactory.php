<?php

namespace Database\Factories;

use App\Models\Portfolio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PortfolioImage>
 */
class PortfolioImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'portfolio_id' => Portfolio::factory(),
            'image' => 'images/portfolio/gallery-'.fake()->numberBetween(1, 12).'.jpg',
            'alt_text' => fake()->sentence(4),
            'sort_order' => fake()->numberBetween(1, 5),
        ];
    }
}
