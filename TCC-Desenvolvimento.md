# 4 DESENVOLVIMENTO

Este capítulo apresenta o processo de construção do protótipo funcional do sistema web de gerenciamento de vagas de emprego com foco em acessibilidade digital, detalhando as decisões metodológicas, arquiteturais e técnicas adotadas ao longo da implementação. A exposição segue uma ordem lógica que parte da organização das atividades, avança pela definição da arquitetura da aplicação e pelas escolhas de engenharia de software, descreve a incorporação das diretrizes da *Web Content Accessibility Guidelines* (WCAG) 2.1 ao artefato desenvolvido e, por fim, discute as estratégias de validação técnica e heurística aplicadas. A abordagem metodológica caracteriza-se como pesquisa aplicada, de natureza qualitativa e finalidade exploratória, cujo produto central é o protótipo apresentado neste trabalho.

## 4.1 Organização e Planejamento das Atividades

Na etapa inicial do desenvolvimento, optou-se pela utilização da metodologia ágil Kanban como instrumento de organização das atividades do projeto. O Kanban estrutura o fluxo de trabalho em colunas que representam os estados de execução das tarefas, promovendo transparência, limitação do trabalho em progresso e melhoria contínua. Para os propósitos deste trabalho, adotou-se a configuração simplificada composta por três colunas: *To Do* (a fazer), *Doing* (em execução) e *Done* (concluído).

A escolha dessa metodologia na fase preliminar justifica-se pela necessidade de decompor o escopo do trabalho em unidades menores e rastreáveis, bem como pela possibilidade de priorizar demandas de forma visual e incremental, sem a sobrecarga cerimonial de métodos ágeis mais prescritivos. Após a consolidação do *backlog* inicial — desdobrado em requisitos funcionais e não funcionais relativos à autenticação, ao cadastro de candidatos, ao gerenciamento de vagas e à conformidade com critérios de acessibilidade — o Kanban cumpriu o papel de balizar a sequência das entregas iniciais.

Cabe destacar, entretanto, que o Kanban não foi adotado como método central de condução do projeto em sua totalidade. À medida que o desenvolvimento avançou, a gestão das atividades passou a apoiar-se predominantemente no fluxo natural do versionamento do código-fonte por meio do sistema Git, com commits semanticamente estruturados, e na documentação técnica incremental do próprio repositório. Tal deslocamento metodológico é coerente com a natureza exploratória do trabalho, no qual a investigação das técnicas de acessibilidade e sua subsequente aplicação demandaram ciclos iterativos de estudo, prototipagem e refinamento que transcendem a rigidez de quadros de tarefas.

## 4.2 Caracterização Tecnológica e Arquitetural

O protótipo foi implementado com o *framework* Laravel, em sua versão 12, que adota o padrão arquitetural *Model-View-Controller* (MVC). A escolha do Laravel fundamenta-se em sua maturidade no ecossistema PHP, na robustez de seus mecanismos de autenticação, validação e roteamento, bem como na produtividade oferecida por suas abstrações de alto nível — notadamente o ORM Eloquent, o motor de *templates* Blade e o sistema de migrações de banco de dados.

A camada de apresentação foi construída com HTML5 semântico, estilizado por meio da biblioteca utilitária Tailwind CSS, e dotada de interatividade leve por intermédio do *framework* Alpine.js. Essa combinação, oriunda do *scaffold* Laravel Breeze, revelou-se adequada ao escopo do trabalho por permitir a criação de componentes acessíveis sem a complexidade inerente a *frameworks* de *single-page application*, os quais frequentemente introduzem desafios adicionais de acessibilidade associados à renderização dinâmica e à manipulação do foco.

A persistência dos dados foi modelada em torno de três entidades principais: `User`, responsável pela autenticação e controle de acesso; `Candidato`, que armazena os dados cadastrais do usuário final, incluindo informações específicas de acessibilidade e de condição de Pessoa com Deficiência (PcD); e `Vaga`, que representa as oportunidades de trabalho disponíveis no sistema. A separação entre `User` e `Candidato` foi uma decisão arquitetural deliberada, motivada pelo princípio da responsabilidade única: enquanto a entidade `User` concentra as preocupações de autenticação e autorização, a entidade `Candidato` modela o domínio de negócio propriamente dito.

