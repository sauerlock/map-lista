<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleGeocodingService
{
    protected $apiKey;

    public function __construct()
    {
        // Chave da API no .ENV
        $this->apiKey = env('GOOGLE_API_KEY');
    }

    public function buscarCoordenadas($address)
    {
        // Monta a URL da API do Google
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&key=' . $this->apiKey;

        // Realiza a requisição GET à API do Google
        $response = Http::withOptions(['verify' => false])->get($url);

        if ($response->successful()) {
            $data = $response->json();

            // Verifica se há resultados e retorna as coordenadas
            if (isset($data['results'][0]['geometry']['location'])) {
                return [
                    'latitude' => $data['results'][0]['geometry']['location']['lat'],
                    'longitude' => $data['results'][0]['geometry']['location']['lng'],
                ];
            }

            return null; // Caso não encontre os dados
        }

        return null; // Caso a requisição falhe
    }
}
