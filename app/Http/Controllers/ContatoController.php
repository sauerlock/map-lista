<?php

namespace App\Http\Controllers;

use App\Models\Contato;
use App\Services\CEPValidationService;
use App\Services\GoogleGeocodingService;
use App\Services\CPFValidationService; // Serviço para validação de CPF
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ContatoController extends Controller
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

    public function store(Request $request)
    {
        Log::info('Entrou no método store do ContatoController');

        // Validação dos dados recebidos
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string|unique:contatos,cpf',
            'telefone' => 'required|string',
            'endereco' => 'required|string',
            'cep' => 'required|string',
        ]);

        $cpf = $validated['cpf'];
        // Verifica se o CPF já está registrado para o usuário autenticado
        $existingCpf = Auth::user()->contatos()->where('cpf', $cpf)->first();
        if ($existingCpf) {
            return response()->json(['error' => 'O CPF já está cadastrado para este usuário.'], 400);
        }
        // Valida o CPF
        if (!$this->CPFValidationService->validarCpf($cpf)) {
            return response()->json(['error' => 'CPF inválido'], 400);
        }

        // Obter as coordenadas (latitude e longitude) com base no endereço
        $coordinates = $this->geocodingService->buscarCoordenadas($request->endereco);
        if (isset($coordinates['error'])) {
            return response()->json(['message' => $coordinates['error']], 400);
        }

        // Fazendo a requisição para o serviço ViaCEP
        $cep = $this->CEPValidationService->validateCep($request);
        // Verifica se o retorno foi efetuado com sucesso
        if (isset($cep['error'])) {
            return response()->json(['message' => $cep['error']], 400);
        }

        // Criação do novo contato com as coordenadas
        $contato = Auth::user()->contatos()->create(array_merge($validated, $coordinates));
        return response()->json($contato, 201);  // Retorna o contato criado
    }

    // List todos os contatos do usuário autenticado
    public function index()
    {
        $contatos = Auth::user()->contatos()->orderBy('nome')->get();
        return response()->json($contatos, 200);
    }

    // Atualiza um contato existente
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string',
            'telefone' => 'required|string',
            'endereco' => 'string',
            'cep' => 'required|string',
        ]);

        $contato = Auth::user()->contatos()->findOrFail($id);

        // Obter as coordenadas (latitude e longitude) com base no endereço
        $coordinates = $this->geocodingService->buscarCoordenadas($validated['endereco']);

        if (!$coordinates) {
            return response()->json(['error' => 'Não foi possível obter as coordenadas para o endereço'], 400);
        }

        // Atualiza o contato, incluindo dados de localização
        $contato->update([
            'nome' => $validated['nome'],
            'cpf' => $validated['cpf'],
            'telefone' => $validated['telefone'],
            'endereco' => $validated['endereco'],
            'cep' => $validated['cep'],
            'latitude' => $coordinates['latitude'],
            'longitude' => $coordinates['longitude'],
        ]);

        return response()->json($contato, 200);
    }

    // Exclui um contato
    public function destroy($id)
    {
        $contato = Auth::user()->contatos()->findOrFail($id);
        $contato->delete();

        return response()->json(['message' => 'Contato removido com sucesso!'], 200);
    }

    // Busca contatos por nome ou CPF
    public function search(Request $request)
    {
        $request->validate(['search' => 'required|string']);

        $data = $request->search;

        $contatos = Contato::where('nome', 'like', "%{$data}%")
            ->orWhere('cpf', 'like', "%{$data}%")
            ->where('user_id', auth()->id())
            ->orderBy('nome', 'asc')
            ->get();

        return response()->json($contatos, 200);
    }
}
