<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Vaga: {{ $vaga->titulo }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card mb-5">
            <div class="card-header"><h1>{{ $vaga->titulo }}</h1></div>
            <div class="card-body">
                <p><strong>Tipo de Contratação:</strong> {{ $vaga->tipo_contratacao }}</p>
                <p><strong>Status:</strong> <span class="badge bg-{{ $vaga->status == 'ativa' ? 'success' : 'warning' }} fs-6">{{ ucfirst($vaga->status) }}</span></p>
                <h5 class="card-title mt-4">Descrição da Vaga</h5>
                <p class="card-text">{!! nl2br(e($vaga->descricao)) !!}</p>
            </div>
        </div>

        <div class="card mb-5">
            <div class="card-header"><h3>Candidatos Inscritos ({{ $vaga->candidatos->count() }})</h3></div>
            <div class="card-body">
                @if($vaga->candidatos->isNotEmpty())
                    <ul class="list-group">
                        @foreach ($vaga->candidatos as $inscrito)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $inscrito->nome }}</strong><br>
                                    <small class="text-muted">{{ $inscrito->email }}</small>
                                </div>
                                <form action="{{ route('vagas.cancelarInscricao', ['vaga' => $vaga->id, 'candidato' => $inscrito->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Cancelar Inscrição" onclick="return confirm('Tem certeza que deseja cancelar a inscrição deste candidato?')">X</button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">Nenhum candidato inscrito nesta vaga ainda.</p>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h3>Inscrever Novo Candidato</h3></div>
            <div class="card-body">
                @if($vaga->status == 'ativa')
                    <form action="{{ route('vagas.inscrever', $vaga->id) }}" method="POST">
                        @csrf
                        <div class="row g-3 align-items-end">
                            <div class="col-md-8">
                                <label for="candidato_id" class="form-label">Selecione o Candidato</label>
                                <select class="form-select" id="candidato_id" name="candidato_id" required>
                                    <option value="" disabled selected>-- Escolha um candidato --</option>
                                    @foreach ($candidatos as $candidato)
                                        <option value="{{ $candidato->id }}">{{ $candidato->nome }} ({{ $candidato->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">Inscrever</button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="alert alert-warning" role="alert">As inscrições para esta vaga estão pausadas no momento.</div>
                @endif
            </div>
        </div>

        <div class="mt-4"><a href="{{ route('vagas.index') }}" class="btn btn-secondary">&larr; Voltar para a Lista de Vagas</a></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>