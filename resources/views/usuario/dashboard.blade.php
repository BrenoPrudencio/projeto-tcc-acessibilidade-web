<x-usuario.layouts.app>
    @section('title', 'Meu Painel')

    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Meu Painel
        </h1>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Boas-vindas --}}
            <section aria-label="Boas-vindas">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Olá, {{ auth()->user()->name }}!
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Bem-vindo(a) ao seu painel. Aqui você acompanha suas candidaturas e gerencia seu perfil.
                    </p>
                </div>
            </section>

            {{-- Cards de resumo --}}
            <section aria-labelledby="resumo-titulo">
                <h2 id="resumo-titulo" class="sr-only">Resumo das candidaturas</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 flex items-center gap-4">
                        <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900 rounded-full p-3" aria-hidden="true">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalCandidaturas }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Candidatura{{ $totalCandidaturas !== 1 ? 's' : '' }}</p>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 flex items-center gap-4">
                        <div class="flex-shrink-0 bg-green-100 dark:bg-green-900 rounded-full p-3" aria-hidden="true">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $candidato && $candidato->pcd ? 'PcD cadastrado' : 'Perfil ativo' }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $candidato && $candidato->telefone ? $candidato->telefone_formatado : 'Sem telefone' }}
                            </p>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 flex items-center gap-4">
                        <div class="flex-shrink-0 bg-purple-100 dark:bg-purple-900 rounded-full p-3" aria-hidden="true">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <a href="{{ route('usuario.vagas.index') }}"
                               class="text-sm font-medium text-purple-600 dark:text-purple-400 hover:underline focus:outline-none focus:ring-2 focus:ring-purple-500 rounded">
                                Ver vagas disponíveis
                            </a>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Novas oportunidades</p>
                        </div>
                    </div>

                </div>
            </section>

            {{-- Candidaturas recentes --}}
            <section aria-labelledby="recentes-titulo">
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h2 id="recentes-titulo" class="text-base font-semibold text-gray-900 dark:text-gray-100">
                            Candidaturas Recentes
                        </h2>
                        @if($totalCandidaturas > 0)
                            <a href="{{ route('usuario.candidaturas.index') }}"
                               class="text-sm text-blue-600 dark:text-blue-400 hover:underline focus:outline-none focus:ring-2 focus:ring-blue-500 rounded">
                                Ver todas
                            </a>
                        @endif
                    </div>

                    @if($candidaturas->isEmpty())
                        <div class="p-6 text-center" role="status">
                            <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Você ainda não se candidatou a nenhuma vaga.</p>
                            <a href="{{ route('usuario.vagas.index') }}"
                               class="mt-3 inline-block text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                Explorar vagas disponíveis
                            </a>
                        </div>
                    @else
                        <ul role="list" class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($candidaturas as $vaga)
                                <li role="listitem" class="p-4 sm:p-6 flex justify-between items-start gap-4">
                                    <div class="min-w-0 flex-1">
                                        <a href="{{ route('usuario.vagas.show', $vaga->id) }}"
                                           class="text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-blue-600 dark:hover:text-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded truncate block">
                                            {{ $vaga->titulo }}
                                        </a>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            @if($vaga->pivot->created_at)
                                                <time datetime="{{ $vaga->pivot->created_at->toISOString() }}">
                                                    Candidatura em {{ $vaga->pivot->created_at->format('d/m/Y') }}
                                                </time>
                                            @else
                                                <span>Candidatura registrada</span>
                                            @endif
                                        </p>
                                    </div>
                                    <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        {{ $vaga->status === 'ativa' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' }}">
                                        {{ ucfirst($vaga->status) }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </section>

            {{-- Ações rápidas --}}
            <section aria-labelledby="acoes-titulo">
                <h2 id="acoes-titulo" class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">
                    Ações rápidas
                </h2>
                <nav aria-label="Ações do painel">
                    <ul role="list" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <li role="listitem">
                            <a href="{{ route('usuario.candidaturas.index') }}"
                               class="flex items-center gap-3 p-4 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <svg class="w-5 h-5 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Minhas Candidaturas</span>
                            </a>
                        </li>
                        <li role="listitem">
                            <a href="{{ route('usuario.perfil.edit') }}"
                               class="flex items-center gap-3 p-4 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Editar Meu Perfil</span>
                            </a>
                        </li>
                        <li role="listitem">
                            <a href="{{ route('usuario.acessibilidade') }}"
                               class="flex items-center gap-3 p-4 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <svg class="w-5 h-5 text-purple-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Preferências de Acessibilidade</span>
                            </a>
                        </li>
                        <li role="listitem">
                            <a href="{{ route('usuario.vagas.index') }}"
                               class="flex items-center gap-3 p-4 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <svg class="w-5 h-5 text-orange-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Buscar Vagas</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </section>

        </div>
    </div>
</x-usuario.layouts.app>
