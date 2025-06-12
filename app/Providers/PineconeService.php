<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PineconeService
{
    protected string $apiKey;
    protected string $indexName;
    protected string $region;

    public function __construct()
    {
        $this->apiKey = env('PINECONE_API_KEY');
        $this->indexName = env('PINECONE_INDEX_NAME', 'test-chat');
        $this->region = env('PINECONE_REGION', 'us-east-1');
    }

    public function query(array $vector, int $topK = 2)
    {
        $url = "https://{$this->indexName}-{$this->region}.svc.pinecone.io/query";

        $response = Http::withHeaders([
            'Api-Key' => $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($url, [
            'vector' => $vector,
            'topK' => $topK,
            'includeMetadata' => true,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Pinecone query failed: ' . $response->body());
    }
}
