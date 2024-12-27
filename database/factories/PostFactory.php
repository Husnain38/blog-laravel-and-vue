<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'          => User::factory(),
            'title'            => $this->faker->sentence,
            'excerpt'          => $this->faker->sentence(10),
            'description'      => $this->faker->paragraph,
            'image'            => $this->faker->imageUrl(640, 480, 'posts', true, 'Faker'),
            'keywords'         => implode(',', $this->faker->words(5)),
            'meta_title'       => $this->faker->sentence,
            'meta_description' => $this->faker->paragraph(2),
            'published_at'     => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
    public function hasComments($count = 5)
    {
        return $this->has(\App\Models\Comment::factory()->count($count), 'comments');
    }
}
