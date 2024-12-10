<?php

namespace Tests\Feature;

use App\Models\Contato;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ContatoTest extends TestCase
{
    use RefreshDatabase;

    #[Test]  public function create_contato_example()
    {
        // Create a user for authentication
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password123')
        ]);

        // Act as the created user
        $this->actingAs($user);

        // Create a contato using the factory
        $contato = Contato::factory()->create([
            'user_id' => $user->id,  // Associate with the authenticated user
        ]);

        // Assert that the contato was created
        $this->assertDatabaseHas('contatos', [
            'nome' => $contato->nome,
            'cpf' => $contato->cpf,
            'telefone' => $contato->telefone,
            'user_id' => $user->id
        ]);
    }
}
