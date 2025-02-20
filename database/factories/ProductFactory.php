<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'product_name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(100, 10000),
            'stock' => $this->faker->numberBetween(1, 100),
            'company_id' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->sentence(),
            'img_path' => null,
        ];
    }
}