<?php

namespace Database\Factories;

use App\Models\Portfolio;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PortfolioTeamMember>
 */
class PortfolioTeamMemberFactory extends Factory
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
            'name' => fake()->name(),
            'role' => fake()->randomElement(['Project Manager', 'UI/UX Designer', 'Frontend Developer', 'Backend Developer']),
            'photo' => 'images/team/member-'.fake()->numberBetween(1, 8).'.jpg',
            'description' => fake()->sentence(16),
            'sort_order' => fake()->numberBetween(1, 5),
        ];
    }
}
