<?php

namespace App\Services;

use Illuminate\Support\ServiceProvider;

class LiqPayService
{
    protected string $publicKey;
    protected string $privateKey;

    public function __construct()
    {
        $this->publicKey = config('services.liqpay.public_key');
        $this->privateKey = config('services.liqpay.private_key');
    }

    public function generateCheckoutPayload(float $amount, int $order_id): array
    {
        $params = [
            'version'        => '3',
            'public_key'     => $this->publicKey,
            'action'         => 'pay',
            'amount'         => $amount,
            'currency'       => 'UAH',
            'description'    => 'Оплата замовлення №' . $order_id,
            'order_id'       => (string)$order_id,
            'server_url'     => route('liqpay.callback'),
            'result_url'     => route('payment.success'),
            'language'       => 'uk'
        ];

        $json = json_encode($params);
        $data = base64_encode($json);
        $signature = $this->generateSignature($data);

        return [
            'data' => $data,
            'signature' => $signature,
        ];
    }

    public function validateSignature(string $data, string $signature): bool
    {
        $expected = $this->generateSignature($data);
        return hash_equals($expected, $signature);
    }

    public function decodeCallbackData(string $data): array
    {
        return json_decode(base64_decode($data), true) ?? [];
    }

    private function generateSignature(string $data): string
    {
        return base64_encode(sha1($this->privateKey . $data . $this->privateKey, true));
    }
}