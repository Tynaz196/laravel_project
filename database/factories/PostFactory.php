<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Enums\PostStatus;

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
            'user_id' => User::inRandomOrder()->first()?->id ?? 1, // Lấy user ngẫu nhiên có sẵn
            'title' => $this->faker->sentence(6),
            'content' => $this->faker->paragraphs(5, true),
            'description' => $this->faker->paragraph(2),
            'publish_date' => $this->faker->dateTimeBetween('-1 month', '+1 week'),
            'status' => $this->faker->randomElement([
                PostStatus::PENDING,
                PostStatus::APPROVED,
                PostStatus::DENNIED
            ]),
        ];
    }

    /**
     * Indicate that the post is approved.
     */
    public function approved(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => PostStatus::APPROVED,
        ]);
    }

    /**
     * Indicate that the post is pending.
     */
    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => PostStatus::PENDING,
        ]);
    }
}
