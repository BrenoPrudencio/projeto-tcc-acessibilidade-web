<x-usuario.layouts.app>
    @section('title', 'Vagas Disponiveis')

    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Vagas Disponiveis') }}
        </h1>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Filtros de busca --}}
            <form method="GET" action="{{ route('usuario.vagas.index') }}" role="search" aria-label="Filtrar vagas">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
                    <fieldset>
                        <legend class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Filtrar vagas</legend>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Buscar por titulo
                                </label>
                                <input type="text" name="search" id="search"
                                    value="{{ request('search') }}"
                                    placeholder="Ex: Desenvolvedor, Analista..."
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tipo de contratacao
                                </label>
                                <select name="tipo" id="tipo"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Todos os tipos</option>
                                    <option value="CLT" {{ request('tipo') === 'CLT' ? 'selected' : '' }}>CLT</option>
                                    <option value="PJ" {{ request('tipo') === 'PJ' ? 'selected' : '' }}>PJ</option>
                                    <option value="Freelancer" {{ request('tipo') === 'Freelancer' ? 'selected' : '' }}>Freelancer</option>
                                </select>
                            </div>

                            <div class="flex items-end gap-2">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    Buscar
                                </button>

                                @if(request('search') || request('tipo'))
                                    <a href="{{ route('usuario.vagas.index') }}"
                                        class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-md hover:bg-gray-300 dark:hover:bg-gray-500 transition">
                                        Limpar
                                    </a>
                                @endif
                            </div>
                        </div>
                    </fieldset>
                </div>
            </form>

            {{-- Contagem de resultados (live region) --}}
            <div aria-live="polite" class="mb-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $vagas->total() }} {{ $vagas->total() === 1 ? 'vaga encontrada' : 'vagas encontradas' }}
                </p>
            </div>

            {{-- Grid de cards --}}
            @if($vagas->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" role="list" aria-label="Lista de vagas disponiveis">
                    @foreach($vagas as $vaga)
                        <article role="listitem"
                            class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden hover:shadow-md transition-shadow flex flex-col">
                            <div class="p-6 flex flex-col flex-1">
                                {{-- Tipo de contratacao (badge) --}}
                                <div class="mb-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($vaga->tipo_contratacao === 'CLT')
                                            bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                        @elseif($vaga->tipo_contratacao === 'PJ')
                                            bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                        @else
                                            bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                        @endif">
                                        {{ $vaga->tipo_contratacao }}
                                    </span>
                                </div>

                                {{-- Titulo --}}
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                    <a href="{{ route('usuario.vagas.show', $vaga) }}"
                                        class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                        {{ $vaga->titulo }}
                                    </a>
                                </h2>

                                {{-- Descricao (truncada) --}}
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 flex-1">
                                    {{ Str::limit($vaga->descricao, 120) }}
                                </p>

                                {{-- Data de publicacao --}}
                                <p class="text-xs text-gray-500 dark:text-gray-500 mb-4">
                                    <time datetime="{{ $vaga->created_at->toISOString() }}">
                                        Publicada em {{ $vaga->created_at->format('d/m/Y') }}
                                    </time>
                                </p>

                                {{-- Botao de acao --}}
                                <a href="{{ route('usuario.vagas.show', $vaga) }}"
                                    class="inline-flex items-center justify-center w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition"
                                    aria-label="Ver detalhes da vaga: {{ $vaga->titulo }}">
                                    Ver detalhes
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Paginacao --}}
                <nav aria-label="Paginacao de vagas" class="mt-8">
                    {{ $vagas->links() }}
                </nav>
            @else
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-12 text-center" role="status">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Nenhuma vaga encontrada</h2>
                    <p class="text-gray-500 dark:text-gray-400">
                        @if(request('search') || request('tipo'))
                            Tente alterar os filtros de busca.
                        @else
                            No momento nao ha vagas ativas. Volte em breve!
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-usuario.layouts.app>
