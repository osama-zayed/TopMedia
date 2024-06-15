<?php

namespace Database\Factories;

use App\Models\Category;
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
            'product_name' => $this->faker->unique()->sentence(3),
            'product_description' => $this->faker->realText(200),
            'categorie_id' => Category::inRandomOrder()->first(),
            'product_price' => $this->faker->numberBetween(1000, 20000),
            'discount_percentage' => $this->faker->numberBetween(1, 70),
            'image' => [
                $this->faker->imageUrl(640, 480, 'products', true),
                $this->faker->imageUrl(640, 480, 'products', true),
                $this->faker->imageUrl(640, 480, 'products', true),
            ],
        ];
    }
}