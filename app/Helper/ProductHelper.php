<?php

namespace App\Helper;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductHelper
{
    public static function setRating(Request $request, $id, $rating)
    {
        $product = Product::findOrFail($id);
        //dd($product);
        $product->rating = $rating;

        $product->update();
    }
}