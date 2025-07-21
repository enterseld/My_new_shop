<?php

namespace App\Http\Controllers;

use App\Services\LiqPayService;
use Illuminate\Http\Request;

class LiqPayController extends Controller
{   
    public function __construct(protected LiqPayService $liqpayService) {}

    public function getPaymentForm(Request $request){
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'order_id' => 'required|string',
        ]);

        $payload = $this->liqpayService->generateCheckoutPayload(
            $request->amount,
            $request->order_id
        );

        return response()->json($payload);
    }

    public function callback(Request $request){
        $data = $request->input('data');
        $signature = $request->input('signature');
        if(!$this->liqpayService->validateSignature($data, $signature)){
            return response("Invalid signature", 403);
        }

        $info = $this->liqpayService->decodeCallbackData($data);
        //Update order status...
        return response('OK');
    }
}
