<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test] public function registra_usuario()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Usuário cadastrado com sucesso!',
                'token' => true,
                'user' => [
                    'name' => 'John Doe',
                    'email' => 'john@example.com',
                    'updated_at' => true,
                    'created_at' => true,
                    'id' => 1
                ],
            ]);

    }

    #[Test] public function login_usuario()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'juju@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'user' => ['id', 'name', 'email'],
            ]);
    }

    #[Test]    public function logout_usuario()
    {
        $user = \App\Models\User::factory()->create();

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->postJson('/api/logout', [], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Logout realizado com sucesso.']);
    }

    #[Test]   public function reset_password_usuario()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'john@example.com',
        ]);

        $response = $this->postJson('/api/forgot-password', [
            'email' => 'john@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['status']);
    }
}
