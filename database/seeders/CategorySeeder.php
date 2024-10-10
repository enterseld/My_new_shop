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
        Category::create([
            'name' => 'adapters_extensions',
            'slug' => 'dell'
        ]);
        Category::create([
            'name' => 'any_adjustment',
            'slug' => 'dell'
        ]);
        Category::create([
            'name' => 'diamond_disks',
            'slug' => 'dell'
        ]);
        Category::create([
            'name' => 'diamond_drills',
            'slug' => 'dell'
        ]);
        Category::create([
            'name' => 'drills_adjustment',
            'slug' => 'dell'
        ]);
        Category::create([
            'name' => 'flexible_polishing_pads',
            'slug' => 'dell'
        ]);
        Category::create([
            'name' => 'instruments_equipment',
            'slug' => 'dell'
        ]);
        Category::create([
            'name' => 'mills',
            'slug' => 'dell'
        ]);
        Category::create([
            'name' => 'polishing_instruments',
            'slug' => 'dell'
        ]);
    }
}
