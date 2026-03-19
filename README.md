# Sistema de Gestão de Vagas e Candidaturas — Painel do Usuário

![Página de Listagem de Vagas](https://i.imgur.com/3xA8l1f.png)  

Módulo **Painel do Usuário** do Sistema-Vagas, desenvolvido como **Trabalho de Conclusão de Curso (TCC)** em Ciência da Computação. Este projeto é a evolução do painel administrativo original, adicionando uma interface pública voltada ao **candidato**, com foco em **acessibilidade web (WCAG 2.1 — Nível AA)** e **inclusão de pessoas com deficiência (PCD)**.

> **Projeto Base:** Originalmente criado como sistema de gerenciamento de vagas para recrutadores, este repositório estende a aplicação para contemplar a experiência do usuário-candidato, permitindo auto-inscrição, acompanhamento de candidaturas e configurações de acessibilidade personalizadas.

---

## 🎓 Contexto Acadêmico

| Item | Detalhe |
|------|---------|
| **Tipo** | Trabalho de Conclusão de Curso (TCC) |
| **Área** | Ciência da Computação |
| **Tema** | Acessibilidade Web em Sistemas de Recrutamento |
| **Padrão de Referência** | WCAG 2.1 — Nível AA |
| **Foco** | Desenvolvimento do Painel do Usuário (Candidato) com acessibilidade |

---

## 🚀 Tecnologias Utilizadas

- **Backend:** PHP, Laravel
- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 5, Tailwind CSS (via Laravel Breeze)
- **Banco de Dados:** MySQL
- **Ambiente de Desenvolvimento:** Docker, Laravel Sail
- **Gerenciamento de Dependências:** Composer, NPM
- **Testes:** PHPUnit
- **Versionamento:** Git & GitHub

---

## ✨ Funcionalidades Principais

### 🔐 1. Autenticação de Usuários
- Sistema completo de **Registro** e **Login** utilizando Laravel Breeze.
- As rotas de gerenciamento são protegidas, garantindo que apenas usuários autenticados possam acessar, criar, editar ou excluir dados.

### 📋 2. CRUD de Vagas (Painel Administrativo)
- **Criação, Leitura, Atualização e Exclusão (CRUD)** de vagas de emprego.
- **Pausa de Vagas:** Uma vaga pode ter seu status alterado para "Pausada", impedindo novas inscrições.
- **Listagem com Filtros:** Lista paginada com filtros dinâmicos por título, tipo de contratação e status.

### 👤 3. CRUD de Candidatos (Painel Administrativo)
- **CRUD completo** para o gerenciamento de candidatos, incluindo dados de acessibilidade PCD.
- **Formatação de Dados:** Campo de telefone com máscara de entrada para melhor experiência.
- **Listagem com Filtros:** Lista paginada com busca por nome/email e filtro por status PCD.

### 🔗 4. Sistema de Inscrição
- **Relação Muitos-para-Muitos:** Um candidato pode se inscrever em múltiplas vagas, e uma vaga pode ter múltiplos candidatos.
- **Interface de Inscrição:** Na página de detalhes de uma vaga, é possível ver candidatos inscritos e inscrever novos.
- **Cancelamento de Inscrição:** Remoção da inscrição de um candidato de uma vaga específica.

### 🧑‍💻 5. Painel do Usuário (Candidato) — *Em Desenvolvimento*
- **Visualização pública de vagas** com filtros e busca acessível.
- **Auto-inscrição em vagas** pelo próprio candidato.
- **Acompanhamento de candidaturas** com status em tempo real.
- **Perfil de acessibilidade** para informar necessidades PCD.
- **Interface 100% acessível** seguindo WCAG 2.1 — Nível AA.

### ⚙️ 6. Melhorias de Usabilidade
- **Deleção em Massa:** Seleção de múltiplos itens para exclusão em uma única ação.
- **Controle de Paginação:** Escolha da quantidade de itens exibidos por página.

### 🌐 7. API RESTful
- **Endpoints JSON:** APIs para os CRUDs de Vagas e Candidatos, retornando dados em formato JSON.

### ✅ 8. Qualidade e Boas Práticas
- **Ambiente Dockerizado:** Laravel Sail para ambiente de desenvolvimento consistente e portátil.
- **Testes Automatizados:** Suíte de testes de funcionalidade (Feature Tests) cobrindo os CRUDs.
- **Dados de Teste (Seeders):** Banco de dados populável com dados falsos para simulação.

---

## 🐳 Instalação com Docker (Laravel Sail) — Método Recomendado

Este projeto foi configurado para ser executado em um ambiente Docker, garantindo consistência e facilidade na configuração.

### Pré-requisitos
- Docker Desktop
- WSL2 (para usuários Windows) ou um ambiente Linux/macOS.

### Passo a Passo

1.  **Clone o repositório:**
    ```bash
    git clone https://github.com/BrenoPrudencio/Painel-Usuario.git
    ```

2.  **Navegue até a pasta do projeto:**
    ```bash
    cd Painel-Usuario
    ```

3.  **Configure o Ambiente:**
    Copie o arquivo de exemplo `.env`. Ele já vem pré-configurado para o Sail.
    ```bash
    cp .env.example .env
    ```

4.  **Instale as dependências do Composer:**
    *Este comando usa uma imagem Docker temporária para instalar os pacotes PHP.*
    ```bash
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php83-composer:latest \
        composer install --ignore-platform-reqs
    ```

5.  **Inicie os Containers do Sail:**
    *O download das imagens pode ser demorado na primeira vez.*
    ```bash
    # Para Linux/macOS/WSL
    ./vendor/bin/sail up -d
    ```

6.  **Execute os Comandos de Finalização:**
    *Use o Sail para executar os comandos Artisan e NPM dentro dos containers.*
    ```bash
    ./vendor/bin/sail npm install
    ./vendor/bin/sail artisan key:generate
    ./vendor/bin/sail artisan migrate:fresh --seed
    ./vendor/bin/sail npm run build
    ```

7.  **Acesse a Aplicação:**
    - Abra seu navegador e acesse `http://localhost`.
    - Você pode se registrar com um novo usuário para começar.

Para parar os containers, use o comando `./vendor/bin/sail down`.

---

## 📚 Documentação

| Documento | Descrição |
|-----------|-----------|
| [DOCS-PAINEL-USUARIO.md](./DOCS-PAINEL-USUARIO.md) | Documentação técnica completa do Painel do Usuário com análise de acessibilidade, tasks e roadmap de implementação |

---

## 📝 Licença

Projeto acadêmico desenvolvido para fins de TCC — Trabalho de Conclusão de Curso.