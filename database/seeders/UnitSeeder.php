<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = collect([
            [
                'name' => 'Piece',
                'slug' => 'piece',
                'short_code' => 'pc',
                'user_id'=>1
            ],
            [
                'name' => 'Set',
                'slug' => 'set',
                'short_code' => 'set',
                'user_id'=>1
            ],
            [
                'name' => 'Pair',
                'slug' => 'pair',
                'short_code' => 'pr',
                'user_id'=>1
            ]
        ]);

        $units->each(function ($unit){
            Unit::insert($unit);
        });
    }
}
