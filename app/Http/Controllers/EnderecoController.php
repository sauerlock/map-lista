<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EnderecoController extends Controller
{
    public function buscarEndereco(Request $request)
    {
        // Valida o formato do CEP
        $request->validate([
            'cep' => 'required|string|size:8',
        ]);

        // Fazendo a requisição para o serviço ViaCEP
        $cep = $request->cep;
        $response = Http::withOptions(['verify' => false])
            ->get("https://viacep.com.br/ws/$cep/json/");

        // Verifica se o retorno foi efetuado com sucesso
        if ($response->successful()) {
            return response()->json($response->json(), 200);
        }

        return response()->json(['error' => 'CEP Não Encontrado ou inválido'], 400);
    }

    public function obterCoordenadas(Request $request)
    {
        $request->validate([
            'endereco' => 'required|string',
        ]);

        $apiKey = env('GOOGLE_API_KEY');
        $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($request->endereco) . '&key=' . $apiKey;

        $response =  Http::withOptions(['verify' => false])
            ->get($url);

        if ($response->successful()) {
            $data = $response->json();
            if (isset($data['results'][0])) {
                $coordinates = $data['results'][0]['geometry']['location'];
                return response()->json($coordinates, 200);
            }

            return response()->json(['error' => 'Endereço não encontrado'], 400);
        }

        return response()->json(['error' => 'Erro na comunicação com o Google Maps'], 500);
    }
}
