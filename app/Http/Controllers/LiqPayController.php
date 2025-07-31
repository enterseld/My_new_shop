<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\LiqPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LiqPayController extends Controller
{
    public function __construct(protected LiqPayService $liqpayService) {}

    public function getPaymentForm(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'order_id' => 'required|numeric|exists:orders,id',
        ]);

        $payload = $this->liqpayService->generateCheckoutPayload(
            $request->amount,
            $request->order_id
        );

        return response()->json($payload);
    }

    public function callback(Request $request)
    {
        $data = $request->input('data');
        $signature = $request->input('signature');
        $info = $request->input('info');

        if (!$data || !$signature) {
            return response('Bad Request', 400);
        }

        if (!$this->liqpayService->validateSignature($data, $signature)) {
            return response('Invalid signature', 403);
        }

        if (!$info) {
            return response('Invalid data format', 400);
        }

        $orderId = $info['order_id'] ?? null;
        if (!$orderId) {
            return response('Missing order_id', 400);
        }

        $order = Order::where('id', $orderId)->first();
        if (!$order) {
            return response('Order not found', 404);
        }

        $result = $info['result'] ?? null;

        if ($result === 'ok') {
            $order->status = 'Paid';
            $order->save();

            return response()->json(['status' => 'success', 'message' => 'Payment processed']);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Payment not successful',
                'debug_data' => $data,
                'another_var' => $result,
            ]);
        }
    }
}