O controle de acesso foi implementado por meio de um *middleware* customizado, denominado `RoleMiddleware`, que inspeciona o atributo `role` do usuário autenticado e restringe o acesso às rotas conforme o perfil — administrativo (`admin`) ou candidato (`candidato`). Essa abordagem preserva a simplicidade do sistema de autenticação nativo do Laravel e, ao mesmo tempo, fornece a granularidade necessária para segregar as áreas administrativa e do usuário final.

## 4.3 Implementação do Protótipo

A implementação do protótipo foi conduzida em fases incrementais, cada qual orientada à entrega de um conjunto coeso de funcionalidades. A primeira fase consistiu na configuração do *scaffold* de autenticação e na definição do modelo de papéis, etapa que estabeleceu a infraestrutura necessária para as demais entregas. A Figura 1 ilustra a tela de autenticação do sistema, porta de entrada para ambos os perfis de usuário.

**Figura 1 – Tela de Login do sistema**

<!-- INSERIR PRINT: tela de login (rota /login) -->

Fonte: Elaborado pelo autor (2026).

A segunda fase dedicou-se ao cadastro e à listagem de vagas, incluindo o formulário de criação de oportunidades e a apresentação pública do catálogo. As Figuras 2 e 3 apresentam, respectivamente, a listagem de vagas disponibilizada no ambiente administrativo e o formulário de cadastro de novas oportunidades.

**Figura 2 – Listagem de Vagas (painel administrativo)**

<!-- INSERIR PRINT: tela de listagem de vagas (rota /vagas) -->

Fonte: Elaborado pelo autor (2026).

**Figura 3 – Formulário de Cadastro de Vaga**

<!-- INSERIR PRINT: formulário de criação de vaga (rota /vagas/create) -->

Fonte: Elaborado pelo autor (2026).

A terceira fase concentrou-se no cadastro de candidatos, com especial atenção aos campos destinados à autodeclaração de condição PcD e à descrição de necessidades específicas de acessibilidade. A Figura 4 exibe o formulário público de registro, no qual se destaca a organização dos campos em *fieldsets* semanticamente rotulados e a exibição condicional do campo "Tipo de Deficiência" mediante o acionamento da autodeclaração PcD.

**Figura 4 – Tela de Cadastro de Candidato com campos de acessibilidade**

<!-- INSERIR PRINT: tela de registro (rota /register) evidenciando os fieldsets e o checkbox PCD -->

Fonte: Elaborado pelo autor (2026).

As fases subsequentes ampliaram o painel do usuário candidato, contemplando funcionalidades de visualização e edição de perfil, de candidatura a vagas e de gerenciamento de preferências de acessibilidade — estas últimas abrangendo ajustes de tamanho de fonte, contraste de tema e redução de animações. As Figuras 5, 6 e 7 apresentam, nessa ordem, o painel principal do candidato, a tela de perfil do usuário e a tela de preferências de acessibilidade.

**Figura 5 – Painel do Candidato**

<!-- INSERIR PRINT: dashboard do candidato (rota /usuario/dashboard) -->

Fonte: Elaborado pelo autor (2026).

**Figura 6 – Tela de Perfil do Candidato**

<!-- INSERIR PRINT: tela de perfil com dados pessoais e informações PcD -->

Fonte: Elaborado pelo autor (2026).

**Figura 7 – Tela de Preferências de Acessibilidade**

<!-- INSERIR PRINT: tela de preferências (fonte, tema, animações) -->

Fonte: Elaborado pelo autor (2026).

Por fim, as fases finais foram dedicadas à integração do fluxo de registro com a criação simultânea de um `User` com perfil `candidato` e do respectivo `Candidato`, bem como à elaboração de testes automatizados de integração.

A estratégia de testes empregou o *framework* PHPUnit em conjunto com a *trait* `RefreshDatabase` e um banco de dados SQLite em memória, conforme recomendações da documentação oficial do Laravel. Essa configuração assegura o isolamento entre casos de teste e reduz drasticamente o tempo de execução da *suite*, viabilizando sua utilização como mecanismo de verificação contínua. Ao término da implementação, a *suite* de testes contabilizou setenta casos de teste, abrangendo autenticação, autorização baseada em papéis, fluxo de registro com atributos de acessibilidade, criação e listagem de vagas e criação e listagem de candidatos.

