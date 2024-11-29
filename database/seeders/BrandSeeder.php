<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::create([
            'name' => 'Distar',
            'slug' => 'dell'
        ]);
        Brand::create([
            'name' => 'Baumesser',
            'slug' => 'dell'
        ]);
        Brand::create([
            'name' => 'Adtns',
            'slug' => 'dell'
        ]);
        Brand::create([
            'name' => 'Mechanic',
            'slug' => 'dell'
        ]);
        Brand::create([
            'name' => 'Україна',
            'slug' => 'dell'
        ]);
    }
}
