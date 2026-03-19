<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Lista de Vagas') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        selectAll: false,
        toggleAll() {
            let checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => cb.checked = this.selectAll);
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded relative"
                    role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Filtros -->
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

                    <form action="{{ route('vagas.index') }}" method="GET" id="filter-form">
                        <input type="hidden" name="per_page" id="per_page_input" value="{{ request('per_page', 20) }}">

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            <div class="col-span-1 md:col-span-2">
                                <label for="search"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar por
                                    Título</label>
                                <input type="text" id="search" name="search" value="{{ request('search') }}"
                                    placeholder="Ex: Desenvolvedor..."
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md">
                            </div>

                            <div>
                                <label for="tipo"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                                <select id="tipo" name="tipo"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">Todos</option>
                                    <option value="CLT" @if(request('tipo') == 'CLT') selected @endif>CLT</option>
                                    <option value="PJ" @if(request('tipo') == 'PJ') selected @endif>PJ</option>
                                    <option value="Freelancer" @if(request('tipo') == 'Freelancer') selected @endif>
                                        Freelancer</option>
                                </select>
                            </div>

                            <div>
                                <label for="status"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                <select id="status" name="status"
                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">Todos</option>
                                    <option value="ativa" @if(request('status') == 'ativa') selected @endif>Ativa</option>
                                    <option value="pausada" @if(request('status') == 'pausada') selected @endif>Pausada
                                    </option>
                                </select>
                            </div>

                            <div class="col-span-1 md:col-span-4 flex justify-end space-x-2 mt-2">
                                <a href="{{ route('vagas.index') }}"
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

            <form action="{{ route('vagas.destroy.mass') }}" method="POST" id="bulk-actions-form" x-ref="bulkForm">
                @csrf
                @method('DELETE')

                <div
                    class="flex flex-col sm:flex-row justify-between items-end sm:items-center mb-4 space-y-4 sm:space-y-0">
                    <div class="flex space-x-4">
                        <a href="{{ route('vagas.create') }}"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Criar Nova Vaga
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

                <x-confirm-modal name="bulk-delete-modal" title="Excluir Selecionados">
                    Tem certeza que deseja excluir as vagas selecionadas? Esta ação não pode ser desfeita.
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
                                            aria-label="Selecionar todas as vagas"
                                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:bg-gray-700 rounded">
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-16">
                                        ID
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Título
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Tipo
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider w-40">
                                        Ações
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($vagas as $vaga)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="checkbox" name="ids[]" value="{{ $vaga->id }}"
                                                aria-label="Selecionar vaga {{ $vaga->id }}"
                                                class="row-checkbox focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:bg-gray-700 rounded">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $vaga->id }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            <a href="{{ route('vagas.show', $vaga->id) }}"
                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                                {{ $vaga->titulo }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $vaga->tipo_contratacao }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $vaga->status == 'ativa' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300' }}">
                                                {{ ucfirst($vaga->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('vagas.edit', $vaga->id) }}"
                                                class="text-yellow-600 dark:text-yellow-500 hover:text-yellow-900 dark:hover:text-yellow-400 inline-block mr-3">Editar</a>

                                            <button type="button" x-data
                                                x-on:click.prevent="$dispatch('open-modal', 'delete-modal-{{ $vaga->id }}')"
                                                class="text-red-600 dark:text-red-500 hover:text-red-900 dark:hover:text-red-400 inline-block bg-transparent border-0 cursor-pointer p-0 m-0">Excluir</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                            Nenhuma vaga encontrada com os filtros aplicados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>

            @foreach ($vagas as $vaga)
                <form action="{{ route('vagas.destroy', $vaga->id) }}" method="POST" id="delete-form-{{ $vaga->id }}">
                    @csrf
                    @method('DELETE')
                </form>

                <x-confirm-modal name="delete-modal-{{ $vaga->id }}" title="Excluir Vaga">
                    Tem certeza que deseja excluir a vaga "{{ $vaga->titulo }}"?
                    <x-slot name="actions">
                        <button type="button" onclick="document.getElementById('delete-form-{{ $vaga->id }}').submit()"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Excluir
                        </button>
                        <button type="button" x-on:click="$dispatch('close-modal', 'delete-modal-{{ $vaga->id }}')"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </x-slot>
                </x-confirm-modal>
            @endforeach

            <div class="mt-4">
                {{ $vagas->links() }}
            </div>
        </div>
    </div>
</x-app-layout>