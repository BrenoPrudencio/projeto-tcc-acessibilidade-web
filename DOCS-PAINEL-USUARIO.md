# 📋 Documentação Técnica — Painel do Usuário (Candidato)

> **Projeto:** Sistema de Gestão de Vagas e Candidaturas  
> **Módulo:** Painel do Usuário (Candidato)  
> **Contexto:** Trabalho de Conclusão de Curso (TCC) — Ciência da Computação  
> **Padrão de Acessibilidade:** WCAG 2.1 — Nível AA  
> **Data:** Março/2026

---

## 1. Visão Geral

O sistema atual opera **exclusivamente como painel administrativo** — não há interface para o candidato. Este documento planeja a criação do **Painel do Usuário**, permitindo ao candidato: visualizar vagas, se candidatar, acompanhar candidaturas e configurar preferências de acessibilidade.

### Diferenciação dos Painéis

| Aspecto | Painel Admin (Existente) | Painel Usuário (Novo) |
|---------|--------------------------|----------------------|
| **Público** | Recrutador / RH | Candidato (incluindo PCD) |
| **Autenticação** | `auth` middleware | `auth` + `role:candidato` |
| **Vagas** | CRUD completo | Apenas visualizar + filtrar |
| **Candidatos** | CRUD completo | Apenas o próprio perfil |
| **Inscrições** | Gerenciar todos | Apenas as próprias |
| **Acessibilidade** | ⭐ Parcial | ⭐⭐⭐⭐⭐ WCAG 2.1 AA |

---

## 2. Análise Profunda de Acessibilidade — Problemas por Arquivo

> [!CAUTION]
> Todas as 7 views de CRUD (vagas e candidatos) são documentos HTML **standalone** com `<!DOCTYPE>` próprio, sem utilizar o layout `app.blade.php`. Perdendo `<main>`, `<nav>`, `<header>`, skip link e navegação consistente.

### 2.1 Telas de Vagas (Sem ARIA)

#### `vagas/index.blade.php`
| Linha | Problema | WCAG | Severidade |
|-------|---------|------|-----------|
| L1-9 | HTML standalone, sem layout | 1.3.1 | 🔴 Crítico |
| L23 | Ícone `fa-filter` sem `aria-hidden="true"` | 1.1.1 | 🟡 Alto |
| L64 | Checkbox `select-all` sem `aria-label` | 4.1.2 | 🔴 Crítico |
| L65-69 | `<th>` sem `scope="col"` | 1.3.1 | 🟡 Alto |
| L75 | Checkboxes de linha sem `aria-label` | 4.1.2 | 🟡 Alto |
| L85 | `confirm()` inacessível para leitor de tela | 2.1.1 | 🟡 Alto |
| L98 | Paginação sem `aria-label` nos links | 2.4.4 | 🟠 Médio |
| — | Sem skip link | 2.4.1 | 🔴 Crítico |
| — | Sem `:focus-visible` customizado | 2.4.7 | 🟡 Alto |
| — | Sem botão alto contraste | 1.4.3 | 🟠 Médio |

#### `vagas/create.blade.php`
| Linha | Problema | WCAG | Severidade |
|-------|---------|------|-----------|
| L1-8 | HTML standalone | 1.3.1 | 🔴 Crítico |
| L15-24 | Erros sem `aria-describedby` nos campos | 3.3.1 | 🔴 Crítico |
| L31 | Sem `aria-required="true"` | 3.3.2 | 🟡 Alto |
| L36 | Sem `aria-required="true"` | 3.3.2 | 🟡 Alto |
| L41 | Sem `aria-required="true"` | 3.3.2 | 🟡 Alto |
| — | Sem foco automático em campo com erro | 3.3.1 | 🟡 Alto |

#### `vagas/edit.blade.php`
| Linha | Problema | WCAG | Severidade |
|-------|---------|------|-----------|
| L1-8 | HTML standalone | 1.3.1 | 🔴 Crítico |
| L14-23 | Erros sem `aria-describedby` | 3.3.1 | 🔴 Crítico |
| L31-54 | Sem `aria-required="true"` em nenhum campo | 3.3.2 | 🟡 Alto |

