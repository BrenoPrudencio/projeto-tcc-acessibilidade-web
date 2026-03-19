<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Nova Vaga</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Criar Nova Vaga</h1>
        <hr>

        {{-- Bloco para exibir erros de validação --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Ops!</strong> Ocorreram alguns problemas com os dados informados.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('vagas.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="titulo" class="form-label">Título da Vaga</label>
                <input type="text" class="form-control" id="titulo" name="titulo" value="{{ old('titulo') }}" required>
            </div>

            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="4" required>{{ old('descricao') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="tipo_contratacao" class="form-label">Tipo de Contratação</label>
                <select class="form-select" id="tipo_contratacao" name="tipo_contratacao" required>
                    <option value="">Selecione um tipo</option>
                    <option value="CLT" @if(old('tipo_contratacao') == 'CLT') selected @endif>CLT</option>
                    <option value="PJ" @if(old('tipo_contratacao') == 'PJ') selected @endif>Pessoa Jurídica</option>
                    <option value="Freelancer" @if(old('tipo_contratacao') == 'Freelancer') selected @endif>Freelancer</option>
                </select>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Salvar Vaga</button>
                <a href="{{ route('vagas.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>