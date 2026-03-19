<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalhes da Vaga') }}: {{ $vaga->titulo }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded relative"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded relative"
                    role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold mb-4">{{ $vaga->titulo }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400">Tipo de
                                Contratação</span>
                            <span class="mt-1 text-base">{{ $vaga->tipo_contratacao }}</span>
                        </div>
                        <div>
                            <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Status</span>
                            <span
                                class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $vaga->status == 'ativa' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200' }}">
                                {{ ucfirst($vaga->status) }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h4 class="text-lg font-medium mb-2 border-b border-gray-200 dark:border-gray-700 pb-2">
                            Descrição da Vaga</h4>
                        <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                            <p>{!! nl2br(e($vaga->descricao)) !!}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-bold mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                        Candidatos Inscritos ({{ $vaga->candidatos->count() }})
                    </h3>

                    @if($vaga->candidatos->isNotEmpty())
                        <ul
                            class="divide-y divide-gray-200 dark:divide-gray-700 border border-gray-200 dark:border-gray-700 rounded-md">
                            @foreach ($vaga->candidatos as $inscrito)
                                <li
                                    class="p-4 flex justify-between items-center bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $inscrito->nome }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $inscrito->email }}</p>
                                    </div>

                                    <button type="button" x-data
                                        x-on:click.prevent="$dispatch('open-modal', 'cancel-modal-{{ $inscrito->id }}')"
                                        aria-label="Cancelar inscrição de {{ $inscrito->nome }}"
                                        class="inline-flex items-center p-2 border border-transparent rounded-full shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                        title="Cancelar Inscrição">
                                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>

                                    <form
                                        action="{{ route('vagas.cancelarInscricao', ['vaga' => $vaga->id, 'candidato' => $inscrito->id]) }}"
                                        method="POST" id="cancel-form-{{ $inscrito->id }}">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                    <x-confirm-modal name="cancel-modal-{{ $inscrito->id }}" title="Cancelar Inscrição">
                                        Tem certeza que deseja cancelar a inscrição do(a) candidato(a)
                                        <strong>{{ $inscrito->nome }}</strong> nesta vaga?
                                        <x-slot name="actions">
                                            <button type="button"
                                                onclick="document.getElementById('cancel-form-{{ $inscrito->id }}').submit()"
                                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                Confirmar Cancelamento
                                            </button>
                                            <button type="button"
                                                x-on:click="$dispatch('close-modal', 'cancel-modal-{{ $inscrito->id }}')"
                                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                                Voltar
                                            </button>
                                        </x-slot>
                                    </x-confirm-modal>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p
                            class="text-gray-500 dark:text-gray-400 py-4 text-center border border-dashed border-gray-300 dark:border-gray-700 rounded-md">
                            Nenhum candidato inscrito nesta vaga ainda.</p>
                    @endif
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-bold mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                        Inscrever Novo Candidato
                    </h3>

                    @if($vaga->status == 'ativa')
                        <form action="{{ route('vagas.inscrever', $vaga->id) }}" method="POST" class="mt-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                <div class="md:col-span-3">
                                    <label for="candidato_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Selecione o
                                        Candidato <span class="text-red-500" aria-hidden="true">*</span></label>
                                    <select id="candidato_id" name="candidato_id" required aria-required="true"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                        <option value="" disabled selected>-- Escolha um candidato --</option>
                                        @foreach ($candidatos as $candidato)
                                            <option value="{{ $candidato->id }}">{{ $candidato->nome }}
                                                ({{ $candidato->email }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-1">
                                    <button type="submit"
                                        class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Inscrever
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div
                            class="bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-400 dark:border-yellow-600 p-4 mt-2">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400 dark:text-yellow-500" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                        As inscrições para esta vaga estão pausadas no momento.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="pt-4">
                <a href="{{ route('vagas.index') }}"
                    class="inline-flex items-center text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                    <svg class="mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Voltar para a Lista de Vagas
                </a>
            </div>

        </div>
    </div>
</x-app-layout>