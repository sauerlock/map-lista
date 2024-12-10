<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class CEPValidationService
{
    public function validateCep($request)
    {
        // Valida o formato do CEP
        $request->validate([
            'cep' => 'required|string',
        ]);

        // Fazendo a requisição para o serviço ViaCEP
        $cep = $request->cep;
        $response = Http::withOptions(['verify' => false])
            ->get("https://viacep.com.br/ws/$cep/json/");

        // Verifica se o retorno foi efetuado com sucesso
        if ($response->successful()) {
            return $response->json();
        }
        return ['error' => 'CEP inválido ou não encontrado'];
        }
    }
