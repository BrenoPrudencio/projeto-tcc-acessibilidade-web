# Roadmap de Tasks — Painel do Usuário (TCC)

## Fase 0 — Correções Críticas de Acessibilidade [CONCLUÍDA]
- [x] **TASK-001:** Migrar as 7 views do Bootstrap 5 para Tailwind CSS
- [x] **TASK-002:** Integrar as 7 views ao layout `<x-app-layout>` (remover HTML standalone)
- [x] **TASK-003:** Adicionar atributos ARIA faltantes nas telas de Vagas
- [x] **TASK-004:** Criar componente de Modal Acessível (Alpine.js) para substituir os `confirm()` nativos
- [x] **TASK-005:** Corrigir acessibilidade do SVG do Logo ([application-logo.blade.php](file:///home/breno/Painel-Usuario/resources/views/components/application-logo.blade.php))

## Fase 1 — Infraestrutura [CONCLUÍDA]
- [x] **TASK-006:** Migration role + candidato_id nas tabelas nativas
- [x] **TASK-007:** Criar Middleware de Role (Admin vs Candidato)
- [x] **TASK-008:** Layout base acessível para o usuário final
- [x] **TASK-009:** Criação inicial do grupo de rotas web auth/guest

## Fase 2 — Telas Públicas [CONCLUÍDA]
- [x] **TASK-010:** Listagem pública de vagas com acessibilidade via teclado
- [x] **TASK-011:** Detalhes da vaga e feedback do leitor de tela

## Fase 3 — Área Autenticada (Painel do Candidato) [CONCLUÍDA]
- [x] **TASK-012:** Dashboard do candidato
- [x] **TASK-013:** Página "Minhas Candidaturas" p/ rastrear jobs aplicados
- [x] **TASK-014:** Perfil do candidato e CRUD de anexo (Currículo)
- [x] **TASK-015:** Componente/Sidebar de configurações globais de acessibilidade

## Fase 4 — Controllers e Lógica de Negócio [CONCLUÍDA]
- [x] **TASK-016:** VagaController (Filtros baseados em status)
- [x] **TASK-017:** CandidaturaController (Aplicar/Desistir)
- [x] **TASK-018:** PerfilController (Dados cadastrais)
- [x] **TASK-019:** DashboardController (Resumo numérico)

## Fase 5 — Auto-Cadastro [CONCLUÍDA]
- [x] **TASK-020:** Adaptar view Breeze de `register` para captar dados de Candidato
- [x] **TASK-021:** Registro com validadores ARIA avançados

## Fase 6 — Testes (TCC Requisitos) [CONCLUÍDA]
- [x] **TASK-022:** Feature Tests para o fluxo principal
- [x] **TASK-023:** Auditoria Lighthouse/axe/WAVE (Meta: 0 erros críticos)
- [x] **TASK-024:** Testes práticos de navegação por teclado manual (DOM traversal logic)
- [x] **TASK-025:** Testes simulados em leitores de tela (NVDA/VoiceOver)

## Fase 7 — Polimento Final
- [ ] **TASK-026:** Footer global com links acessíveis e contraste WCAG AA
- [ ] **TASK-027:** Geração da documentação técnica final do TCC
- [ ] **TASK-028:** Revisão geral de contrastes e paletas de cor
