<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = collect([
            [
                'id'    => 1,
                'name'  => 'Bike Tyres & Tubes',
                'slug'  => 'bike_tyres_tubes',
                'user_id' => 1,
            ],
            [
                'id'    => 2,
                'name'  => 'Car Tyres & Tubes',
                'slug'  => 'car_tyres_tubes',
                'user_id' => 1,
            ],
            [
                'id'    => 3,
                'name'  => 'Tractor & Heavy Duty',
                'slug'  => 'tractor_heavy_duty',
                'user_id' => 1,
            ],
            [
                'id'    => 4,
                'name'  => 'Nozzles & Valves',
                'slug'  => 'nozzles_valves',
                'user_id' => 1,
            ],
            [
                'id'    => 5,
                'name'  => 'Accessories & Patches',
                'slug'  => 'accessories_patches',
                'user_id' => 1,
            ]
        ]);

        $categories->each(function ($category) {
            Category::insert($category);
        });
    }
}
