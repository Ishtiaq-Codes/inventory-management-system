<?php

namespace Database\Seeders;

use App\Models\Product;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = collect([
            [
                'name' => 'General Tyre 70cc (Bike)',
                'slug' => 'general-tyre-70cc-bike',
                'code' => 001,
                'quantity' => 50,
                'buying_price' => 1200,
                'selling_price' => 1500,
                'quantity_alert' => 10,
                'tax' => 18,
                'tax_type' => 1,
                'notes' => 'Standard bike tyre',
                'category_id' => 1,
                'unit_id' => 1,
                'user_id'=>1,
                'uuid'=>Str::uuid(),
                'product_image' => 'assets/img/products/default.webp'
            ],
            [
                'name' => 'Panther Tyre 125cc (Bike)',
                'slug' => 'panther-tyre-125cc',
                'code' => 002,
                'quantity' => 30,
                'buying_price' => 2500,
                'selling_price' => 3200,
                'quantity_alert' => 5,
                'tax' => 18,
                'tax_type' => 1,
                'notes' => 'Premium bike tyre',
                'category_id' => 1,
                'unit_id' => 1,
                'user_id'=>1,
                'uuid'=>Str::uuid(),
                'product_image' => 'assets/img/products/default.webp'
            ],
            [
                'name' => 'Servis Car Tyre 175/70 R13',
                'slug' => 'servis-car-tyre-175-70-r13',
                'code' => 003,
                'quantity' => 20,
                'buying_price' => 12000,
                'selling_price' => 15500,
                'quantity_alert' => 4,
                'tax' => 18,
                'tax_type' => 1,
                'notes' => 'Standard car tyre',
                'category_id' => 2,
                'unit_id' => 1,
                'user_id'=>1,
                'uuid'=>Str::uuid(),
                'product_image' => 'assets/img/products/default.webp'
            ],
            [
                'name' => 'Tractor Tube 16-inch',
                'slug' => 'tractor-tube-16-inch',
                'code' => 004,
                'quantity' => 15,
                'buying_price' => 4500,
                'selling_price' => 5800,
                'quantity_alert' => 2,
                'tax' => 18,
                'tax_type' => 1,
                'notes' => 'Heavy duty tube',
                'category_id' => 3,
                'unit_id' => 1,
                'user_id'=>1,
                'uuid'=>Str::uuid(),
                'product_image' => 'assets/img/products/default.webp'
            ],
            [
                'name' => 'Tubeless Valve / Nozzle',
                'slug' => 'tubeless-valve-nozzle',
                'code' => 005,
                'quantity' => 100,
                'buying_price' => 150,
                'selling_price' => 300,
                'quantity_alert' => 20,
                'tax' => 18,
                'tax_type' => 1,
                'notes' => 'Standard tubeless valve',
                'category_id' => 4,
                'unit_id' => 1,
                'user_id'=>1,
                'uuid'=>Str::uuid(),
                'product_image' => 'assets/img/products/default.webp'
            ],
            [
                'name' => 'Puncture Patch Box',
                'slug' => 'puncture-patch-box',
                'code' => 006,
                'quantity' => 40,
                'buying_price' => 800,
                'selling_price' => 1200,
                'quantity_alert' => 5,
                'tax' => 18,
                'tax_type' => 1,
                'notes' => 'Box of 50 patches',
                'category_id' => 5,
                'unit_id' => 1,
                'user_id'=>1,
                'uuid'=>Str::uuid(),
                'product_image' => 'assets/img/products/default.webp'
            ]
        ]);

        $products->each(function ($product){
            Product::create($product);
        });
    }
}
