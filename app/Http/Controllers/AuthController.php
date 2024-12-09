<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuário cadastrado com sucesso!',
            'token' => $token,
            'user' => $user
        ]);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user]);
    }
    public function excluirConta(Request $request)
    {
        // Obtém o usuário autenticado
        $user = Auth::user();

        // Valida a senha fornecida
        $request->validate([
            'password' => 'required|string',
        ]);

        // Verifica se a senha fornecida é válida
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Senha incorreta'], 401);
        }

        // Exclui os dados associados ao usuário (contatos)
        $user->contatos()->delete();

        // Exclui o usuário
        $user->delete();

        // Retorna uma resposta de sucesso
        return response()->json(['message' => 'Conta excluída com sucesso.'], 200);
    }

}