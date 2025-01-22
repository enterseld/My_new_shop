<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserAdress;
use Illuminate\Http\Request;

class AdressesController extends Controller
{
    public function store(Request $request)
    {  
        
        $adress = new UserAdress();
        $adress->city = $request->shipping_city;
        $adress->warehouse = $request->shipping_warehouse;
        $adress->isMain = "1";
        $adress->user_id = $request->user_id;
        $adress->save();

    return redirect()->back()->with('success', 'sent successfully');
    }
}
