<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductTest extends TestCase
{

    /**
     * A create product example test.
     */
    public function test_create_product(): void
    {
        $token = $this->getToken();
        
        $code = Str::random(10);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->json('POST', 'api/product', [
            'name' => 'Produto',
            'code' => $code,
            'value' => 10,
        ]);        
        
        $response->assertStatus(201);
    }

    /**
     * A list product example test.
     */
    public function test_list_product(): void
    {
        $token = $this->getToken();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token, // Adiciona o token ao cabeçalho da solicitação
        ])->get('api/product');

        $response->assertStatus(200);
    }

    /**
     * Simula um usuario logado.
     */
    private function getToken(): String {        
        $password = '12345';

        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);
        
        $response = $this->actingAs($user)
            ->post('api/auth/login', [
                'email' => $user->email,
                'password' => $password,
            ]);
        
        $token = $response->json('token'); // Obtém o token da resposta JSON

        return $token;
    }
}
