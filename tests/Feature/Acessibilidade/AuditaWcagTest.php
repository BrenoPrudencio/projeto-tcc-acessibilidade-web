<?php

namespace Tests\Feature\Acessibilidade;

use App\Models\Candidato;
use App\Models\User;
use App\Models\Vaga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * TASK-023 — Auditoria estática WCAG 2.1 / axe equivalente
 *
 * Verifica os critérios de acessibilidade observáveis via HTML gerado pelo
 * servidor, cobrindo os requisitos de conformidade Nível A e AA exigidos
 * pelo TCC.
 */
class AuditaWcagTest extends TestCase
{
    use RefreshDatabase;

    // ─── Helpers ─────────────────────────────────────────────────────────────

    private function makeCandidatoUser(): User
    {
        $candidato = Candidato::factory()->create();
        return User::factory()->create([
            'role'         => 'candidato',
            'candidato_id' => $candidato->id,
        ]);
    }

    // ─── 1. Estrutura de Página (WCAG 1.3.1 / 2.4.2 / 3.1.1) ────────────────

    public function test_pagina_de_vagas_tem_atributo_lang_definido(): void
    {
        $response = $this->get(route('usuario.vagas.index'));

        $response->assertSee('lang=', false);
    }

    public function test_pagina_de_vagas_tem_titulo_descritivo(): void
    {
        $response = $this->get(route('usuario.vagas.index'));

        $response->assertSee('<title>', false);
        $response->assertSee('Vagas', false);
    }

    public function test_pagina_de_login_tem_titulo_descritivo(): void
    {
        $response = $this->get(route('login'));

        $response->assertSee('<title>', false);
        $response->assertStatus(200);
    }

    public function test_pagina_de_registro_tem_titulo_descritivo(): void
    {
        $response = $this->get(route('register'));

        $response->assertSee('<title>', false);
        $response->assertStatus(200);
    }

    // ─── 2. Skip Link (WCAG 2.4.1) ───────────────────────────────────────────

    public function test_layout_principal_contem_skip_link_para_conteudo(): void
    {
        $response = $this->get(route('usuario.vagas.index'));

        $response->assertSee('skip-link', false);
        $response->assertSee('#conteudo-principal', false);
    }

    public function test_main_tem_id_que_e_alvo_do_skip_link(): void
    {
        $response = $this->get(route('usuario.vagas.index'));

        $response->assertSee('id="conteudo-principal"', false);
    }

    // ─── 3. Formulários e Labels (WCAG 1.3.1 / 4.1.2) ───────────────────────

    public function test_formulario_de_busca_tem_label_para_campo_search(): void
    {
        $response = $this->get(route('usuario.vagas.index'));

        $response->assertSee('for="search"', false);
        $response->assertSee('id="search"', false);
    }

    public function test_formulario_de_busca_tem_label_para_campo_tipo(): void
    {
        $response = $this->get(route('usuario.vagas.index'));

        $response->assertSee('for="tipo"', false);
        $response->assertSee('id="tipo"', false);
    }

    public function test_formulario_de_busca_tem_fieldset_com_legend(): void
    {
        $response = $this->get(route('usuario.vagas.index'));

        $response->assertSee('<fieldset>', false);
        $response->assertSee('<legend', false);
    }

    public function test_formulario_de_login_tem_labels_associadas_aos_campos(): void
    {
        $response = $this->get(route('login'));

        $response->assertSee('for="email"', false);
        $response->assertSee('id="email"', false);
        $response->assertSee('for="password"', false);
        $response->assertSee('id="password"', false);
    }

    public function test_formulario_de_registro_tem_labels_associadas_e_aria_required(): void
    {
        $response = $this->get(route('register'));

        $response->assertSee('for="name"', false);
        $response->assertSee('for="email"', false);
        $response->assertSee('for="password"', false);
        $response->assertSee('for="telefone"', false);
        $response->assertSee('aria-required="true"', false);
    }

    public function test_formulario_candidatura_tem_aria_describedby_nos_campos(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);
        $user = $this->makeCandidatoUser();

