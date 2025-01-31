<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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
    public function index(){
        $products = Product::with('brand', 'category', 'product_images')->orderBy('id')->limit(8)->get();
        $allProducts = Product::with('product_images')->orderBy('id')->where('published', '!=', 0)->get();
        $favorites = '';
        if($currentUser = Auth::user()){
            $favorites = $currentUser->favorites;
        }
        
        return Inertia::render('User/Index', [
            'products' => $products,
            'allProducts' => $allProducts,
            'canLogin' => app('router')->has('login'),
            'favorites' => $favorites,
            'canRegister' => app('router')->has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }
}
