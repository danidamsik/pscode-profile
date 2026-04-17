<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeamMember>
 */
class TeamMemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'role' => fake()->randomElement(['Founder', 'Project Manager', 'UI/UX Designer', 'Full Stack Developer']),
            'photo' => 'images/team/member-'.fake()->numberBetween(1, 8).'.jpg',
            'description' => fake()->sentence(18),
            'social_links' => [
                'linkedin' => fake()->url(),
                'github' => fake()->url(),
            ],
            'sort_order' => fake()->numberBetween(1, 10),
            'is_active' => true,
        ];
    }
}
