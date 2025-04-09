<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductDiameter;
use App\Models\ProductFitDiameter;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductListController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'brand', 'product_images', 'product_comments', 'product_fit_diameter', 'product_diameter');
        $filterProducts = $products->filtered()->paginate(16)->withQueryString();
        $categories = Category::get();
        $brands = Brand::get();
        $productDiameters = ProductDiameter::get();
        $productFitDiameters = ProductFitDiameter::get();
        return Inertia::render('User/Catalog', [
            'categories' => $categories,
            'brands' => $brands,
            'productDiameters' => $productDiameters,
            'productFitDiameters' => $productFitDiameters,
            'products' => ProductResource::collection($filterProducts),
            'pagination' => [
                'current_page' => $filterProducts->currentPage(),
                'last_page' => $filterProducts->lastPage(),
                'per_page' => $filterProducts->perPage(),
                'total' => $filterProducts->total(),
            ],
        ]);
    }

    public function indexByCategory($category)
    {
        $products = Product::with('category', 'brand', 'product_images', 'product_comments', 'product_fit_diameter', 'product_diameter');
        $filterProducts = $products->filtered()->paginate(16)->withQueryString();

        $categories = Category::get();
        $brands = Brand::get();
        $productDiameters = ProductDiameter::get();
        $productFitDiameters = ProductFitDiameter::get();

        return Inertia::render('User/ListForCategory', [
            'categories' => $categories,
            'brands' => $brands,
            'productDiameters' => $productDiameters,
            'productFitDiameters' => $productFitDiameters,

            'products' => ProductResource::collection($filterProducts),
            'categoryForList' => $category,
            'pagination' => [
                'current_page' => $filterProducts->currentPage(),
                'last_page' => $filterProducts->lastPage(),
                'per_page' => $filterProducts->perPage(),
                'total' => $filterProducts->total(),
            ],
        ]);
    }

    public function indexAll()
    {
        $allProducts = Product::with('product_images')->orderBy('id')->get();
        return response()->json(['allProducts' => $allProducts]);
    }

    public function showAndIndex($id)
    {
        $product = Product::with('category', 'brand', 'product_images', 'product_comments.comments_replies')->findOrFail($id);
        $products = Product::with('brand', 'category', 'product_images')->orderBy('id')->limit(4)->get();


        return Inertia::render('User/ProductInfo', [
            'product' => $product,
            'products' => $products
        ]);
    }

    
}
