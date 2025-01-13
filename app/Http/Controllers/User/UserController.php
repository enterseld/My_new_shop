<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(){
        $products = Product::with('brand', 'category', 'product_images')->orderBy('id')->limit(8)->get();
        $allProducts = Product::with('product_images')->orderBy('id')->where('published', '!=', 0)->get();
        return Inertia::render('User/Index', [
            'products' => $products,
            'allProducts' => $allProducts,
            'canLogin' => app('router')->has('login'),
            'canRegister' => app('router')->has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }
}