## 4.4 Aplicação das Diretrizes WCAG 2.1

A incorporação das diretrizes WCAG 2.1, publicadas pelo *World Wide Web Consortium* (W3C), orientou de maneira transversal todas as decisões de implementação da camada de apresentação. Adotou-se como meta a conformidade com o Nível AA, considerado pela literatura e pela legislação brasileira — em particular, pela Lei Brasileira de Inclusão da Pessoa com Deficiência — como o patamar apropriado para aplicações web de interesse público. A aplicação das diretrizes é apresentada a seguir, organizada segundo os quatro princípios fundamentais da norma.

### 4.4.1 Perceptível

O princípio da perceptibilidade determina que as informações e os componentes da interface sejam apresentáveis aos usuários de modo que possam ser percebidos. Para assegurar conformidade com este princípio, o protótipo utiliza elementos HTML semânticos — tais como `<header>`, `<nav>`, `<main>`, `<section>`, `<fieldset>` e `<legend>` — que transmitem a estrutura do documento aos leitores de tela. Todos os campos de formulário estão associados a elementos `<label>` explícitos por meio do atributo `for`, e mensagens de erro são correlacionadas aos respectivos controles por meio dos atributos `aria-describedby` e `aria-invalid`.

Os pares de cor utilizados na estilização foram selecionados a partir de verificações de razão de contraste, de modo a atender ao critério de sucesso 1.4.3 (*Contrast Minimum*), que exige razão mínima de 4,5:1 para texto normal e 3:1 para texto em tamanho grande. A tipografia foi definida em unidades relativas, permitindo que o ajuste de tamanho realizado pelo usuário — seja no navegador, seja no próprio painel de preferências da aplicação — seja acomodado sem quebras de *layout*.

### 4.4.2 Operável

A operabilidade foi endereçada por meio da garantia de navegação completa via teclado, incluindo ordem de tabulação lógica e indicadores visuais de foco em todos os elementos interativos. O critério 2.4.7 (*Focus Visible*) é atendido pelo uso consistente das classes utilitárias `focus:ring-2` e `focus:outline-none` — esta última acompanhada obrigatoriamente de um anel de foco alternativo — que preservam a visibilidade do foco sem suprimir a indicação nativa do navegador de forma lesiva.

Implementou-se, ainda, um *skip link* na camada de layout, permitindo ao usuário de teclado ou de tecnologia assistiva saltar diretamente ao conteúdo principal, em conformidade com o critério 2.4.1 (*Bypass Blocks*). Os rótulos das páginas foram estruturados de modo a prover títulos únicos e descritivos, em atendimento ao critério 2.4.2 (*Page Titled*).

### 4.4.3 Compreensível

O princípio da compreensibilidade exige que o conteúdo textual e a operação da interface sejam inteligíveis. O atributo `lang="pt-BR"` é declarado no elemento raiz do documento, fornecendo a informação necessária aos leitores de tela para a seleção apropriada do mecanismo de síntese de voz. As mensagens de erro de validação de formulário são apresentadas em linguagem objetiva e instrutiva, evitando jargão técnico e indicando, sempre que cabível, a ação corretiva esperada do usuário.

Regiões dinâmicas de feedback, tais como o bloco que consolida os erros de validação após o envio de um formulário, são marcadas com os atributos `role="alert"`, `aria-live="assertive"` e `aria-atomic="true"`, de modo que leitores de tela anunciem automaticamente as alterações de estado ao usuário, sem necessidade de que este reposicione o foco. A Figura 8 exemplifica a exibição de mensagens de erro de validação em conformidade com essa diretriz.

**Figura 8 – Exibição de mensagens de erro de validação em região ARIA live**

<!-- INSERIR PRINT: formulário com erros de validação exibidos no bloco role="alert" -->

Fonte: Elaborado pelo autor (2026).

### 4.4.4 Robusto

A robustez, quarto princípio da WCAG, é atendida pela aderência à especificação HTML5 e pelo uso criterioso dos atributos WAI-ARIA apenas nas situações em que a semântica nativa do HTML não é suficiente — prática recomendada pela primeira regra da *ARIA Authoring Practices Guide*, que estabelece a preferência por elementos nativos sobre atributos ARIA customizados. Os *templates* Blade são compilados para produzir marcação validada, e a interação com Alpine.js preserva a acessibilidade dos elementos subjacentes por meio do uso de `x-bind` para propriedades booleanas como `required` e `aria-required`, evitando conflitos entre a renderização *server-side* e a reatividade *client-side*.

