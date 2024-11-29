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
            'id' => "101",
            'name' => 'Пристосування для свердлів',
            'slug' => 'dell'
        ]);
        Category::create([
            'id' => "102",
            'name' => 'Пристосування',
            'slug' => 'dell'
        ]);
        Category::create([
            'id' => "41",
            'name' => 'Алмазні фрези',
            'slug' => 'dell'
        ]);
        Category::create([
            'id' => "21",
            'name' => 'Алмазні свердла',
            'slug' => 'dell'
        ]);
        Category::create([
            'id' => "2",
            'name' => 'Алмазні диски',
            'slug' => 'dell'
        ]);
        Category::create([
            'id' => "81",
            'name' => 'Гнучкі полірувальні круги',
            'slug' => 'dell'
        ]);
        Category::create([
            'id' => "121",
            'name' => 'Перехідники',
            'slug' => 'dell'
        ]);
        Category::create([
            'id' => "181",
            'name' => 'Пластикові аксесуари',
            'slug' => 'dell'
        ]);
        Category::create([
            'id' => "241",
            'name' => 'Кожух накладний на КШМ',
            'slug' => 'dell'
        ]);
        Category::create([
            'id' => "141",
            'name' => 'Аксесуари',
            'slug' => 'dell'
        ]);
        Category::create([
            'id' => "242",
            'name' => 'Обладнання',
            'slug' => 'dell'
        ]);
        Category::create([
            'id' => "142",
            'name' => 'Полірувальний інструмент',
            'slug' => 'dell'
        ]);
        Category::create([
            'id' => "143",
            'name' => 'Інструменти та обладнання',
            'slug' => 'dell'
        ]);
        Category::create([
            'id' => "221",
            'name' => 'Подовжувачі',
            'slug' => 'dell'
        ]);
        Category::create([
            'id' => "262",
            'name' => 'Інструменти для розмітки',
            'slug' => 'dell'
        ]);
        Category::create([
            'id' => "261",
            'name' => 'Брусок абразивний',
            'slug' => 'dell'
        ]);
        
    }
}
