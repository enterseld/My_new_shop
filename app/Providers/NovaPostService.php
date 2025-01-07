<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use stdClass;

class NovaPostService
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('novaposhta.api_key');
        $this->apiUrl = config('novaposhta.api_url');
    }

    public function makeRequest(string $modelName, string $calledMethod, string $findBy)
    {
        $methodProperties = new stdClass();
        $methodProperties->Limit = "5";
        $methodProperties->FindByString = $findBy;

        
    
        $response = Http::withOptions(['verify' => false])->post($this->apiUrl, [
            'apiKey' => $this->apiKey,
            'modelName' => $modelName,
            'calledMethod' => $calledMethod,
            'methodProperties' => (array)$methodProperties,
        ]);

        return $response->json();
    }
}