## 4.5 Validação e Avaliação do Protótipo

A avaliação do protótipo combinou estratégias automatizadas e analíticas, com o propósito de triangular evidências e reduzir os vieses inerentes a cada técnica isoladamente. A adoção simultânea de ferramentas automatizadas, avaliação heurística por especialistas e uso de agentes de Inteligência Artificial como revisores segue a recomendação consolidada na literatura, segundo a qual nenhuma técnica isolada é capaz de detectar a totalidade dos problemas de acessibilidade.

### 4.5.1 Validação Automatizada com WAVE e AXE

Para a verificação automatizada, empregaram-se duas ferramentas amplamente reconhecidas: o *Web Accessibility Evaluation Tool* (WAVE), mantido pelo *WebAIM*, e o *Accessibility Engine* (AXE), desenvolvido pela Deque Systems. Ambas operam por análise estática do Documento Objeto de Modelagem (*Document Object Model*, DOM) da página renderizada, aplicando um conjunto de regras derivadas dos critérios de sucesso da WCAG 2.1 e reportando violações, alertas e ocorrências estruturais.

O WAVE foi utilizado em sua modalidade de extensão de navegador, aplicada de forma sistemática a cada uma das páginas do protótipo — incluindo as telas de *login*, registro, listagem de vagas, detalhamento de vaga, painel do candidato, edição de perfil e configuração de preferências de acessibilidade. O AXE, por sua vez, foi executado via extensão *axe DevTools* em conjunto com a ferramenta de desenvolvimento do navegador, permitindo análise detalhada de cada regra violada e a inspeção do elemento responsável pela ocorrência.

É importante reconhecer que ferramentas automatizadas são capazes de detectar apenas uma parcela dos problemas reais de acessibilidade, restando um universo substancial de violações — especialmente aquelas relacionadas à semântica, ao contexto e à adequação dos rótulos — que requerem avaliação humana. Por esse motivo, os relatórios produzidos pelo WAVE e pelo AXE foram tratados como condição necessária, mas não suficiente, de aderência às diretrizes. As Figuras 9 e 10 apresentam, respectivamente, relatórios gerados pelas ferramentas WAVE e AXE sobre o protótipo desenvolvido.

**Figura 9 – Relatório do WAVE aplicado ao protótipo**

<!-- INSERIR PRINT: extensão WAVE executada sobre uma das páginas do sistema -->

Fonte: Elaborado pelo autor (2026) a partir da ferramenta WAVE (WebAIM).

**Figura 10 – Relatório do axe DevTools aplicado ao protótipo**

<!-- INSERIR PRINT: extensão axe DevTools aberta no DevTools do navegador -->

Fonte: Elaborado pelo autor (2026) a partir da ferramenta axe DevTools (Deque Systems).

### 4.5.2 Avaliação Heurística

A avaliação heurística consistiu na aplicação combinada das dez heurísticas de usabilidade de Nielsen e dos critérios de sucesso da WCAG 2.1. As heurísticas de Nielsen, embora não tenham sido originalmente formuladas com foco em acessibilidade, fornecem um arcabouço consistente para a análise da qualidade da interação, especialmente no que tange à visibilidade do estado do sistema, à consistência e padronização, à prevenção de erros e à flexibilidade e eficiência de uso — aspectos que, quando bem executados, beneficiam também os usuários de tecnologias assistivas.

A inspeção foi conduzida página a página, com registro sistemático dos achados e classificação por gravidade. Problemas identificados foram remediados por meio de refatoração da marcação ou dos estilos, e os ciclos de verificação foram repetidos até a estabilização das interfaces. A adoção combinada das heurísticas gerais de usabilidade e dos critérios específicos da WCAG permitiu, por exemplo, identificar situações em que a informação apresentada era tecnicamente acessível — rotulada, contrastada e perceptível — mas ainda assim apresentava fragilidades de organização ou de linguagem que comprometeriam a experiência dos usuários em condições reais de uso.

### 4.5.3 Revisão por Agentes de Inteligência Artificial