#### `vagas/show.blade.php`
| Linha | Problema | WCAG | Severidade |
|-------|---------|------|-----------|
| L1-8 | HTML standalone | 1.3.1 | 🔴 Crítico |
| L44 | `text-muted` (#6c757d): contraste marginal ~4.6:1 | 1.4.3 | 🟡 Alto |
| L49 | Botão "X" sem `aria-label` (só `title`) | 4.1.2 | 🔴 Crítico |
| L49 | `confirm()` inacessível | 2.1.1 | 🟡 Alto |

### 2.2 Telas de Candidatos (ARIA Parcial — melhor estado)

#### `candidatos/index.blade.php` — ⭐⭐⭐
- ✅ `aria-label="Selecionar todos"` no checkbox (L113)
- ✅ `aria-label="Selecionar candidato X"` por linha (L130)
- ✅ `aria-live="polite"` no alert de sucesso (L32)
- ✅ Botão alto contraste (L81)
- ✅ `:focus-visible` customizado (L10)
- ❌ HTML standalone (L1-27)
- ❌ `<th>` sem `scope="col"` (L113-120)
- ❌ `confirm()` do JS inacessível (L157)

#### `candidatos/create.blade.php` — ⭐⭐⭐
- ✅ Skip link "Pular para o conteúdo" (L20)
- ✅ `aria-required="true"` em todos os campos obrigatórios
- ✅ `aria-describedby` vinculando campos a erros
- ✅ `aria-hidden="true"` no asterisco `*`
- ✅ `aria-live="assertive"` nos erros (L30)
- ✅ `<fieldset>` + `<legend>` para seção PCD (L104-148)
- ✅ Foco automático em campo com erro (L172-173)
- ✅ Máscara de telefone com iMask
- ❌ HTML standalone (L1-18)

#### `candidatos/edit.blade.php` — ⭐⭐⭐
- ✅ Skip link (L20)
- ✅ Todos os atributos ARIA implementados
- ✅ `<fieldset>` + `<legend>` para PCD (L111-161)
- ✅ Toggle PCD com habilitação/desabilitação (L179-192)
- ❌ HTML standalone (L1-17)
- ❌ Textarea `acessibilidade` desabilitado quando PCD desmarcado, mas sem indicação visual clara

### 2.3 Layouts e Componentes (Breeze)

#### `layouts/app.blade.php` — ⭐⭐⭐⭐
- ✅ `<main>` landmark (L31)
- ✅ `<header>` com slot (L22-28)
- ✅ `@include('layouts.navigation')` com `<nav>` (L19)
- ✅ `lang="{{ str_replace('_', '-', app()->getLocale()) }}"` (L2)
- ❌ Sem skip link
- ❌ Sem `<footer>`
- ❌ Sem botão de alto contraste global

#### `layouts/navigation.blade.php` — ⭐⭐⭐⭐
- ✅ `<nav>` com `x-data` para mobile toggle (L1)
- ✅ Links desktop e mobile separados
- ❌ SVG do dropdown (L39-41) sem `aria-hidden="true"`
- ❌ Hamburger SVG (L68-71) sem `aria-label`

#### `components/application-logo.blade.php` — ⭐
- ❌ SVG sem `<title>` (L1-3)
- ❌ Sem `role="img"` + `aria-label`
- ❌ Path puro sem contexto semântico

### 2.4 Página Inicial

#### `welcome.blade.php` — ⭐⭐
- ⚠️ Página padrão do Laravel (82KB!) com Tailwind CSS inline
- ✅ `<header>` e `<nav>` presentes (L23-51)
- ✅ `<main>` landmark (L53)
- ❌ SVGs decorativos sem `aria-hidden` (L123-200)
- ❌ `<title>` genérico "Laravel" (L7)
- ❌ Texto em inglês ("Let's get started")
- ❌ Links externos sem indicação de nova aba para leitores de tela

---

## 3. Arquitetura Proposta

### 3.1 Novas Rotas

```php
// Públicas
Route::get('/vagas', [Usuario\VagaController::class, 'index'])->name('usuario.vagas.index');
Route::get('/vagas/{vaga}', [Usuario\VagaController::class, 'show'])->name('usuario.vagas.show');

// Autenticadas (candidato)
Route::middleware(['auth', 'role:candidato'])->prefix('painel')->name('usuario.')->group(function () {
    Route::get('/dashboard', [Usuario\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('candidaturas', Usuario\CandidaturaController::class)->only(['index', 'store', 'destroy']);
    Route::get('/perfil', [Usuario\PerfilController::class, 'edit'])->name('perfil.edit');
    Route::put('/perfil', [Usuario\PerfilController::class, 'update'])->name('perfil.update');
});
```

### 3.2 Novos Arquivos

```
app/Http/Controllers/Usuario/         ← NOVO
├── DashboardController.php
├── VagaController.php
├── CandidaturaController.php
└── PerfilController.php

app/Http/Middleware/
└── RoleMiddleware.php                ← NOVO

resources/views/usuario/              ← NOVO
├── layouts/app.blade.php             ← Layout acessível
├── dashboard.blade.php
├── vagas/index.blade.php
├── vagas/show.blade.php
├── candidaturas/index.blade.php
└── perfil/edit.blade.php

database/migrations/
└── xxxx_add_role_to_users_table.php  ← NOVO
```

### 3.3 Alterações no Model User

```php
// Adicionar ao User.php:
protected $fillable = ['name', 'email', 'password', 'role', 'candidato_id'];

public function candidato() { return $this->belongsTo(Candidato::class); }
public function isAdmin(): bool { return $this->role === 'admin'; }
public function isCandidato(): bool { return $this->role === 'candidato'; }
```

---

## 4. Roadmap de Tasks

### Fase 0 — Correções Críticas de Acessibilidade (~12h)

| Task | Descrição | Prioridade | 
|------|-----------|-----------|
| TASK-001 | Unificar framework CSS (Bootstrap OU Tailwind) | 🔴 Crítica |
| TASK-002 | Integrar as 7 views standalone ao layout `app.blade.php` | 🔴 Crítica |
| TASK-003 | Adicionar atributos ARIA em todas as telas de Vagas | 🔴 Crítica |
| TASK-004 | Substituir `confirm()` por modal acessível com trap de foco | 🟡 Alta |
| TASK-005 | Corrigir SVG do logo (adicionar `<title>`, `role="img"`) | 🟠 Média |

### Fase 1 — Infraestrutura do Painel (~6h)

| Task | Descrição | Prioridade |
|------|-----------|-----------|
| TASK-006 | Migration: campo `role` + `candidato_id` em `users` | 🔴 Crítica |
| TASK-007 | Middleware `RoleMiddleware` | 🔴 Crítica |
| TASK-008 | Layout base acessível `usuario/layouts/app.blade.php` | 🔴 Crítica |
| TASK-009 | Definir rotas do painel com prefixo `usuario.` | 🔴 Crítica |

### Fase 2 — Telas Públicas (~8h)

| Task | Descrição | Prioridade |
|------|-----------|-----------|
| TASK-010 | Listagem pública de vagas (cards acessíveis) | 🔴 Crítica |
| TASK-011 | Detalhes da vaga + botão candidatar-se | 🔴 Crítica |

### Fase 3 — Área Autenticada (~14h)

| Task | Descrição | Prioridade |
|------|-----------|-----------|
| TASK-012 | Dashboard do candidato | 🟡 Alta |
| TASK-013 | Tela "Minhas Candidaturas" | 🟡 Alta |
| TASK-014 | Tela de perfil (dados + PCD) | 🟡 Alta |
| TASK-015 | Configurações de acessibilidade (alto contraste, fonte, animações) | 🟡 Alta |

### Fase 4 — Controllers (~8h)

| Task | Descrição | Prioridade |
|------|-----------|-----------|
| TASK-016 | `Usuario\VagaController` (index, show) | 🔴 Crítica |
| TASK-017 | `Usuario\CandidaturaController` (index, store, destroy) | 🔴 Crítica |
| TASK-018 | `Usuario\PerfilController` (edit, update) | 🟡 Alta |
| TASK-019 | `Usuario\DashboardController` | 🟡 Alta |

### Fase 5 — Fluxo de Registro (~6h)

| Task | Descrição | Prioridade |
|------|-----------|-----------|
| TASK-020 | Adaptar registro Breeze para criar `User` + `Candidato` | 🔴 Crítica |
| TASK-021 | Página de registro dedicada com campos PCD (alternativa) | 🟠 Média |

### Fase 6 — Testes (~12h)

| Task | Descrição | Prioridade |
|------|-----------|-----------|
| TASK-022 | Feature Tests para todas as funcionalidades do candidato | 🟡 Alta |
| TASK-023 | Auditoria Lighthouse/axe/WAVE (meta: ≥90 em cada tela) | 🔴 Crítica |
| TASK-024 | Testes manuais de navegação por teclado | 🟡 Alta |
| TASK-025 | Testes com leitor de tela (NVDA/VoiceOver) | 🟡 Alta |

### Fase 7 — Polimento (~7h)

| Task | Descrição | Prioridade |
|------|-----------|-----------|
| TASK-026 | Adicionar `<footer>` global com link acessibilidade | 🟠 Média |
| TASK-027 | Documentação final para inclusão no TCC | 🟡 Alta |
| TASK-028 | Revisar e corrigir contraste de cores | 🟡 Alta |

### Resumo

| Fase | Estimativa |
|------|-----------|
| Fase 0 — Correções Críticas | ~12h |
| Fase 1 — Infraestrutura | ~6h |
| Fase 2 — Telas Públicas | ~8h |
| Fase 3 — Área Autenticada | ~14h |
| Fase 4 — Controllers | ~8h |
| Fase 5 — Registro | ~6h |
| Fase 6 — Testes | ~12h |
| Fase 7 — Polimento | ~7h |
| **TOTAL** | **~73h** |

---

## 5. Checklist Global de Acessibilidade (WCAG 2.1 AA)

Aplicar em **todas** as telas do Painel do Usuário:

- [ ] `<html lang="pt-BR">`
- [ ] `<title>` descritivo e único
- [ ] Skip link "Pular para o conteúdo principal"
- [ ] Layout com `<header>`, `<nav>`, `<main>`, `<footer>`
- [ ] Hierarquia `h1 → h2 → h3` sem pular níveis
- [ ] `:focus-visible` customizado (outline: 3px solid #0d6efd)
- [ ] Contraste ≥ 4.5:1 (texto) e ≥ 3:1 (texto grande)
- [ ] Botão de alto contraste
- [ ] 100% navegável por teclado
- [ ] `<label for="">` em todos os campos
- [ ] `aria-required="true"` em obrigatórios
- [ ] `aria-describedby` para erros
- [ ] `role="alert"` ou `aria-live` em alertas
- [ ] `aria-hidden="true"` em ícones decorativos
- [ ] `scope="col"` em `<th>` de tabelas
- [ ] Sem `user-scalable=no` no viewport
- [ ] Lighthouse Accessibility ≥ 90

---

## 6. Referências

| Referência | Link |
|-----------|------|
| WCAG 2.1 | https://www.w3.org/TR/WCAG21/ |
| WAI-ARIA Practices | https://www.w3.org/WAI/ARIA/apg/ |
| eMAG (Gov.br) | https://emag.governoeletronico.gov.br/ |
| Bootstrap 5 Accessibility | https://getbootstrap.com/docs/5.3/getting-started/accessibility/ |
| Lighthouse | https://developer.chrome.com/docs/lighthouse/ |