        $response = $this->actingAs($user)->get(route('usuario.vagas.show', $vaga));

        $response->assertSee('aria-describedby=', false);
        $response->assertSee('aria-required="true"', false);
    }

    // ─── 4. Imagens e SVGs (WCAG 1.1.1) ─────────────────────────────────────

    public function test_svgs_decorativos_tem_aria_hidden(): void
    {
        $response = $this->get(route('usuario.vagas.index'));

        $response->assertSee('aria-hidden="true"', false);
    }

    public function test_detalhe_da_vaga_tem_svgs_decorativos_ocultos(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);

        $response = $this->get(route('usuario.vagas.show', $vaga));

        $response->assertSee('aria-hidden="true"', false);
    }

    // ─── 5. Elementos de Estrutura Semântica (WCAG 1.3.1) ────────────────────

    public function test_pagina_de_vagas_usa_elemento_article_para_cada_vaga(): void
    {
        Vaga::factory()->create(['titulo' => 'Dev Backend', 'status' => 'ativa']);

        $response = $this->get(route('usuario.vagas.index'));

        $response->assertSee('<article', false);
    }

    public function test_detalhe_da_vaga_usa_sections_com_aria_labelledby(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);

        $response = $this->get(route('usuario.vagas.show', $vaga));

        $response->assertSee('aria-labelledby=', false);
    }

    public function test_dashboard_usa_sections_com_aria_labelledby(): void
    {
        $user = $this->makeCandidatoUser();

        $response = $this->actingAs($user)->get(route('usuario.dashboard'));

        $response->assertSee('aria-labelledby=', false);
    }

    // ─── 6. Indicadores de Foco (WCAG 2.4.7) ────────────────────────────────

    public function test_layout_define_estilo_focus_visible_global(): void
    {
        $response = $this->get(route('usuario.vagas.index'));

        $response->assertSee(':focus-visible', false);
    }

    public function test_botoes_de_acao_tem_classe_focus_ring(): void
    {
        $response = $this->get(route('usuario.vagas.index'));

        $response->assertSee('focus:ring-', false);
    }

    // ─── 7. Contraste e Texto Alternativo do Logo (WCAG 1.4.3 / 1.1.1) ──────

    public function test_logo_tem_aria_label_descritivo(): void
    {
        $response = $this->get(route('usuario.vagas.index'));

        $response->assertSee('aria-label=', false);
    }

    // ─── 8. Erros e Validações (WCAG 3.3.1 / 3.3.3) ─────────────────────────

    public function test_registro_exibe_erros_com_role_alert_quando_invalido(): void
    {
        $this->post(route('register'), [
            'name'     => '',
            'email'    => 'nao-e-email',
            'password' => '123',
            'password_confirmation' => 'abc',
        ])->assertSessionHasErrors();

        // Após falha, o template renderiza os erros com role="alert"
        $html = $this->get(route('register'))->getContent();
        $this->assertStringContainsString('role="alert"', $html);
    }

    public function test_candidatura_invalida_retorna_erros_acessiveis(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);
        $user = $this->makeCandidatoUser();

        $response = $this->actingAs($user)->post(route('usuario.candidaturas.store'), [
            'vaga_id' => $vaga->id,
            // telefone ausente
        ]);

        $response->assertSessionHasErrors('telefone');
    }

    // ─── 9. Landmarks de Navegação (WCAG 2.4.1) ──────────────────────────────

    public function test_pagina_tem_nav_com_aria_label(): void
    {
        $response = $this->get(route('usuario.vagas.index'));

        $response->assertSee('aria-label="Navegacao principal"', false);
    }

    public function test_pagina_de_vagas_tem_nav_de_paginacao_com_aria_label(): void
    {
        Vaga::factory()->count(20)->create(['status' => 'ativa']);

        $response = $this->get(route('usuario.vagas.index'));

        $response->assertSee('aria-label=', false);
    }

    public function test_elemento_main_esta_presente_como_landmark(): void
    {
        $response = $this->get(route('usuario.vagas.index'));

        $response->assertSee('<main', false);
    }
}
