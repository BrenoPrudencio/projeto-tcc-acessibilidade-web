<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Vaga - {{ $vaga->titulo }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Editar Vaga</h1>
        <hr>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Ops!</strong> Ocorreram alguns problemas.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('vagas.update', $vaga->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="titulo" class="form-label">Título da Vaga</label>
                <input type="text" class="form-control" id="titulo" name="titulo" value="{{ old('titulo', $vaga->titulo) }}" required>
            </div>

            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao" rows="4" required>{{ old('descricao', $vaga->descricao) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="tipo_contratacao" class="form-label">Tipo de Contratação</label>
                <select class="form-select" id="tipo_contratacao" name="tipo_contratacao" required>
                    <option value="">Selecione um tipo</option>
                    <option value="CLT" @if(old('tipo_contratacao', $vaga->tipo_contratacao) == 'CLT') selected @endif>CLT</option>
                    <option value="PJ" @if(old('tipo_contratacao', $vaga->tipo_contratacao) == 'PJ') selected @endif>Pessoa Jurídica</option>
                    <option value="Freelancer" @if(old('tipo_contratacao', $vaga->tipo_contratacao) == 'Freelancer') selected @endif>Freelancer</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="ativa" @if(old('status', $vaga->status) == 'ativa') selected @endif>Ativa</option>
                    <option value="pausada" @if(old('status', $vaga->status) == 'pausada') selected @endif>Pausada</option>
                </select>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Atualizar Vaga</button>
                <a href="{{ route('vagas.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>