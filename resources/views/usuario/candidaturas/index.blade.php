<x-usuario.layouts.app>
    @section('title', 'Minhas Candidaturas')

    <x-slot name="header">
        <nav aria-label="Breadcrumb" class="mb-2">
            <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                <li>
                    <a href="{{ route('usuario.dashboard') }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                        Meu Painel
                    </a>
                </li>
                <li aria-hidden="true">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                </li>
                <li aria-current="page" class="text-gray-900 dark:text-gray-100 font-medium">
                    Minhas Candidaturas
                </li>
            </ol>
        </nav>
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Minhas Candidaturas
        </h1>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div role="alert" aria-live="assertive" class="mb-6 rounded-md bg-green-50 dark:bg-green-900/30 p-4 border border-green-200 dark:border-green-800">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">

                {{-- Cabeçalho com contador --}}
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <p aria-live="polite" class="text-sm text-gray-600 dark:text-gray-400">
                        @if($candidaturas instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{ $candidaturas->total() }} candidatura{{ $candidaturas->total() !== 1 ? 's' : '' }} encontrada{{ $candidaturas->total() !== 1 ? 's' : '' }}
                        @else
                            Nenhuma candidatura encontrada
                        @endif
                    </p>
                </div>

                @if($candidaturas->isEmpty())
                    <div class="p-12 text-center" role="status">
                        <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">Você ainda não se candidatou a nenhuma vaga.</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Explore as oportunidades disponíveis e envie sua candidatura.</p>
                        <a href="{{ route('usuario.vagas.index') }}"
                           class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                            Ver vagas disponíveis
                        </a>
                    </div>
                @else
                    <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($candidaturas as $vaga)
                            <li role="listitem" class="p-5 sm:p-6"
                                x-data="{ confirmando: false }"
                                id="candidatura-{{ $vaga->id }}">

                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                                    <div class="min-w-0 flex-1">
                                        {{-- Título da vaga --}}
                                        <a href="{{ route('usuario.vagas.show', $vaga->id) }}"
                                           class="text-base font-semibold text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded">
                                            {{ $vaga->titulo }}
                                        </a>

                                        {{-- Metadados --}}
                                        <div class="mt-2 flex flex-wrap items-center gap-3">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($vaga->tipo_contratacao === 'CLT') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($vaga->tipo_contratacao === 'PJ') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @else bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 @endif">
                                                {{ $vaga->tipo_contratacao }}
                                            </span>

                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $vaga->status === 'ativa' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                                                {{ ucfirst($vaga->status) }}
                                            </span>

                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                @if($vaga->pivot->created_at)
                                                    Candidatura em
                                                    <time datetime="{{ $vaga->pivot->created_at->toISOString() }}">
                                                        {{ $vaga->pivot->created_at->format('d/m/Y \à\s H:i') }}
                                                    </time>
                                                @else
                                                    Candidatura registrada
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Ações --}}
                                    <div class="flex items-center gap-2 shrink-0">
                                        <a href="{{ route('usuario.vagas.show', $vaga->id) }}"
                                           class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline focus:outline-none focus:ring-2 focus:ring-blue-500 rounded"
                                           aria-label="Ver detalhes da vaga {{ $vaga->titulo }}">
                                            Ver vaga
                                        </a>

                                        <span class="text-gray-300 dark:text-gray-600" aria-hidden="true">|</span>

                                        <button type="button"
                                            x-on:click="confirmando = true"
                                            class="text-sm font-medium text-red-600 dark:text-red-400 hover:underline focus:outline-none focus:ring-2 focus:ring-red-500 rounded"
                                            aria-label="Cancelar candidatura para {{ $vaga->titulo }}"
                                            :aria-expanded="confirmando.toString()">
                                            Cancelar
                                        </button>
                                    </div>
                                </div>

                                {{-- Confirmação inline de cancelamento --}}
                                <div x-show="confirmando"
                                     x-transition
                                     class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg"
                                     role="alert"
                                     aria-live="assertive"
                                     id="confirmar-{{ $vaga->id }}">
                                    <p class="text-sm font-medium text-red-800 dark:text-red-200">
                                        Tem certeza que deseja cancelar sua candidatura para <strong>{{ $vaga->titulo }}</strong>? Esta ação não pode ser desfeita.
                                    </p>
                                    <div class="mt-3 flex gap-3">
                                        <form action="{{ route('usuario.candidaturas.destroy', $vaga->pivot->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                                                Confirmar cancelamento
                                            </button>
                                        </form>
                                        <button type="button"
                                            x-on:click="confirmando = false"
                                            class="inline-flex items-center px-3 py-1.5 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-md border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition">
                                            Voltar
                                        </button>
                                    </div>
                                </div>

                            </li>
                        @endforeach
                    </ul>

                    {{-- Paginação --}}
                    @if($candidaturas instanceof \Illuminate\Pagination\LengthAwarePaginator && $candidaturas->hasPages())
                        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                            <nav aria-label="Paginação de candidaturas">
                                {{ $candidaturas->links() }}
                            </nav>
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-usuario.layouts.app>
