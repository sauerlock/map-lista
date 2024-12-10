<?php

namespace App\Http\Controllers;

use App\Services\CEPValidationService;
use App\Services\CPFValidationService;
use App\Services\GoogleGeocodingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class EnderecoController extends Controller
{
    protected $geocodingService;
    protected $CPFValidationService;
    protected $CEPValidationService;

    public function __construct(GoogleGeocodingService $geocodingService, CPFValidationService $CPFValidationService, CEPValidationService $CEPValidationService)
    {
        $this->geocodingService = $geocodingService;
        $this->CPFValidationService = $CPFValidationService;
        $this->CEPValidationService = $CEPValidationService;
    }

    public function buscarEndereco(Request $request)
    {
        // Valida o formato do CEP
        $request->validate([
            'cep' => 'required|string',
        ]);

        // Fazendo a requisição para o serviço ViaCEP
        $cep = $this->CEPValidationService->validateCep($request);
        // Verifica se o retorno foi efetuado com sucesso
        if (isset($cep['error'])) {
            return response()->json(['message' => $cep['error']], 400);
        }
        return response()->json($cep, 200);
    }

    public function obterCoordenadas(Request $request)
    {
        $request->validate([
            'endereco' => 'required|string',
        ]);

        $coordinates = $this->geocodingService->buscarCoordenadas($request->endereco);

        if (isset($coordinates['error'])) {
            return response()->json(['message' => $coordinates['error']], 400);
        }

        return response()->json($coordinates, 200);
    }
}
