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
                    <section aria-labelledby="candidatura-titulo">
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
                                    <form method="POST" action="{{ route('usuario.candidaturas.store') }}">
                                        @csrf
                                        <input type="hidden" name="vaga_id" value="{{ $vaga->id }}">
                                        <button type="submit"
                                            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition text-base">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Candidatar-me a esta vaga
                                        </button>
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
