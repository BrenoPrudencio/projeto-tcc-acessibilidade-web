<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Novo Candidato</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :focus-visible { outline: 3px solid #0d6efd; outline-offset: 2px; }
        .high-contrast body,
        .high-contrast .form-control,
        .high-contrast .btn { background:#000 !important; color:#fff !important; }
        .visually-hidden {
            position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0 0 0 0); border:0;
        }
        .required-indicator { color:#c00; }                                
    </style>
</head>
<body>
    <a href="#conteudo" class="visually-hidden focusable">Pular para o conteúdo principal</a>

    <div class="container mt-5" id="conteudo">
        <h1 class="h2 mb-3">Novo Candidato</h1>

        <div class="d-flex gap-2 mb-3">
            <button type="button" id="toggle-contrast" class="btn btn-sm btn-outline-secondary">Alto contraste</button>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert" aria-live="assertive">
                <p class="mb-2"><strong>Há problemas no formulário:</strong></p>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('candidatos.store') }}" method="POST" novalidate>
            @csrf

            <div class="mb-3">  
                <label for="nome" class="form-label">
                    Nome Completo <span class="required-indicator" aria-hidden="true">*</span>
                </label>
                <input
                    type="text"
                    class="form-control @error('nome') is-invalid @enderror"
                    id="nome"
                    name="nome"
                    value="{{ old('nome') }}"
                    required
                    aria-required="true"
                    @error('nome') aria-describedby="erro-nome" @enderror
                    autocomplete="name">
                @error('nome')
                    <div id="erro-nome" class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">
                    Email <span class="required-indicator" aria-hidden="true">*</span>
                </label>
                <input
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    aria-required="true"
                    autocomplete="email"
                    inputmode="email"
                    @error('email') aria-describedby="erro-email" @enderror>
                @error('email')
                    <div id="erro-email" class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="telefone" class="form-label">
                    Telefone <span class="required-indicator" aria-hidden="true">*</span>
                    <span class="visually-hidden">(Formato: (99) 99999-9999)</span>
                </label>
                <input
                    type="text"
                    class="form-control @error('telefone') is-invalid @enderror"
                    id="telefone"
                    name="telefone"
                    value="{{ old('telefone') }}"
                    required
                    aria-required="true"
                    inputmode="tel"
                    aria-describedby="ajuda-telefone @error('telefone') erro-telefone @enderror"
                    placeholder="(99) 99999-9999">
                <div id="ajuda-telefone" class="form-text">Formato esperado: (11) 98888-7777</div>
                @error('telefone')
                    <div id="erro-telefone" class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <fieldset class="mb-3">
                <legend class="fs-6 mb-2">Informações de Acessibilidade (opcional)</legend>

                <div class="form-check mb-2">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="pcd"
                        id="pcd"
                        value="1"
                        {{ old('pcd') ? 'checked' : '' }}>
                    <label class="form-check-label" for="pcd">Sou Pessoa com Deficiência (PCD)</label>
                </div>

                <div class="mb-2">
                    <label for="tipo_deficiencia" class="form-label">Tipo de deficiência (opcional)</label>
                    <select
                        id="tipo_deficiencia"
                        name="tipo_deficiencia"
                        class="form-select"
                        {{ old('pcd') ? '' : 'disabled' }}>
                        <option value="">Selecione...</option>
                        <option value="visual" {{ old('tipo_deficiencia')=='visual'?'selected':'' }}>Visual</option>
                        <option value="auditiva" {{ old('tipo_deficiencia')=='auditiva'?'selected':'' }}>Auditiva</option>
                        <option value="motora" {{ old('tipo_deficiencia')=='motora'?'selected':'' }}>Motora</option>
                        <option value="intelectual" {{ old('tipo_deficiencia')=='intelectual'?'selected':'' }}>Intelectual</option>
                        <option value="autismo" {{ old('tipo_deficiencia')=='autismo'?'selected':'' }}>TEA</option>
                        <option value="outra" {{ old('tipo_deficiencia')=='outra'?'selected':'' }}>Outra</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="acessibilidade" class="form-label">Necessidades de acessibilidade (opcional)</label>
                    <textarea
                        id="acessibilidade"
                        name="acessibilidade"
                        class="form-control"
                        rows="2"
                        maxlength="500"
                        aria-describedby="ajuda-acess">{{ old('acessibilidade') }}</textarea>
                    <div id="ajuda-acess" class="form-text">
                        Ex: Intérprete de Libras, rampa, leitor de tela, tempo adicional.
                    </div>
                </div>
            </fieldset>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Salvar Candidato</button>
                <a href="{{ route('candidatos.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <script src="https://unpkg.com/imask"></script>
    <script>
        const telEl = document.getElementById('telefone');
        IMask(telEl, { mask: '(00) 00000-0000' });

        document.getElementById('toggle-contrast').addEventListener('click', () => {
            document.documentElement.classList.toggle('high-contrast');
        });

        const pcdChk = document.getElementById('pcd');
        const tipoDef = document.getElementById('tipo_deficiencia');
        pcdChk.addEventListener('change', () => {
            tipoDef.disabled = !pcdChk.checked;
        });

        const invalid = document.querySelector('.is-invalid');
        if (invalid) invalid.focus();
    </script>
</body>
</html>