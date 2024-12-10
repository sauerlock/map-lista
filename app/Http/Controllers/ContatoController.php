<?php

namespace App\Http\Controllers;

use App\Models\Contato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ContatoController extends Controller
{
    // Chave de API do Google Maps
    protected $googleMapsApiKey;

    public function __construct()
    {
        // Defina sua chave da API do Google Maps
        $this->googleMapsApiKey = env('GOOGLE_API_KEY');
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
            'cep' => 'required|string|',
        ]);

        // Obter as coordenadas (latitude e longitude) com base no endereço
        $coordinates = $this->getCoordinatesFromAddress($validated['endereco']);

        if (!$coordinates) {
            return response()->json(['error' => 'Não foi possível obter as coordenadas para o endereço'], 400);
        }

        Log::info('Validação concluída com sucesso', $validated);

        // Criação do novo contato no banco de dados com dados de localização
        $contato = Auth::user()->contatos()->create([
            'nome' => $validated['nome'],
            'cpf' => $validated['cpf'],
            'telefone' => $validated['telefone'],
            'endereco' => $validated['endereco'],
            'cep' => $validated['cep'],
            'latitude' => $coordinates['latitude'],
            'longitude' => $coordinates['longitude'],
        ]);

        Log::info('Contato criado com sucesso', ['contato' => $contato]);

        // Retorna o contato criado com status 201 (Created)
        return response()->json($contato, 201);
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
            'nome' => 'required|string|max:255',
            'cpf' => 'required|string',
            'telefone' => 'required|string',
            'endereco' => 'string',
            'cep' => 'required|string|size:8',
        ]);

        $contato = Auth::user()->contatos()->findOrFail($id);

        // Obter as coordenadas (latitude e longitude) com base no endereço
        $coordinates = $this->getCoordinatesFromAddress($validated['endereco']);

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

    // Obtem coordenadas a partir do endereço utilizando a API do Google Maps
    private function getCoordinatesFromAddress($address)
    {
        $response = Http::withOptions([
            'verify' => false,  // Desabilita a verificação SSL
        ])->get('https://maps.googleapis.com/maps/api/geocode/json', [
            'address' => '$address',
            'key' => $this->googleMapsApiKey,
        ]);

        // Verifica se a resposta da API foi bem-sucedida
        if ($response->successful()) {
            $data = $response->json();

            // Verifica se a geolocalização foi encontrada
            if (isset($data['results'][0]['geometry']['location'])) {
                return [
                    'latitude' => $data['results'][0]['geometry']['location']['lat'],
                    'longitude' => $data['results'][0]['geometry']['location']['lng'],
                ];
            }
        }

        return null; // Se não conseguir obter as coordenadas, retorna null
    }
}
