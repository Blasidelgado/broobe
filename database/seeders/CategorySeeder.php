<?php
namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = config('const.categories');

        foreach ($categories as $categoryName) {
            Category::factory()->create(['name' => $categoryName]);
        }
    }
}
