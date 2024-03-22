<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => \App\Models\Category::inRandomOrder()->first()->id,
            'title' => fake()->word(),
            'content' => fake()->sentence(),
            'price' => $this->faker->numberBetween($min=1500, $max=6000),
            'image' => NULL
        ];
    }
}
