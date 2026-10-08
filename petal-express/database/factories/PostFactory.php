<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => ucwords(fake()->words(3, true)),
            'body' => fake()->sentence(12),
            'price' => '₱'.number_format(fake()->numberBetween(899, 3999)),
            'user_id' => User::factory(),
            // seeder creates the categories first, then this picks one
            'category_id' => Category::query()->inRandomOrder()->value('id'),
        ];
    }
}
