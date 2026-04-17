<?php

namespace Tests\Feature\Usuario;

use App\Models\Candidato;
use App\Models\User;
use App\Models\Vaga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CandidaturaTest extends TestCase
{
    use RefreshDatabase;

    private function makeCandidatoUser(): User
    {
        $candidato = Candidato::factory()->create();
        return User::factory()->create([
            'role' => 'candidato',
            'candidato_id' => $candidato->id,
        ]);
    }

    // ─── store ───────────────────────────────────────────────────────────────

    public function test_candidato_pode_se_candidatar_a_vaga_ativa(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);
        $user = $this->makeCandidatoUser();

        $response = $this->actingAs($user)->post(route('usuario.candidaturas.store'), [
            'vaga_id' => $vaga->id,
            'telefone' => '(11) 99999-1234',
        ]);

        $response->assertRedirect(route('usuario.vagas.show', $vaga));
        $this->assertDatabaseHas('candidaturas', [
            'vaga_id' => $vaga->id,
            'candidato_id' => $user->candidato_id,
        ]);
    }

    public function test_candidatura_cria_perfil_candidato_automaticamente_se_ausente(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);
        $user = User::factory()->create(['role' => 'candidato', 'candidato_id' => null]);

        $this->actingAs($user)->post(route('usuario.candidaturas.store'), [
            'vaga_id' => $vaga->id,
            'telefone' => '(11) 98888-0000',
        ]);

        $user->refresh();
        $this->assertNotNull($user->candidato_id);
        $this->assertDatabaseHas('candidaturas', [
            'vaga_id' => $vaga->id,
            'candidato_id' => $user->candidato_id,
        ]);
    }

    public function test_candidatura_atualiza_perfil_com_dados_pcd(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);
        $user = $this->makeCandidatoUser();

        $this->actingAs($user)->post(route('usuario.candidaturas.store'), [
            'vaga_id' => $vaga->id,
            'telefone' => '(21) 97777-5555',
            'pcd' => '1',
            'tipo_deficiencia' => 'Visual',
            'acessibilidade' => 'Preciso de leitor de tela',
        ]);

        $this->assertDatabaseHas('candidatos', [
            'id' => $user->candidato_id,
            'pcd' => true,
            'tipo_deficiencia' => 'Visual',
            'acessibilidade' => 'Preciso de leitor de tela',
        ]);
    }

    public function test_candidatura_sem_pcd_nao_salva_tipo_deficiencia(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);
        $user = $this->makeCandidatoUser();

        $this->actingAs($user)->post(route('usuario.candidaturas.store'), [
            'vaga_id' => $vaga->id,
            'telefone' => '(31) 96666-4444',
            'pcd' => '0',
            'tipo_deficiencia' => 'Motorist',
        ]);

        $this->assertDatabaseHas('candidatos', [
            'id' => $user->candidato_id,
            'pcd' => false,
            'tipo_deficiencia' => null,
        ]);
    }

    public function test_candidatura_duplicada_e_rejeitada(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);
        $user = $this->makeCandidatoUser();
        $vaga->candidatos()->attach($user->candidato_id);

        $response = $this->actingAs($user)->post(route('usuario.candidaturas.store'), [
            'vaga_id' => $vaga->id,
            'telefone' => '(11) 91111-2222',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('candidaturas', 1);
    }

    public function test_candidatura_a_vaga_pausada_e_rejeitada(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'pausada']);
        $user = $this->makeCandidatoUser();

        $response = $this->actingAs($user)->post(route('usuario.candidaturas.store'), [
            'vaga_id' => $vaga->id,
            'telefone' => '(11) 91111-3333',
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('candidaturas', ['vaga_id' => $vaga->id]);
    }

    public function test_telefone_e_obrigatorio_na_candidatura(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);
        $user = $this->makeCandidatoUser();

        $response = $this->actingAs($user)->post(route('usuario.candidaturas.store'), [
            'vaga_id' => $vaga->id,
        ]);

        $response->assertSessionHasErrors('telefone');
        $this->assertDatabaseMissing('candidaturas', ['vaga_id' => $vaga->id]);
    }

    public function test_nao_autenticado_nao_pode_se_candidatar(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);

        $response = $this->post(route('usuario.candidaturas.store'), [
            'vaga_id' => $vaga->id,
            'telefone' => '(11) 99999-9999',
        ]);

        $response->assertRedirect(route('login'));
        $this->assertDatabaseCount('candidaturas', 0);
    }

    // ─── destroy ─────────────────────────────────────────────────────────────

    public function test_candidato_pode_cancelar_propria_candidatura(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);
        $user = $this->makeCandidatoUser();
        $vaga->candidatos()->attach($user->candidato_id);

        $candidaturaId = \DB::table('candidaturas')
            ->where('vaga_id', $vaga->id)
            ->where('candidato_id', $user->candidato_id)
            ->value('id');

        $response = $this->actingAs($user)->delete(route('usuario.candidaturas.destroy', $candidaturaId));

        $response->assertRedirect();
        $this->assertDatabaseMissing('candidaturas', [
            'vaga_id' => $vaga->id,
            'candidato_id' => $user->candidato_id,
        ]);
    }

    public function test_candidato_nao_pode_cancelar_candidatura_de_outro(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);

        $outro = Candidato::factory()->create();
        $vaga->candidatos()->attach($outro->id);

        $candidaturaId = \DB::table('candidaturas')
            ->where('vaga_id', $vaga->id)
            ->where('candidato_id', $outro->id)
            ->value('id');

        $user = $this->makeCandidatoUser();

        $response = $this->actingAs($user)->delete(route('usuario.candidaturas.destroy', $candidaturaId));

        $response->assertStatus(404);
        $this->assertDatabaseHas('candidaturas', ['id' => $candidaturaId]);
    }

    // ─── index ───────────────────────────────────────────────────────────────

    public function test_candidato_ve_proprias_candidaturas(): void
    {
        $vaga = Vaga::factory()->create(['titulo' => 'Analista de Dados', 'status' => 'ativa']);
        $user = $this->makeCandidatoUser();
        $vaga->candidatos()->attach($user->candidato_id);

        $response = $this->actingAs($user)->get(route('usuario.candidaturas.index'));

        $response->assertStatus(200);
        $response->assertSee('Analista de Dados');
    }

    public function test_candidato_nao_ve_candidaturas_de_outros(): void
    {
        $vaga = Vaga::factory()->create(['titulo' => 'Vaga Alheia', 'status' => 'ativa']);
        $outro = Candidato::factory()->create();
        $vaga->candidatos()->attach($outro->id);

        $user = $this->makeCandidatoUser();

        $response = $this->actingAs($user)->get(route('usuario.candidaturas.index'));

        $response->assertStatus(200);
        $response->assertDontSee('Vaga Alheia');
    }
}
