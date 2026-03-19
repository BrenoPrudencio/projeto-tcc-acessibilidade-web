<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lista de Candidatos') }}
        </h2>
    </x-slot>

    @push('styles')
        <style>
            .hc-mode,
            .hc-mode * {
                background-color: #000 !important;
                color: #fff !important;
                border-color: #fff !important;
            }

            .hc-mode a,
            .hc-mode button {
                text-decoration: underline !important;
                color: #ff0 !important;
            }

            .required-indicator {
                color: #dc2626;
            }
        </style>
    @endpush

    <div class="py-12" x-data="{
        selectAll: false,
        highContrast: false,
        toggleAll() {
            let checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => cb.checked = this.selectAll);
        },
        toggleContrast() {
            this.highContrast = !this.highContrast;
            if(this.highContrast) {
                document.documentElement.classList.add('hc-mode');
            } else {
                document.documentElement.classList.remove('hc-mode');
            }
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded relative"
                    role="alert" aria-live="polite">
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <button type="button" class="absolute top-0 bottom-0 right-0 px-4 py-3"
                        onclick="this.parentElement.style.display='none'" aria-label="Fechar">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20">
                            <title>Close</title>
                            <path
                                d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                        </svg>
                    </button>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        Filtros de Busca
                    </h3>

                    <form action="{{ route('candidatos.index') }}" method="GET" id="filter-form">
                        <input type="hidden" name="per_page" id="per_page_input" value="{{ request('per_page', 20) }}">

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            <div class="col-span-1 md:col-span-2">
                                <label for="search"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar por
                                    Nome ou Email</label>
                                <input type="text" id="search" name="search" value="{{ request('search') }}"
                                    placeholder="Digite um nome ou email..."
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                            </div>

                            <div>
                                <label for="pcd"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">PCD</label>
                                <select id="pcd" name="pcd"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">Todos</option>
                                    <option value="1" @selected(request('pcd') === '1')>Sim</option>
                                    <option value="0" @selected(request('pcd') === '0')>Não</option>
                                </select>
                            </div>

                            <div class="col-span-1 md:col-span-4 flex justify-end space-x-2 mt-2">
                                <a href="{{ route('candidatos.index') }}"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Limpar
                                </a>
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Filtrar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <form action="{{ route('candidatos.destroy.mass') }}" method="POST" id="bulk-actions-form" x-ref="bulkForm">
                @csrf
                @method('DELETE')

                <div
                    class="flex flex-col sm:flex-row justify-between items-end sm:items-center mb-4 space-y-4 sm:space-y-0">
                    <div class="flex flex-wrap gap-2 items-center">
                        <button type="button" @click="toggleContrast()"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Alternar Alto Contraste
                        </button>
                        <a href="{{ route('candidatos.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Novo Candidato
                        </a>
                        <button type="button" x-on:click="$dispatch('open-modal', 'bulk-delete-modal')"
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest shadow-sm hover:bg-red-500 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 active:bg-red-600 disabled:opacity-25 transition">
                            Excluir Selecionados
                        </button>
                    </div>

                    <div class="flex items-center">
                        <label for="per_page_select"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mr-2 mb-0">Itens por
                            página:</label>
                        <select id="per_page_select"
                            class="block w-24 pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                            onchange="document.getElementById('per_page_input').value = this.value; document.getElementById('filter-form').submit();">
                            <option value="10" @if(request('per_page') == 10) selected @endif>10</option>
                            <option value="20" @if(request('per_page', 20) == 20) selected @endif>20</option>
                            <option value="50" @if(request('per_page') == 50) selected @endif>50</option>
                        </select>
                    </div>
                </div>

                <x-confirm-modal name="bulk-delete-modal" title="Excluir Candidatos Selecionados">
                    Tem certeza que deseja excluir os candidatos selecionados? Esta ação não pode ser desfeita.
                    <x-slot name="actions">
                        <button type="button" x-on:click="$refs.bulkForm.submit()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Excluir
                        </button>
                        <button type="button" x-on:click="$dispatch('close-modal', 'bulk-delete-modal')"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </x-slot>
                </x-confirm-modal>

                <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-md mt-4">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-12">
                                        <input type="checkbox" x-model="selectAll" x-on:change="toggleAll"
                                            aria-label="Selecionar todos os candidatos"
                                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:bg-gray-700 rounded">
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        ID
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Nome
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Telefone
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        PCD
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-40">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($candidatos as $candidato)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" name="ids[]" value="{{ $candidato->id }}"
                                                aria-label="Selecionar candidato {{ $candidato->nome }}"
                                                class="row-checkbox focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:bg-gray-700 rounded">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $candidato->id }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $candidato->nome }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $candidato->email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $candidato->telefone_formatado }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($candidato->pcd)
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                                    PCD
                                                    @if($candidato->tipo_deficiencia)
                                                        <span class="sr-only">Tipo: {{ $candidato->tipo_deficiencia }}</span>
                                                    @endif
                                                </span>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500">—</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('candidatos.edit', $candidato->id) }}"
                                                class="text-yellow-600 dark:text-yellow-500 hover:text-yellow-900 dark:hover:text-yellow-400 inline-block mr-3">Editar</a>
                                            <button type="button"
                                                @click.prevent="$dispatch('open-modal', 'delete-modal-{{ $candidato->id }}')"
                                                class="text-red-600 dark:text-red-500 hover:text-red-900 dark:hover:text-red-400 inline-block bg-transparent border-0 cursor-pointer p-0 m-0">Excluir</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7"
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                            Nenhum candidato encontrado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>

            @foreach ($candidatos as $candidato)
                <form action="{{ route('candidatos.destroy', $candidato->id) }}" method="POST"
                    id="delete-form-{{ $candidato->id }}">
                    @csrf
                    @method('DELETE')
                </form>

                <x-confirm-modal name="delete-modal-{{ $candidato->id }}" title="Excluir Candidato">
                    Tem certeza que deseja excluir o candidato "{{ $candidato->nome }}"? Esta ação não poderá ser desfeita.
                    <x-slot name="actions">
                        <button type="button" onclick="document.getElementById('delete-form-{{ $candidato->id }}').submit()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Excluir
                        </button>
                        <button type="button" x-on:click="$dispatch('close-modal', 'delete-modal-{{ $candidato->id }}')"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </x-slot>
                </x-confirm-modal>
            @endforeach

            <div class="mt-4">
                {{ $candidatos->links() }}
            </div>
        </div>
    </div>
</x-app-layout>