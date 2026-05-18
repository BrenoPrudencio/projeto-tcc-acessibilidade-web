<?php

namespace Tests\Feature\Acessibilidade;

use App\Models\Candidato;
use App\Models\User;
use App\Models\Vaga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * TASK-024 — Testes de Navegação por Teclado (DOM traversal logic)
 *
 * Valida que todos os elementos interativos críticos são alcançáveis e
 * acionáveis via teclado, seguindo a ordem lógica do DOM e os padrões
 * WCAG 2.1 (SC 2.1.1 — Keyboard, SC 2.4.3 — Focus Order).
 */
class TecladoNavegacaoTest extends TestCase
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

    // ─── 1. Skip Link (WCAG 2.4.1) ───────────────────────────────────────────

    public function test_skip_link_e_o_primeiro_elemento_focavel_da_pagina(): void
    {
        $html = $this->get(route('usuario.vagas.index'))->getContent();

        // O skip-link deve aparecer antes de qualquer outro link interativo
        $posSkipLink = strpos($html, 'skip-link');
        $posNavLink  = strpos($html, '<nav');

        $this->assertNotFalse($posSkipLink, 'Skip link não encontrado na página.');
        $this->assertLessThan($posNavLink, $posSkipLink, 'Skip link deve vir antes do <nav>.');
    }

    public function test_skip_link_aponta_para_ancora_do_conteudo_principal(): void
    {
        $html = $this->get(route('usuario.vagas.index'))->getContent();

        $this->assertStringContainsString('href="#conteudo-principal"', $html);
        $this->assertStringContainsString('id="conteudo-principal"', $html);
    }

    public function test_skip_link_e_visualmente_oculto_ate_receber_foco(): void
    {
        $html = $this->get(route('usuario.vagas.index'))->getContent();

        // A classe skip-link deve ter posicionamento fora da tela por padrão
        $this->assertStringContainsString('skip-link', $html);
        $this->assertStringContainsString('top: -40px', $html);
        $this->assertStringContainsString(':focus', $html);
    }

    // ─── 2. Ordem de Foco na Listagem de Vagas (WCAG 2.4.3) ─────────────────

    public function test_links_dos_cards_de_vaga_aparecem_antes_dos_controles_de_paginacao(): void
    {
        Vaga::factory()->create(['titulo' => 'Dev Frontend', 'status' => 'ativa']);

        $html = $this->get(route('usuario.vagas.index'))->getContent();

        $posCardLink = strpos($html, 'Ver detalhes');
        $posNav      = strpos($html, 'aria-label="Paginacao de vagas"');

        // Card links devem aparecer antes da paginação no DOM
        if ($posNav !== false) {
            $this->assertLessThan($posNav, $posCardLink);
        } else {
            // Sem paginação, basta o link de detalhes existir
            $this->assertNotFalse($posCardLink);
        }
    }

    public function test_formulario_de_busca_precede_a_lista_de_vagas_no_dom(): void
    {
        Vaga::factory()->create(['titulo' => 'Dev Backend', 'status' => 'ativa']);

        $html = $this->get(route('usuario.vagas.index'))->getContent();

        $posForm = strpos($html, 'role="search"');
        $posList = strpos($html, 'role="list"');

        $this->assertNotFalse($posForm, 'Formulário de busca não encontrado.');
        $this->assertNotFalse($posList, 'Lista de vagas não encontrada.');
        $this->assertLessThan($posList, $posForm, 'Formulário deve preceder a lista no DOM.');
    }

    // ─── 3. Controles Interativos com Acessibilidade via Teclado ─────────────

    public function test_botao_de_busca_e_do_tipo_submit(): void
    {
        $html = $this->get(route('usuario.vagas.index'))->getContent();

        $this->assertStringContainsString('type="submit"', $html);
    }

    public function test_botao_de_menu_mobile_tem_atributos_de_teclado_corretos(): void
    {
        $html = $this->get(route('usuario.vagas.index'))->getContent();

        // Botão hamburger deve ter aria-label e aria-expanded para ser utilizável via teclado
        $this->assertStringContainsString('aria-label="Abrir menu de navegacao"', $html);
        $this->assertStringContainsString(':aria-expanded', $html);
    }

    public function test_botao_dark_mode_tem_aria_label_dinamico(): void
    {
        $html = $this->get(route('usuario.vagas.index'))->getContent();

        $this->assertStringContainsString('Alternar para Modo', $html);
    }

    public function test_links_de_acao_dos_cards_tem_aria_label_descritivo(): void
    {
        Vaga::factory()->create(['titulo' => 'Analista de Sistemas', 'status' => 'ativa']);

        $html = $this->get(route('usuario.vagas.index'))->getContent();

        $this->assertStringContainsString('aria-label="Ver detalhes da vaga:', $html);
    }

    // ─── 4. Formulário de Candidatura — Ordem e Foco ─────────────────────────

    public function test_formulario_candidatura_tem_campo_telefone_como_primeiro_input(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);
        $user = $this->makeCandidatoUser();

        $html = $this->actingAs($user)
            ->get(route('usuario.vagas.show', $vaga))
            ->getContent();

        $posTel    = strpos($html, 'id="telefone"');
        $posPcd    = strpos($html, 'id="pcd"');
        $posTipoD  = strpos($html, 'id="tipo_deficiencia"');

        $this->assertNotFalse($posTel, 'Campo telefone não encontrado.');
        $this->assertLessThan($posPcd, $posTel, 'Telefone deve preceder o campo PCD.');
        $this->assertLessThan($posTipoD, $posPcd, 'PCD deve preceder tipo_deficiencia.');
    }

    public function test_fieldset_pcd_agrupa_controles_relacionados(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);
        $user = $this->makeCandidatoUser();

        $html = $this->actingAs($user)
            ->get(route('usuario.vagas.show', $vaga))
            ->getContent();

        $this->assertStringContainsString('<fieldset', $html);
        $this->assertStringContainsString('<legend', $html);
        $this->assertStringContainsString('Informacoes de Acessibilidade', $html);
    }

    // ─── 5. Breadcrumb — Navegação Hierárquica (WCAG 2.4.8) ─────────────────

    public function test_detalhe_da_vaga_tem_breadcrumb_navegavel(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);

        $html = $this->get(route('usuario.vagas.show', $vaga))->getContent();

        $this->assertStringContainsString('aria-label="Breadcrumb"', $html);
        // Separadores decorativos são ocultos do teclado
        $this->assertStringContainsString('aria-hidden="true"', $html);
    }

    public function test_candidaturas_tem_breadcrumb_com_item_atual_marcado(): void
    {
        $user = $this->makeCandidatoUser();

        $html = $this->actingAs($user)
            ->get(route('usuario.candidaturas.index'))
            ->getContent();

        $this->assertStringContainsString('aria-label="Breadcrumb"', $html);
        $this->assertStringContainsString('aria-current="page"', $html);
    }

    // ─── 6. Formulário de Registro — Ordem de Campos (WCAG 1.3.2) ────────────

    public function test_registro_tem_campos_na_ordem_logica_de_tab(): void
    {
        $html = $this->get(route('register'))->getContent();

        $posName     = strpos($html, 'id="name"');
        $posEmail    = strpos($html, 'id="email"');
        $posPass     = strpos($html, 'id="password"');
        $posPassConf = strpos($html, 'id="password_confirmation"');
        $posTel      = strpos($html, 'id="telefone"');

        $this->assertLessThan($posEmail,    $posName);
        $this->assertLessThan($posPass,     $posEmail);
        $this->assertLessThan($posPassConf, $posPass);
        $this->assertLessThan($posTel,      $posPassConf);
    }

    public function test_botao_submit_de_registro_e_o_ultimo_elemento_interativo_do_form(): void
    {
        $html = $this->get(route('register'))->getContent();

        $posTel    = strpos($html, 'id="telefone"');
        $posSubmit = strpos($html, 'Criar conta');

        $this->assertGreaterThan($posTel, $posSubmit, 'Botão submit deve ser o último elemento focável do form.');
    }

    // ─── 7. Indicadores de Foco Visíveis (WCAG 2.4.7) ───────────────────────

    public function test_pagina_define_focus_outline_visivel_globalmente(): void
    {
        $html = $this->get(route('usuario.vagas.index'))->getContent();

        $this->assertStringContainsString(':focus-visible', $html);
        $this->assertStringContainsString('outline:', $html);
    }

    public function test_links_e_botoes_da_listagem_tem_classe_focus_ring(): void
    {
        Vaga::factory()->create(['status' => 'ativa']);

        $html = $this->get(route('usuario.vagas.index'))->getContent();

        $this->assertStringContainsString('focus:ring-', $html);
        $this->assertStringContainsString('focus:outline-none', $html);
    }

    public function test_acoes_do_dashboard_tem_focus_ring_aplicado(): void
    {
        $user = $this->makeCandidatoUser();

        $html = $this->actingAs($user)
            ->get(route('usuario.dashboard'))
            ->getContent();

        $this->assertStringContainsString('focus:ring-2', $html);
    }

    // ─── 8. Nenhum tabindex positivo (WCAG 2.4.3 — antipadrão) ──────────────

    public function test_pagina_de_vagas_nao_usa_tabindex_positivo(): void
    {
        $html = $this->get(route('usuario.vagas.index'))->getContent();

        // tabindex com valor positivo interfere na ordem natural de foco
        preg_match_all('/tabindex="([^"]+)"/', $html, $matches);
        $tabindexPositivos = array_filter($matches[1], fn($v) => (int)$v > 0);

        $this->assertEmpty(
            $tabindexPositivos,
            'tabindex positivo encontrado: ' . implode(', ', $tabindexPositivos) . '. Use apenas 0 ou -1.'
        );
    }

    public function test_formulario_de_candidatura_nao_usa_tabindex_positivo(): void
    {
        $vaga = Vaga::factory()->create(['status' => 'ativa']);
        $user = $this->makeCandidatoUser();

        $html = $this->actingAs($user)
            ->get(route('usuario.vagas.show', $vaga))
            ->getContent();

        preg_match_all('/tabindex="([^"]+)"/', $html, $matches);
        $tabindexPositivos = array_filter($matches[1], fn($v) => (int)$v > 0);

        $this->assertEmpty(
            $tabindexPositivos,
            'tabindex positivo encontrado: ' . implode(', ', $tabindexPositivos) . '. Use apenas 0 ou -1.'
        );
    }
}