Como estratégia complementar de avaliação, o protótipo foi submetido à inspeção por agentes de Inteligência Artificial baseados em modelos de linguagem de grande porte (*Large Language Models*, LLMs). Os agentes foram instruídos a atuar como auditores de acessibilidade, recebendo como entrada o código-fonte dos *templates* Blade, dos estilos e dos *scripts* da aplicação, e produzindo como saída uma análise estruturada das potenciais conformidades e não conformidades com a WCAG 2.1.

A justificativa para o emprego dessa técnica reside na capacidade dos LLMs de realizarem inferências contextuais sobre a semântica da marcação — avaliando, por exemplo, a pertinência de um texto alternativo em relação à imagem que descreve, ou a adequação de um rótulo em relação ao campo que identifica — algo que se mostra notoriamente limitado em ferramentas de análise estática puramente baseadas em regras. Tal abordagem é consonante com a tendência emergente de utilização de modelos de linguagem como instrumento de apoio à avaliação de acessibilidade.

A análise produzida pelos agentes de IA foi tratada criticamente, à luz do conhecimento técnico acumulado ao longo do desenvolvimento, evitando-se a aceitação acrítica de suas recomendações. Os apontamentos pertinentes foram incorporados ao ciclo de refinamento do protótipo; os incorretos ou irrelevantes foram descartados. Desse modo, os agentes atuaram como revisores auxiliares e não como instância decisória. A Figura 11 ilustra o ambiente de interação com o agente de IA durante a revisão de acessibilidade.

**Figura 11 – Interação com agente de IA para revisão de acessibilidade**

<!-- INSERIR PRINT: tela do agente de IA com análise da marcação acessível -->

Fonte: Elaborado pelo autor (2026).

### 4.5.4 Testes Automatizados de Integração

Em complemento às técnicas de avaliação de acessibilidade propriamente ditas, foi elaborada uma *suite* de testes automatizados de integração, com o objetivo de garantir que os fluxos funcionais da aplicação — dos quais a camada acessível depende — operassem conforme o esperado. Os testes cobrem o fluxo completo de registro de usuário com atributos de acessibilidade (declaração PcD, tipo de deficiência e descrição de necessidades específicas), a autenticação e o redirecionamento condicionado ao papel, a criação e listagem de vagas e de candidatos, e a validação das regras de negócio associadas à duplicidade de *e-mail* e à obrigatoriedade de campos.

A manutenção dessa *suite* como parte integrante do repositório do projeto cumpre uma função metodológica adicional: assegura que refatorações posteriores, realizadas com vistas à melhoria da acessibilidade, não introduzam regressões funcionais que comprometam a usabilidade do artefato. A Figura 12 apresenta a saída da execução completa da *suite* de testes automatizados.

**Figura 12 – Execução da suíte de testes PHPUnit**

<!-- INSERIR PRINT: terminal com saída do `php artisan test` exibindo os 70 testes aprovados -->

Fonte: Elaborado pelo autor (2026).

## 4.6 Síntese dos Resultados

Ao término do ciclo de desenvolvimento, o protótipo apresenta-se como um sistema web funcional, capaz de intermediar o cadastro de candidatos — inclusive aqueles com autodeclaração de condição de Pessoa com Deficiência — e o anúncio de vagas de emprego, oferecendo funcionalidades específicas de personalização de acessibilidade ao usuário final. A verificação automatizada por meio do WAVE e do AXE, conjugada à avaliação heurística fundamentada nas diretrizes de Nielsen e da WCAG 2.1, e complementada pela inspeção apoiada por agentes de Inteligência Artificial, indicou aderência substancial ao Nível AA da norma.

Os resultados obtidos, ainda que circunscritos ao escopo de um protótipo exploratório e não validados junto a usuários finais em estudo experimental, evidenciam a viabilidade técnica da incorporação sistemática das diretrizes WCAG 2.1 ao processo de desenvolvimento de aplicações web construídas sobre o *framework* Laravel, com custo incremental reduzido quando tais diretrizes são consideradas desde a fase de definição arquitetural, em oposição à sua aplicação posterior, à guisa de adequação corretiva. Essa constatação corrobora a tese, amplamente defendida pela literatura, de que a acessibilidade, quando tratada como requisito de primeira classe, converge de maneira sinérgica com objetivos gerais de qualidade de software — tais como manutenibilidade, testabilidade e clareza semântica — em vez de com eles concorrer.
