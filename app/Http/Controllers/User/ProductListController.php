<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductListController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'brand', 'product_images');
        $filterProducts = $products->filtered()->paginate(16)->withQueryString();

        $categories = Category::get();
        $brands = Brand::get();

        return Inertia::render('User/ProductList', [

            'categories' => $categories,
            'brands' => $brands,
            'products' => ProductResource::collection($filterProducts),
            'pagination' => [
                'current_page' => $filterProducts->currentPage(),
                'last_page' => $filterProducts->lastPage(),
                'per_page' => $filterProducts->perPage(),
                'total' => $filterProducts->total(),
            ],
        ]);
    }

    public function showAndIndex($id)
    {

        $product = Product::with('category', 'brand', 'product_images')->findOrFail($id);
        $products = Product::with('brand', 'category', 'product_images')->orderBy('id')->limit(4)->get();


        return Inertia::render('User/ProductInfo', [
            'product' => $product,
            'products' => $products
        ]);
    }
}
