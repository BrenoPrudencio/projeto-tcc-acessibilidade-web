<?php

namespace Tests\Feature\Acessibilidade;

use App\Models\Candidato;
use App\Models\User;
use App\Models\Vaga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * TASK-025 — Testes Simulados de Leitores de Tela (NVDA / VoiceOver)
 *
 * Verifica que o HTML gerado pelo servidor inclui os atributos ARIA e os
 * padrões semânticos que garantem uma experiência compreensível para
 * usuários de tecnologias assistivas, conforme WCAG 2.1 SC 4.1.2 e 1.3.1.
 */
class LeitorDeTelasTest extends TestCase
{
    use RefreshDatabase;

    private function makeCandidatoUser(): User
    {
        $candidato = Candidato::factory()->create();
        return User::factory()->create([
            'role'         => 'candidato',
            'candidato_id' => $candidato->id,
        ]);
    }

    // ─── 1. Live Regions — Anúncio Dinâmico de Conteúdo ─────────────────────

    public function test_listagem_de_vagas_anuncia_total_de_resultados_via_aria_live(): void
    {
        $html = $this->get(route('usuario.vagas.index'))->getContent();

        $this->assertStringContainsString('aria-live="polite"', $html);
    }

    public function test_detalhe_da_vaga_usa_aria_live_assertive_para_alertas_de_erro(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);
        $user = $this->makeCandidatoUser();

        // Provoca erros de validação na candidatura → redirect back com errors na sessão
        $this->actingAs($user)
            ->from(route('usuario.vagas.show', $vaga))
            ->post(route('usuario.candidaturas.store'), ['vaga_id' => $vaga->id]);

        // Na próxima requisição a sessão flash mantém os erros — o template renderiza aria-live
        $html = $this->actingAs($user)
            ->get(route('usuario.vagas.show', $vaga))
            ->getContent();

