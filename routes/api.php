<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContatoController;
use App\Http\Controllers\EnderecoController;
use App\Http\Controllers\ForgotPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------|
| API Routes                                                               |
|--------------------------------------------------------------------------|
|
| Here is where you can register API routes for your application. These    |
| routes are loaded by the RouteServiceProvider and all of them will       |
| be assigned to the "api" middleware group. Make something great!         |
|
*/

// Rota para solicitar o link de redefinição de senha (POST)
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink']);

// Rota para processar a redefinição de senha (POST)
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');

// Rotas de autenticação (POST)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


// Rota para obter informações do usuário autenticado
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rotas protegidas por autenticação
Route::middleware('auth:sanctum')->group(function () {
    Route::resource('contatos', ContatoController::class)->except(['show', 'edit', 'create']);
    Route::get('/search-contato', [ContatoController::class, 'search']);
});


Route::delete('/excluir-conta', [AuthController::class, 'excluirConta']);
Route::get('/buscar-endereco', [EnderecoController::class, 'buscarEndereco']);
Route::get('/obter-coordenadas', [EnderecoController::class, 'obterCoordenadas']);

