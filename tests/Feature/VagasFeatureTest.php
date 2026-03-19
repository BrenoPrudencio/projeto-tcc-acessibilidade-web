<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Vaga;
use App\Models\User;

class VagasFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_vagas_index_page_can_be_rendered(): void
    {
        $user = User::factory()->create(); // <-- 2. Crie um usuário

        $response = $this->actingAs($user)->get(route('vagas.index')); // <-- 3. Use actingAs()

        $response->assertStatus(200);
        $response->assertSee('Lista de Vagas');
    }

    public function test_a_created_vaga_appears_on_the_index_page(): void
    {
        $user = User::factory()->create();
        $vaga = Vaga::factory()->create(['titulo' => 'Vaga de Teste Específica']);

        $response = $this->actingAs($user)->get(route('vagas.index'));

        $response->assertSee('Vaga de Teste Específica');
    }

    public function test_a_vaga_can_be_created_via_form(): void
    {
        $user = User::factory()->create();
        $vagaData = [
            'titulo' => 'Nova Vaga de Teste de Criação',
            'descricao' => 'Descrição completa da vaga de teste.',
            'tipo_contratacao' => 'CLT',
        ];

        $response = $this->actingAs($user)->post(route('vagas.store'), $vagaData);

        $response->assertRedirect(route('vagas.index'));
        $this->assertDatabaseHas('vagas', ['titulo' => 'Nova Vaga de Teste de Criação']);
        $response->assertSessionHas('success', 'Vaga criada com sucesso!');
    }
    
    public function test_vaga_creation_fails_with_invalid_data(): void
    {
        $user = User::factory()->create();
        $vagaData = [
            'titulo' => '',
            'descricao' => 'Descrição válida para o teste de falha.',
            'tipo_contratacao' => 'PJ',
        ];

        $response = $this->actingAs($user)->post(route('vagas.store'), $vagaData);

        $response->assertSessionHasErrors('titulo');
        $this->assertDatabaseMissing('vagas', ['descricao' => 'Descrição válida para o teste de falha.']);
    }
}