        $this->assertStringContainsString('aria-live="assertive"', $html);
    }

    public function test_pagina_de_candidaturas_usa_aria_live_polite_para_contador(): void
    {
        $user = $this->makeCandidatoUser();

        $html = $this->actingAs($user)
            ->get(route('usuario.candidaturas.index'))
            ->getContent();

        $this->assertStringContainsString('aria-live="polite"', $html);
    }

    public function test_cancelamento_de_candidatura_usa_aria_live_assertive(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);
        $user = $this->makeCandidatoUser();
        $vaga->candidatos()->attach($user->candidato_id);

        $html = $this->actingAs($user)
            ->get(route('usuario.candidaturas.index'))
            ->getContent();

        $this->assertStringContainsString('aria-live="assertive"', $html);
    }

    // ─── 2. role="alert" para Mensagens de Feedback ──────────────────────────

    public function test_formulario_de_registro_exibe_erros_com_role_alert(): void
    {
        // Provoca erros de validação → flash na sessão
        $this->post(route('register'), [
            'name'                  => '',
            'email'                 => 'nao-e-email',
            'password'              => '123',
            'password_confirmation' => 'abc',
            'telefone'              => '',
        ]);

        // A sessão flash mantém os erros; o template renderiza role="alert"
        $html = $this->get(route('register'))->getContent();
        $this->assertStringContainsString('role="alert"', $html);
    }

    public function test_detalhe_da_vaga_tem_regiao_com_role_alert_para_erros(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);
        $user = $this->makeCandidatoUser();

        // Provoca erros de validação na candidatura
        $this->actingAs($user)
            ->from(route('usuario.vagas.show', $vaga))
            ->post(route('usuario.candidaturas.store'), ['vaga_id' => $vaga->id]);

        $html = $this->actingAs($user)
            ->get(route('usuario.vagas.show', $vaga))
            ->getContent();

        $this->assertStringContainsString('role="alert"', $html);
    }

    // ─── 3. Textos Apenas para Leitores de Tela (sr-only) ────────────────────

    public function test_secao_de_resumo_do_dashboard_tem_titulo_sr_only(): void
    {
        $user = $this->makeCandidatoUser();

        $html = $this->actingAs($user)
            ->get(route('usuario.dashboard'))
            ->getContent();

        $this->assertStringContainsString('class="sr-only"', $html);
    }

    public function test_asterisco_de_campo_obrigatorio_tem_sr_only_como_alternativa_textual(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);
        $user = $this->makeCandidatoUser();

        $html = $this->actingAs($user)
            ->get(route('usuario.vagas.show', $vaga))
            ->getContent();

        // O asterisco (*) é aria-hidden; o texto equivalente fica em sr-only
        $this->assertStringContainsString('class="sr-only"', $html);
        $this->assertStringContainsString('aria-hidden="true"', $html);
    }

    // ─── 4. aria-label em Elementos sem Texto Visível ────────────────────────

    public function test_logo_do_site_tem_aria_label_descritivo(): void
    {
        $html = $this->get(route('usuario.vagas.index'))->getContent();

        $this->assertStringContainsString('aria-label=', $html);
        $this->assertStringContainsString('Pagina inicial', $html);
    }

    public function test_botao_dark_mode_tem_aria_label(): void
    {
        $html = $this->get(route('usuario.vagas.index'))->getContent();

        $this->assertStringContainsString('Alternar para Modo', $html);
    }

    public function test_cards_de_vaga_tem_aria_label_incluindo_titulo_da_vaga(): void
    {
        Vaga::factory()->create(['titulo' => 'Analista de Dados PcD', 'status' => 'ativa']);

        $html = $this->get(route('usuario.vagas.index'))->getContent();

        $this->assertStringContainsString('aria-label="Ver detalhes da vaga: Analista de Dados PcD"', $html);
    }

    public function test_botao_cancelar_candidatura_tem_aria_label_com_titulo_da_vaga(): void
    {
        $vaga = Vaga::factory()->create(['titulo' => 'Dev Sênior', 'status' => 'ativa']);
        $user = $this->makeCandidatoUser();
        $vaga->candidatos()->attach($user->candidato_id);

        $html = $this->actingAs($user)
            ->get(route('usuario.candidaturas.index'))
            ->getContent();

        $this->assertStringContainsString('aria-label="Cancelar candidatura para Dev Sênior"', $html);
    }

    // ─── 5. aria-expanded em Componentes Expansíveis ─────────────────────────

    public function test_botao_de_menu_mobile_expoe_aria_expanded(): void
    {
        $html = $this->get(route('usuario.vagas.index'))->getContent();

        $this->assertStringContainsString(':aria-expanded', $html);
    }

    public function test_botao_cancelar_candidatura_expoe_aria_expanded(): void
    {
        $vaga = Vaga::factory()->create(['titulo' => 'Cargo A', 'status' => 'ativa']);
        $user = $this->makeCandidatoUser();
        $vaga->candidatos()->attach($user->candidato_id);

        $html = $this->actingAs($user)
            ->get(route('usuario.candidaturas.index'))
            ->getContent();

        $this->assertStringContainsString(':aria-expanded', $html);
    }

    // ─── 6. aria-current para Localização do Usuário (WCAG 2.4.8) ───────────

    public function test_breadcrumb_do_detalhe_da_vaga_marca_item_atual_com_aria_current(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);

        $html = $this->get(route('usuario.vagas.show', $vaga))->getContent();

        $this->assertStringContainsString('aria-current="page"', $html);
    }

    public function test_breadcrumb_das_candidaturas_marca_item_atual(): void
    {
        $user = $this->makeCandidatoUser();

        $html = $this->actingAs($user)
            ->get(route('usuario.candidaturas.index'))
            ->getContent();

        $this->assertStringContainsString('aria-current="page"', $html);
    }

    // ─── 7. Listas Semânticas com role (WCAG 1.3.1) ──────────────────────────

    public function test_listagem_de_vagas_usa_role_list_e_listitem(): void
    {
        Vaga::factory()->create(['status' => 'ativa']);

        $html = $this->get(route('usuario.vagas.index'))->getContent();

        $this->assertStringContainsString('role="list"', $html);
        $this->assertStringContainsString('role="listitem"', $html);
    }

    public function test_painel_de_candidaturas_usa_role_list(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);
        $user = $this->makeCandidatoUser();
        $vaga->candidatos()->attach($user->candidato_id);

        $html = $this->actingAs($user)
            ->get(route('usuario.candidaturas.index'))
            ->getContent();

        $this->assertStringContainsString('role="list"', $html);
        $this->assertStringContainsString('role="listitem"', $html);
    }

    public function test_lista_de_acoes_do_dashboard_usa_role_list(): void
    {
        $user = $this->makeCandidatoUser();

        $html = $this->actingAs($user)
            ->get(route('usuario.dashboard'))
            ->getContent();

        $this->assertStringContainsString('role="list"', $html);
    }

    // ─── 8. aria-required nos Formulários ────────────────────────────────────

    public function test_formulario_de_registro_marca_campos_obrigatorios_com_aria_required(): void
    {
        $html = $this->get(route('register'))->getContent();

        $this->assertStringContainsString('aria-required="true"', $html);
    }

    public function test_formulario_de_candidatura_marca_telefone_como_aria_required(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);
        $user = $this->makeCandidatoUser();

        $html = $this->actingAs($user)
            ->get(route('usuario.vagas.show', $vaga))
            ->getContent();

        $this->assertStringContainsString('aria-required="true"', $html);
    }

    // ─── 9. Tempo e Datas Legíveis por Máquina (WCAG 1.3.1) ─────────────────

    public function test_cards_de_vaga_usam_elemento_time_com_datetime(): void
    {
        Vaga::factory()->create(['status' => 'ativa']);

        $html = $this->get(route('usuario.vagas.index'))->getContent();

        $this->assertStringContainsString('<time datetime=', $html);
    }

    public function test_lista_de_candidaturas_usa_elemento_time_para_datas(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);
        $user = $this->makeCandidatoUser();
        $vaga->candidatos()->attach($user->candidato_id);

        $html = $this->actingAs($user)
            ->get(route('usuario.candidaturas.index'))
            ->getContent();

        $this->assertStringContainsString('<time datetime=', $html);
    }

    // ─── 10. Estado "Inscrito" Acessível (WCAG 4.1.3) ────────────────────────

    public function test_detalhe_da_vaga_usa_role_status_para_confirmacao_de_inscricao(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);
        $candidato = Candidato::factory()->create();
        $user = User::factory()->create([
            'role'         => 'candidato',
            'candidato_id' => $candidato->id,
        ]);
        $vaga->candidatos()->attach($candidato->id);

        $html = $this->actingAs($user)
            ->get(route('usuario.vagas.show', $vaga))
            ->getContent();

        $this->assertStringContainsString('role="status"', $html);
        $this->assertStringContainsString('inscrito nesta vaga', $html);
    }

    public function test_dashboard_usa_role_status_para_estado_vazio_de_candidaturas(): void
    {
        $user = $this->makeCandidatoUser();

        $html = $this->actingAs($user)
            ->get(route('usuario.dashboard'))
            ->getContent();

        $this->assertStringContainsString('role="status"', $html);
    }

    // ─── 11. VLibras — Suporte a Língua de Sinais ────────────────────────────

    public function test_layout_carrega_widget_vlibras_para_lingua_de_sinais(): void
    {
        $html = $this->get(route('usuario.vagas.index'))->getContent();

        $this->assertStringContainsString('vlibras', $html);
        $this->assertStringContainsString('vw-access-button', $html);
    }

    // ─── 12. Formulário com novalidate e Validação Server-Side ───────────────

    public function test_formulario_de_registro_usa_novalidate_com_validacao_servidor(): void
    {
        $html = $this->get(route('register'))->getContent();

        // novalidate delega a validação ao servidor, que retorna mensagens acessíveis
        $this->assertStringContainsString('novalidate', $html);
    }

    public function test_formulario_de_candidatura_usa_novalidate(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);
        $user = $this->makeCandidatoUser();

        $html = $this->actingAs($user)
            ->get(route('usuario.vagas.show', $vaga))
            ->getContent();

        $this->assertStringContainsString('novalidate', $html);
    }
}
