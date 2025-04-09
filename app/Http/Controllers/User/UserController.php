<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\FavoriteProducts;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PDO;

class UserController extends Controller
{
    public function index()
    {
        $products = Product::with('brand', 'category', 'product_images')->orderBy('id')->limit(8)->get();
        $favorites = '';
        if ($currentUser = Auth::user()) {
            $favorites = $currentUser->favorites;
        }

        $productsByCategory = Category::whereNotIn('id', [141, 262,261,242,221])
            ->with(['products.product_images'])
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'products' => $category->products->take(4),
                ];
            })
            ->toArray();


        return Inertia::render('User/Index', [
            'products' => $products,
            'productsByCategory' => $productsByCategory,
            'canLogin' => app('router')->has('login'),
            'favorites' => $favorites,
            'canRegister' => app('router')->has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }
}
