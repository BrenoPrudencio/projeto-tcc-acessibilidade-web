<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Candidatos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :focus-visible { outline: 3px solid #0d6efd; outline-offset: 2px; }
        .high-contrast body,
        .high-contrast .form-control,
        .high-contrast .btn { background:#000 !important; color:#fff !important; }
        .visually-hidden {
            position:absolute;
            width:1px;
            height:1px;
            padding:0;
            margin:-1px;
            overflow:hidden;
            clip:rect(0 0 0 0);
            white-space:nowrap;
            border:0;   
        }
        .required-indicator { color:#c00; }
    </style>
</head>
<body>

    <div class="container mt-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" aria-live="polite">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        @endif
    </div>

    <div class="container mt-5">
        {{-- Formulário de Filtro --}}
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="mb-0"><i class="fas fa-filter"></i> Filtros de Busca</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('candidatos.index') }}" method="GET" id="filter-form">
                    <input type="hidden" name="per_page" id="per_page_input" value="{{ request('per_page', 20) }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label for="search" class="form-label">Buscar por Nome ou Email</label>
                            <input type="text"
                                   class="form-control"
                                   id="search"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Digite um nome ou email...">
                        </div>
                        <div class="col-md-2">
                            <label for="pcd" class="form-label">PCD</label>
                            <select id="pcd" name="pcd" class="form-select">
                                <option value="">Todos</option>
                                <option value="1" @selected(request('pcd')==='1')>Sim</option>
                                <option value="0" @selected(request('pcd')==='0')>Não</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-grid">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                        <div class="col-md-2 d-grid">
                            <a href="{{ route('candidatos.index') }}" class="btn btn-secondary">Limpar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Cabeçalho / Ações --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0">Lista de Candidatos</h1>
            <div class="d-flex gap-2">
                <button type="button" id="toggle-contrast" class="btn btn-sm btn-outline-secondary">Alto contraste</button>
                <a href="{{ route('candidatos.create') }}" class="btn btn-success">Novo Candidato</a>
            </div>
        </div>

        {{-- Formulário de Ações em Massa + Tabela --}}
        <form action="{{ route('candidatos.destroy.mass') }}" method="POST" id="bulk-actions-form">
            @csrf
            @method('DELETE')

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <button type="submit"
                            class="btn btn-danger"
                            onclick="return confirm('Tem certeza que deseja excluir os candidatos selecionados?')">
                        Excluir Selecionados
                    </button>
                </div>
                <div class="d-flex align-items-center">
                    <label for="per_page_select" class="form-label me-2 mb-0">Itens por página:</label>
                    <select id="per_page_select" class="form-select w-auto">
                        <option value="10" @if(request('per_page') == 10) selected @endif>10</option>
                        <option value="20" @if(request('per_page', 20) == 20) selected @endif>20</option>
                        <option value="50" @if(request('per_page') == 50) selected @endif>50</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th style="width:5%;"><input type="checkbox" id="select-all" aria-label="Selecionar todos"></th>
                            <th style="width:5%;">ID</th>
                            <th style="width:26%;">Nome</th>
                            <th style="width:24%;">Email</th>
                            <th style="width:12%;">Telefone</th>
                            <th style="width:8%;">PCD</th>
                            <th style="width:20%;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($candidatos as $candidato)
                            <tr>
                                <td>
                                    <input type="checkbox"
                                           name="ids[]"
                                           value="{{ $candidato->id }}"
                                           class="row-checkbox"
                                           aria-label="Selecionar candidato {{ $candidato->nome }}">
                                </td>
                                <td>{{ $candidato->id }}</td>
                                <td>{{ $candidato->nome }}</td>
                                <td>{{ $candidato->email }}</td>
                                <td>{{ $candidato->telefone_formatado }}</td>
                                <td>
                                    @if($candidato->pcd)
                                        <span class="badge bg-primary">
                                            PCD
                                            @if($candidato->tipo_deficiencia)
                                                <span class="visually-hidden">Tipo: {{ $candidato->tipo_deficiencia }}</span>
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('candidatos.edit', $candidato->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                    <form action="{{ route('candidatos.destroy', $candidato->id) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Tem certeza?')">
                                            Excluir
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">Nenhum candidato encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        <div class="d-flex justify-content-center mt-4">
            {!! $candidatos->links('pagination::bootstrap-5') !!}
        </div>
    </div>

    <script>
        // Toggle alto contraste
        const btnContrast = document.getElementById('toggle-contrast');
        if (btnContrast) {
            btnContrast.addEventListener('click', () => {
                document.documentElement.classList.toggle('high-contrast');
            });
        }

        // Selecionar todos
        const selectAll = document.getElementById('select-all');
        if (selectAll) {
            selectAll.addEventListener('click', function (e) {
                document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = e.target.checked);
            });
        }

        // Alterar per_page             
        const perPageSelect = document.getElementById('per_page_select');
        if (perPageSelect) {
            perPageSelect.addEventListener('change', function () {
                document.getElementById('per_page_input').value = this.value;
                document.getElementById('filter-form').submit();
            });
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>