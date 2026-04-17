<?php

namespace Tests\Feature\Usuario;

use App\Models\Candidato;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PainelAcessoTest extends TestCase
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

    private function makeAdminUser(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    // ─── Dashboard ───────────────────────────────────────────────────────────

    public function test_candidato_acessa_dashboard(): void
    {
        $user = $this->makeCandidatoUser();

        $response = $this->actingAs($user)->get(route('usuario.dashboard'));

        $response->assertStatus(200);
    }

    public function test_visitante_e_redirecionado_ao_tentar_acessar_dashboard(): void
    {
        $response = $this->get(route('usuario.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_nao_acessa_dashboard_do_candidato(): void
    {
        $admin = $this->makeAdminUser();

        $response = $this->actingAs($admin)->get(route('usuario.dashboard'));

        $response->assertStatus(403);
    }

    // ─── Painel de candidaturas ───────────────────────────────────────────────

    public function test_candidato_acessa_suas_candidaturas(): void
    {
        $user = $this->makeCandidatoUser();

        $response = $this->actingAs($user)->get(route('usuario.candidaturas.index'));

        $response->assertStatus(200);
    }

    public function test_admin_nao_acessa_candidaturas_do_painel(): void
    {
        $admin = $this->makeAdminUser();

        $response = $this->actingAs($admin)->get(route('usuario.candidaturas.index'));

        $response->assertStatus(403);
    }

    // ─── Perfil ───────────────────────────────────────────────────────────────

    public function test_candidato_acessa_tela_de_perfil(): void
    {
        $user = $this->makeCandidatoUser();

        $response = $this->actingAs($user)->get(route('usuario.perfil.edit'));

        $response->assertStatus(200);
    }

    public function test_admin_nao_acessa_perfil_do_painel_candidato(): void
    {
        $admin = $this->makeAdminUser();

        $response = $this->actingAs($admin)->get(route('usuario.perfil.edit'));

        $response->assertStatus(403);
    }

    // ─── Redirecionamentos de role ────────────────────────────────────────────

    public function test_rota_dashboard_global_redireciona_candidato_ao_painel(): void
    {
        $user = $this->makeCandidatoUser();

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertRedirect(route('usuario.dashboard'));
    }

    public function test_rota_inicial_redireciona_candidato_autenticado_ao_painel(): void
    {
        $user = $this->makeCandidatoUser();

        $response = $this->actingAs($user)->get('/');

        $response->assertRedirect(route('usuario.dashboard'));
    }

    public function test_rota_inicial_redireciona_admin_autenticado_ao_dashboard(): void
    {
        $admin = $this->makeAdminUser();

        $response = $this->actingAs($admin)->get('/');

        $response->assertRedirect(route('dashboard'));
    }

    public function test_rota_inicial_redireciona_visitante_ao_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }
}
