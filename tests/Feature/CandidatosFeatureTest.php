<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Candidato;
use App\Models\User;

class CandidatosFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_candidatos_index_page_can_be_rendered(): void
    {
        $user = User::factory()->create(); // <-- 2. Crie um usuário

        $response = $this->actingAs($user)->get(route('candidatos.index')); // <-- 3. Use actingAs()

        $response->assertStatus(200);
        $response->assertSee('Lista de Candidatos');
    }

    public function test_a_created_candidato_appears_on_the_index_page(): void
    {
        $user = User::factory()->create();
        $candidato = Candidato::factory()->create(['nome' => 'Candidato de Teste']);

        $response = $this->actingAs($user)->get(route('candidatos.index'));

        $response->assertSee('Candidato de Teste');
    }

    public function test_a_candidato_can_be_created_via_form(): void
    {
        $user = User::factory()->create();
        $candidatoData = [
            'nome' => 'João da Silva',
            'email' => 'joao.silva@example.com',
            'telefone' => '11999998888',
        ];

        $response = $this->actingAs($user)->post(route('candidatos.store'), $candidatoData);

        $response->assertRedirect(route('candidatos.index'));
        $this->assertDatabaseHas('candidatos', ['email' => 'joao.silva@example.com']);
        $response->assertSessionHas('success', 'Candidato criado com sucesso!');
    }

    public function test_candidato_creation_fails_with_duplicate_email(): void
    {
        $user = User::factory()->create();
        Candidato::factory()->create(['email' => 'email.existente@example.com']);

        $candidatoData = [
            'nome' => 'Outro Candidato',
            'email' => 'email.existente@example.com',
            'telefone' => '22988887777',
        ];

        $response = $this->actingAs($user)->post(route('candidatos.store'), $candidatoData);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseCount('candidatos', 1);
    }
}