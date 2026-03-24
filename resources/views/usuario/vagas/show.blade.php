<x-usuario.layouts.app>
    @section('title', $vaga->titulo)

    <x-slot name="header">
        <nav aria-label="Breadcrumb" class="mb-2">
            <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                <li>
                    <a href="{{ route('usuario.vagas.index') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                        Vagas
                    </a>
                </li>
                <li aria-hidden="true">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                </li>
                <li aria-current="page" class="text-gray-900 dark:text-gray-100 font-medium">
                    {{ Str::limit($vaga->titulo, 40) }}
                </li>
            </ol>
        </nav>
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $vaga->titulo }}
        </h1>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Alertas de feedback --}}
            @if(session('success'))
                <div role="alert" aria-live="assertive" class="mb-6 rounded-md bg-green-50 dark:bg-green-900/30 p-4 border border-green-200 dark:border-green-800">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div role="alert" aria-live="assertive" class="mb-6 rounded-md bg-red-50 dark:bg-red-900/30 p-4 border border-red-200 dark:border-red-800">
                    <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
                </div>
            @endif

            <article class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-6 sm:p-8">

                    {{-- Metadados da vaga --}}
                    <div class="flex flex-wrap items-center gap-3 mb-6">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($vaga->tipo_contratacao === 'CLT')
                                bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                            @elseif($vaga->tipo_contratacao === 'PJ')
                                bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                            @else
                                bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                            @endif">
                            {{ $vaga->tipo_contratacao }}
                        </span>

                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            <time datetime="{{ $vaga->created_at->toISOString() }}">
                                Publicada em {{ $vaga->created_at->format('d/m/Y') }}
                            </time>
                        </span>
                    </div>

                    {{-- Descricao completa --}}
                    <section aria-labelledby="descricao-titulo">
                        <h2 id="descricao-titulo" class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">
                            Descricao da vaga
                        </h2>
                        <div class="prose prose-gray dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-line">{{ $vaga->descricao }}</div>
                    </section>

                    <hr class="my-8 border-gray-200 dark:border-gray-700">

                    {{-- Acao de candidatura --}}
                    <section aria-labelledby="candidatura-titulo" x-data="{
                        isPcd: {{ old('pcd') ? 'true' : 'false' }}
                    }">
                        <h2 id="candidatura-titulo" class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            Candidatar-se a esta vaga
                        </h2>

                        @auth
                            @if(auth()->user()->isCandidato())
                                @if($jaInscrito)
                                    <div class="flex items-center gap-3 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800" role="status">
                                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                            Voce ja esta inscrito nesta vaga. Acompanhe pelo seu painel.
                                        </p>
                                    </div>
                                @else
                                    @if ($errors->any())
                                        <div class="mb-5 rounded-md bg-red-50 dark:bg-red-900/30 border border-red-300 dark:border-red-700 p-4" role="alert" aria-live="assertive">
                                            <p class="font-semibold text-sm text-red-800 dark:text-red-200">Corrija os erros abaixo:</p>
                                            <ul class="mt-2 list-disc list-inside text-sm text-red-700 dark:text-red-300">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('usuario.candidaturas.store') }}" novalidate class="space-y-5">
                                        @csrf
                                        <input type="hidden" name="vaga_id" value="{{ $vaga->id }}">

                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Preencha seus dados para se candidatar. Campos com <span class="text-red-500" aria-hidden="true">*</span><span class="sr-only">asterisco</span> sao obrigatorios.
                                        </p>

                                        {{-- Telefone --}}
                                        <div>
                                            <label for="telefone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Telefone para contato <span class="text-red-500" aria-hidden="true">*</span>
                                            </label>
                                            <input type="tel" id="telefone" name="telefone"
                                                value="{{ old('telefone', $candidato->telefone ?? '') }}"
                                                required aria-required="true"
                                                inputmode="tel"
                                                placeholder="(99) 99999-9999"
                                                aria-describedby="ajuda-telefone @error('telefone') erro-telefone @enderror"
                                                class="mt-1 block w-full rounded-md shadow-sm sm:text-sm
                                                    border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white
                                                    focus:border-blue-500 focus:ring-blue-500
                                                    @error('telefone') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" id="ajuda-telefone">Formato esperado: (11) 98888-7777</p>
                                            @error('telefone')
                                                <p class="mt-1 text-sm text-red-600 dark:text-red-400" id="erro-telefone">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Fieldset PCD --}}
                                        <fieldset class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-800/50">
                                            <legend class="text-base font-medium text-gray-900 dark:text-gray-100 px-2">
                                                Informacoes de Acessibilidade
                                            </legend>

                                            {{-- Checkbox PCD --}}
                                            <div class="mt-3 flex items-start">
                                                <div class="flex items-center h-5">
                                                    <input type="checkbox" id="pcd" name="pcd" value="1"
                                                        x-model="isPcd"
                                                        {{ old('pcd', $candidato->pcd ?? false) ? 'checked' : '' }}
                                                        class="h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded focus:ring-blue-500">
                                                </div>
                                                <div class="ml-3 text-sm">
                                                    <label for="pcd" class="font-medium text-gray-700 dark:text-gray-300">
                                                        Sou Pessoa com Deficiencia (PcD)
                                                    </label>
                                                    <p class="text-gray-500 dark:text-gray-400 text-xs mt-0.5">Marque se voce possui alguma deficiencia para que possamos oferecer os recursos necessarios.</p>
                                                </div>
                                            </div>

                                            {{-- Tipo de deficiencia --}}
                                            <div class="mt-4" x-show="isPcd" x-transition>
                                                <label for="tipo_deficiencia" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Tipo de deficiencia
                                                </label>
                                                <select id="tipo_deficiencia" name="tipo_deficiencia"
                                                    x-bind:disabled="!isPcd"
                                                    class="mt-1 block w-full rounded-md sm:text-sm
                                                        border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white
                                                        focus:border-blue-500 focus:ring-blue-500
                                                        disabled:opacity-50 disabled:cursor-not-allowed">
                                                    <option value="">Selecione...</option>
                                                    <option value="visual" {{ old('tipo_deficiencia', $candidato->tipo_deficiencia ?? '') == 'visual' ? 'selected' : '' }}>Visual</option>
                                                    <option value="auditiva" {{ old('tipo_deficiencia', $candidato->tipo_deficiencia ?? '') == 'auditiva' ? 'selected' : '' }}>Auditiva</option>
                                                    <option value="motora" {{ old('tipo_deficiencia', $candidato->tipo_deficiencia ?? '') == 'motora' ? 'selected' : '' }}>Motora</option>
                                                    <option value="intelectual" {{ old('tipo_deficiencia', $candidato->tipo_deficiencia ?? '') == 'intelectual' ? 'selected' : '' }}>Intelectual</option>
                                                    <option value="autismo" {{ old('tipo_deficiencia', $candidato->tipo_deficiencia ?? '') == 'autismo' ? 'selected' : '' }}>TEA (Transtorno do Espectro Autista)</option>
                                                    <option value="outra" {{ old('tipo_deficiencia', $candidato->tipo_deficiencia ?? '') == 'outra' ? 'selected' : '' }}>Outra</option>
                                                </select>
                                            </div>

                                            {{-- Necessidades de acessibilidade --}}
                                            <div class="mt-4" x-show="isPcd" x-transition>
                                                <label for="acessibilidade" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Necessidades de acessibilidade
                                                </label>
                                                <textarea id="acessibilidade" name="acessibilidade" rows="3" maxlength="500"
                                                    x-bind:disabled="!isPcd"
                                                    aria-describedby="ajuda-acess"
                                                    class="mt-1 block w-full rounded-md sm:text-sm
                                                        border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white
                                                        focus:border-blue-500 focus:ring-blue-500
                                                        disabled:opacity-50 disabled:cursor-not-allowed">{{ old('acessibilidade', $candidato->acessibilidade ?? '') }}</textarea>
                                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" id="ajuda-acess">
                                                    Ex: Interprete de Libras, rampa de acesso, leitor de tela, tempo adicional em provas.
                                                </p>
                                            </div>
                                        </fieldset>

                                        {{-- Botao submit --}}
                                        <div class="pt-2">
                                            <button type="submit"
                                                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition text-base">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Enviar candidatura
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            @else
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Esta area e exclusiva para candidatos.
                                </p>
                            @endif
                        @else
                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                                <p class="text-gray-700 dark:text-gray-300 mb-3">
                                    Para se candidatar a esta vaga, voce precisa ter uma conta.
                                </p>
                                <div class="flex flex-wrap gap-3">
                                    <a href="{{ route('login') }}"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                        Entrar
                                    </a>
                                    <a href="{{ route('register') }}"
                                        class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-600 text-gray-700 dark:text-gray-200 text-sm font-medium rounded-md border border-gray-300 dark:border-gray-500 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                        Criar conta
                                    </a>
                                </div>
                            </div>
                        @endauth
                    </section>
                </div>
            </article>

            {{-- Voltar --}}
            <div class="mt-6">
                <a href="{{ route('usuario.vagas.index') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Voltar para vagas
                </a>
            </div>
        </div>
    </div>
</x-usuario.layouts.app>
