<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'price' => fake()->randomFloat(2, 10, 500),
            'description' => fake()->sentence(),
            'stock' => fake()->numberBetween(0, 100),
            'is_active' => true,
        ];
    }
}
