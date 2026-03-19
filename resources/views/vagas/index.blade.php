<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Vagas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container mt-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <div class="container mt-5">
        {{-- Formulário de Filtro --}}
        <div class="card mb-4">
            <div class="card-header"><h3><i class="fas fa-filter"></i> Filtros de Busca</h3></div>
            <div class="card-body">
                <form action="{{ route('vagas.index') }}" method="GET" id="filter-form">
                    <input type="hidden" name="per_page" id="per_page_input" value="{{ request('per_page', 20) }}">
                    <div class="row align-items-end">
                        <div class="col-md-4"><label for="search" class="form-label">Buscar por Título</label><input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Ex: Desenvolvedor..."></div>
                        <div class="col-md-2"><label for="tipo" class="form-label">Tipo</label><select id="tipo" name="tipo" class="form-select"><option value="">Todos</option><option value="CLT" @if(request('tipo') == 'CLT') selected @endif>CLT</option><option value="PJ" @if(request('tipo') == 'PJ') selected @endif>PJ</option><option value="Freelancer" @if(request('tipo') == 'Freelancer') selected @endif>Freelancer</option></select></div>
                        <div class="col-md-2"><label for="status" class="form-label">Status</label><select id="status" name="status" class="form-select"><option value="">Todos</option><option value="ativa" @if(request('status') == 'ativa') selected @endif>Ativa</option><option value="pausada" @if(request('status') == 'pausada') selected @endif>Pausada</option></select></div>
                        <div class="col-md-2 d-grid"><button type="submit" class="btn btn-primary">Filtrar</button></div>
                        <div class="col-md-2 d-grid"><a href="{{ route('vagas.index') }}" class="btn btn-secondary">Limpar</a></div>
                    </div>
                </form>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Lista de Vagas</h1>
            <a href="{{ route('vagas.create') }}" class="btn btn-success">Criar Nova Vaga</a>
        </div>

        {{-- Formulário de Ações em Massa --}}
        <form action="{{ route('vagas.destroy.mass') }}" method="POST" id="bulk-actions-form">
            @csrf
            @method('DELETE')
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div><button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir as vagas selecionadas?')">Excluir Selecionados</button></div>
                <div class="d-flex align-items-center">
                    <label for="per_page_select" class="form-label me-2 mb-0">Itens por página:</label>
                    <select id="per_page_select" class="form-select w-auto">
                        <option value="10" @if(request('per_page') == 10) selected @endif>10</option>
                        <option value="20" @if(request('per_page', 20) == 20) selected @endif>20</option>
                        <option value="50" @if(request('per_page') == 50) selected @endif>50</option>
                    </select>
                </div>
            </div>
            
            {{-- A TABELA ESTÁ DE VOLTA AQUI --}}
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 5%;"><input type="checkbox" id="select-all"></th>
                            <th style="width: 5%;">ID</th>
                            <th style="width: 35%;">Título</th>
                            <th style="width: 15%;">Tipo</th>
                            <th style="width: 15%;">Status</th>
                            <th style="width: 25%;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vagas as $vaga)
                            <tr>
                                <td><input type="checkbox" name="ids[]" value="{{ $vaga->id }}" class="row-checkbox"></td>
                                <td>{{ $vaga->id }}</td>
                                <td><a href="{{ route('vagas.show', $vaga->id) }}">{{ $vaga->titulo }}</a></td>
                                <td>{{ $vaga->tipo_contratacao }}</td>
                                <td><span class="badge bg-{{ $vaga->status == 'ativa' ? 'success' : 'secondary' }}">{{ ucfirst($vaga->status) }}</span></td>
                                <td>
                                    <a href="{{ route('vagas.edit', $vaga->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                    <form action="{{ route('vagas.destroy', $vaga->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-4">Nenhuma vaga encontrada com os filtros aplicados.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>

        <div class="d-flex justify-content-center mt-4">
            {!! $vagas->links('pagination::bootstrap-5') !!}
        </div>
    </div>

    <script>
        document.getElementById('select-all').addEventListener('click', function(event) {
            document.querySelectorAll('.row-checkbox').forEach(checkbox => {
                checkbox.checked = event.target.checked;
            });
        });

        document.getElementById('per_page_select').addEventListener('change', function() {
            const perPageValue = this.value;
            const form = document.getElementById('filter-form');
            form.querySelector('input[name="per_page"]').value = perPageValue;
            form.submit();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>