<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\ProductImages;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use SimpleXMLElement;

class ImportProducts extends Command
{
    protected $signature = 'import:products {xmlFile}';

    protected $description = 'Import products and pictures from XML file';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        DB::delete('delete from products;');
        DB::delete('delete from product_images;');

        $xmlUrl = 'https://distarimport.distar.com.ua/import2/content_dst.xml';
        $xml = simplexml_load_file($xmlUrl);
        $brands = [
            'DISTAR',
            'Baumesser',
            'ADTnS',
            'MECHANIC',
            'Прочая продукция'
        ];


        foreach ($xml->shop->offers->offer as $product) {
            $newProduct = new Product();
            $newProduct->title = (string)$product->name_ua;
            $newProduct->measure = (string)$product->meashure;
            $newProduct->published = 1;
            $newProduct->price = (float)$product->price;
            $newProduct->currency = (string)$product->currencyId;
            $newProduct->category_id = (string)$product->categoryId;
            $newProduct->vendor_code = (string)$product->vendorCode;
            $newProduct->brand_id = array_search((string)$product->xpath("param[@name='Торгівельна марка']")[0], $brands)+1;
            $newProduct->keywords_ua = (string)$product->keywords_ua;
            $newProduct->description = (string)$product->description_ua;
            $newProduct->disk_type = isset($product->xpath("param[@name='Вид диска']")[0]) ? (string)$product->xpath("param[@name='Вид диска']")[0] : null;
            $newProduct->work_materials = isset($product->xpath("param[@name='Робочий матеріал']")[0]) ? (string)$product->xpath("param[@name='Робочий матеріал']")[0] : null;
            $newProduct->compatibility = isset($product->xpath("param[@name='Сумісність із яким інструментом']")[0]) ? (string)$product->xpath("param[@name='Сумісність із яким інструментом']")[0] : null;
            $newProduct->mass_without_package = isset($product->xpath("param[@name='Вага без упаковки, кг']")[0]) ? (string)$product->xpath("param[@name='Вага без упаковки, кг']")[0] : null;
            $newProduct->mass_in_package = isset($product->xpath("param[@name='Вага в упаковці, кг']")[0]) ? (string)$product->xpath("param[@name='Вага в упаковці, кг']")[0] : null;
            $newProduct->height_without_package = isset($product->xpath("param[@name='Висота без упаковки, мм']")[0]) ? (string)$product->xpath("param[@name='Висота без упаковки, мм']")[0] : null;
            $newProduct->height_in_package = isset($product->xpath("param[@name='Висота в упаковці, мм']")[0]) ? (string)$product->xpath("param[@name='Висота в упаковці, мм']")[0] : null;
            //Diamond disks
            $newProduct->type_of_segments = isset($product->xpath("param[@name='Тип']")[0]) ? (string)$product->xpath("param[@name='Тип']")[0] : null;
            $newProduct->diameter_of_disk = isset($product->xpath("param[@name='Діаметр, мм']")[0]) ? (string)$product->xpath("param[@name='Діаметр, мм']")[0] : null;
            $newProduct->diameter_of_fit = isset($product->xpath("param[@name='Діаметр посадкового отвору, мм']")[0]) ? (string)$product->xpath("param[@name='Діаметр посадкового отвору, мм']")[0] : null;
            $newProduct->diamond_layer_height = isset($product->xpath("param[@name='Висота алмазного шару, мм']")[0]) ? (string)$product->xpath("param[@name='Висота алмазного шару, мм']")[0] : null;
            $newProduct->diamond_layer_width = isset($product->xpath("param[@name='Товщина алмазного шару, мм']")[0]) ? (string)$product->xpath("param[@name='Товщина алмазного шару, мм']")[0] : null;
            //Diamond drills
            $newProduct->drill_type = isset($product->xpath("param[@name='Вид свердла']")[0]) ? (string)$product->xpath("param[@name='Вид свердла']")[0] : null;
            $newProduct->drill_diameter = isset($product->xpath("param[@name='Діаметр свердла, мм']")[0]) ? (string)$product->xpath("param[@name='Діаметр свердла, мм']")[0] : null;
            $newProduct->end_type = isset($product->xpath("param[@name='Тип хвостовика']")[0]) ? (string)$product->xpath("param[@name='Тип хвостовика']")[0] : null;
            $newProduct->length = isset($product->xpath("param[@name='Довжина, мм']")[0]) ? (string)$product->xpath("param[@name='Довжина, мм']")[0] : null;
            //Mills...
            $newProduct->number_of_segments = isset($product->xpath("param[@name='Кількість сегментів, шт']")[0]) ? (int)$product->xpath("param[@name='Кількість сегментів, шт']")[0] : null;
            $newProduct->inStock = (boolean)$product['available'] === "true";
            $newProduct->quantity = 0;

            $newProduct->save();
            // Insert the product into the diamondDisks table
            foreach ($product->picture as $image) {
                // Create a new product image record with the product_id and unique name
                ProductImages::create([
                    'product_id' => $newProduct->id,
                    'image' => $image,
                ]);
            }
        }
    }
}
