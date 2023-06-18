<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic create user test.
     */
    public function test_create_user(): void
    {
        // Verificar se o usuário já existe antes de criar um novo
        $existingUser = User::where('email', 'ronaldo@gmail.com')->first();
        if ($existingUser) {
            $existingUser->delete(); // Remover o usuário existente antes de criar um novo
        }

        $response = $this->post('api/auth/new-user', [
            'name' => 'ronaldo',
            'email' => 'ronaldo@gmail.com',
            'password' => '12345',
        ]);

        $response->assertStatus(201); // Verifica o redirecionamento
    }

    /**
     * A basic login test.
     */
    public function test_login(): void
    {
        $response = $this->post('api/auth/login', [
            'email' => 'ronaldo@gmail.com',
            'password' => '12345',
        ]);

        $response->assertStatus(200); // Verifica o redirecionamento
    }
}
