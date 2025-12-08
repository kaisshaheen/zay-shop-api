<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
     

    public function definition(): array
    {
        
        $files = Storage::disk('public')->files('img');

        $randomImagePath = !empty($files) ? Arr::random($files) : null;

        if ($randomImagePath) {
            $newPath = 'products/' . basename($randomImagePath);
            Storage::disk('public')->copy($randomImagePath, $newPath);
        }

        $categories = Category::all();
        $categories_id = $categories->pluck("id")->filter()->toArray();

        return [
            'name' => $this->faker->sentence(3),
            'brand' => $this->faker->company,
            'image_path' =>  isset($newPath) ? $newPath : null, 
            'description' => $this->faker->paragraph(2),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'color' => $this->faker->colorName,
            'price' => $this->faker->randomFloat(2, 10, 500),
            'stock' => $this->faker->numberBetween(15 , 100),
            'size' => $this->faker->randomElement(['XS', 'S', 'M', 'L', 'XL', 'XXL']),
            'category_id' => Arr::random($categories_id),
            'admin_id' => 1
        ];
    }
}
