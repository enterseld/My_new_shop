<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'title' => 'Подовжувач L300 1/2GAS',
            'price' => 1690,
            'quantity' => 1,
            'category_id'=> 1,
            'brand_id'=>1,
            'description'=>'Застосовується для збільшення глибини свердління на 200 мм корончатими свердлами з посадочним містом 1/2 GAS. Подовжувач виконаний зі Сталі. Має лиски для зручного накручування та зняття ключем.'
        ]);
    }
}
