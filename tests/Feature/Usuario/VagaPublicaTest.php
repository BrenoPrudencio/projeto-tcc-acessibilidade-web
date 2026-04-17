<?php

namespace Tests\Feature\Usuario;

use App\Models\Vaga;
use App\Models\User;
use App\Models\Candidato;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VagaPublicaTest extends TestCase
{
    use RefreshDatabase;

    // ─── Listagem pública ───────────────────────────────────────────────────

    public function test_listagem_publica_acessivel_sem_autenticacao(): void
    {
        $response = $this->get(route('usuario.vagas.index'));
        $response->assertStatus(200);
    }

    public function test_apenas_vagas_ativas_aparecem_na_listagem(): void
    {
        Vaga::factory()->create(['titulo' => 'Vaga Ativa', 'status' => 'ativa']);
        Vaga::factory()->create(['titulo' => 'Vaga Pausada', 'status' => 'pausada']);

        $response = $this->get(route('usuario.vagas.index'));

        $response->assertSee('Vaga Ativa');
        $response->assertDontSee('Vaga Pausada');
    }

    public function test_filtro_por_titulo_funciona(): void
    {
        Vaga::factory()->create(['titulo' => 'Desenvolvedor PHP', 'status' => 'ativa']);
        Vaga::factory()->create(['titulo' => 'Designer UX', 'status' => 'ativa']);

        $response = $this->get(route('usuario.vagas.index', ['search' => 'PHP']));

        $response->assertSee('Desenvolvedor PHP');
        $response->assertDontSee('Designer UX');
    }

    public function test_filtro_por_tipo_contratacao_funciona(): void
    {
        Vaga::factory()->create(['titulo' => 'Vaga CLT', 'tipo_contratacao' => 'CLT', 'status' => 'ativa']);
        Vaga::factory()->create(['titulo' => 'Vaga PJ', 'tipo_contratacao' => 'PJ', 'status' => 'ativa']);

        $response = $this->get(route('usuario.vagas.index', ['tipo' => 'CLT']));

        $response->assertSee('Vaga CLT');
        $response->assertDontSee('Vaga PJ');
    }

    public function test_mensagem_quando_nenhuma_vaga_encontrada(): void
    {
        $response = $this->get(route('usuario.vagas.index'));
        $response->assertSee('Nenhuma vaga encontrada');
    }

    // ─── Detalhe da vaga ─────────────────────────────────────────────────────

    public function test_detalhe_de_vaga_ativa_acessivel_sem_autenticacao(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);

        $response = $this->get(route('usuario.vagas.show', $vaga));

        $response->assertStatus(200);
        $response->assertSee($vaga->titulo);
    }

    public function test_vaga_pausada_retorna_404(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'pausada']);

        $response = $this->get(route('usuario.vagas.show', $vaga));

        $response->assertStatus(404);
    }

    public function test_detalhe_exibe_botao_candidatar_para_candidato_nao_inscrito(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);
        $candidato = Candidato::factory()->create();
        $user = User::factory()->create(['role' => 'candidato', 'candidato_id' => $candidato->id]);

        $response = $this->actingAs($user)->get(route('usuario.vagas.show', $vaga));

        $response->assertSee('Enviar candidatura');
    }

    public function test_detalhe_exibe_status_inscrito_para_candidato_ja_inscrito(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);
        $candidato = Candidato::factory()->create();
        $user = User::factory()->create(['role' => 'candidato', 'candidato_id' => $candidato->id]);
        $vaga->candidatos()->attach($candidato->id);

        $response = $this->actingAs($user)->get(route('usuario.vagas.show', $vaga));

        $response->assertSee('inscrito nesta vaga');
    }

    public function test_detalhe_exibe_links_login_registro_para_visitante(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);

        $response = $this->get(route('usuario.vagas.show', $vaga));

        $response->assertSee('Entrar');
        $response->assertSee('Criar conta');
    }
}
