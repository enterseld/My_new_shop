<?php

namespace Database\Seeders;

use App\Models\ProductDiameter;
use App\Models\ProductFitDiameter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductDiameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $xmlUrl = 'https://distarimport.distar.com.ua/import2/content_dst.xml';
        $xml = simplexml_load_file($xmlUrl);
        $uniqueDiametres = [];
        $uniqueFits = [];

        foreach ($xml->shop->offers->offer as $product) {
            // Get the values as floats
            $fitDiameter = isset($product->xpath("param[@name='Діаметр посадкового отвору, мм']")[0]) ? (string)$product->xpath("param[@name='Діаметр посадкового отвору, мм']")[0] : null;
            $diameter = isset($product->xpath("param[@name='Діаметр, мм']")[0]) ? (string)$product->xpath("param[@name='Діаметр, мм']")[0] : null;
            $drillDiameter = isset($product->xpath("param[@name='Діаметр свердла, мм']")[0]) ? (string)$product->xpath("param[@name='Діаметр свердла, мм']")[0] : null;

            // Process ProductFitDiameter
            if ($fitDiameter && !in_array($fitDiameter, $uniqueFits)) {
                $uniqueFits[] = $fitDiameter;  // Store the unique fit diameter
            }

            // Collect diameters and drillDiameters
            $diameters = array_filter([$diameter, $drillDiameter]); // Remove null values

            foreach ($diameters as $dia) {
                if (!in_array($dia, $uniqueDiametres)) {
                    $uniqueDiametres[] = $dia;  // Store unique diameters
                }
            }
        }

        // Sort arrays in ascending order
        sort($uniqueDiametres);
        sort($uniqueFits);

        // Insert ProductFitDiameters into the database
        foreach ($uniqueFits as $fit) {
            if (!ProductFitDiameter::where('number', $fit)->exists()) {
                ProductFitDiameter::create([
                    'number' => $fit,
                    'slug' => Str::slug((string)$fit)
                ]);
            }
        }

        // Insert ProductDiameters into the database
        foreach ($uniqueDiametres as $dia) {
            if (!ProductDiameter::where('number', $dia)->exists()) {
                ProductDiameter::create([
                    'number' => $dia,
                    'slug' => Str::slug((string)$dia)
                ]);
            }
        }
    }
}
