<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Candidato;
use App\Models\User;

class CandidatosFeatureTest extends TestCase
{
    use RefreshDatabase;

    private function makeAdmin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_candidatos_index_page_can_be_rendered(): void
    {
        $response = $this->actingAs($this->makeAdmin())->get(route('candidatos.index'));

        $response->assertStatus(200);
        $response->assertSee('Lista de Candidatos');
    }

    public function test_a_created_candidato_appears_on_the_index_page(): void
    {
        $candidato = Candidato::factory()->create(['nome' => 'Candidato de Teste']);

        $response = $this->actingAs($this->makeAdmin())->get(route('candidatos.index'));

        $response->assertSee('Candidato de Teste');
    }

    public function test_a_candidato_can_be_created_via_form(): void
    {
        $candidatoData = [
            'nome' => 'João da Silva',
            'email' => 'joao.silva@example.com',
            'telefone' => '11999998888',
        ];

        $response = $this->actingAs($this->makeAdmin())->post(route('candidatos.store'), $candidatoData);

        $response->assertRedirect(route('candidatos.index'));
        $this->assertDatabaseHas('candidatos', ['email' => 'joao.silva@example.com']);
        $response->assertSessionHas('success', 'Candidato criado com sucesso!');
    }

    public function test_candidato_creation_fails_with_duplicate_email(): void
    {
        Candidato::factory()->create(['email' => 'email.existente@example.com']);

        $candidatoData = [
            'nome' => 'Outro Candidato',
            'email' => 'email.existente@example.com',
            'telefone' => '22988887777',
        ];

        $response = $this->actingAs($this->makeAdmin())->post(route('candidatos.store'), $candidatoData);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseCount('candidatos', 1);
    }
}
