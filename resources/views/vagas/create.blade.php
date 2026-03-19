<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Criar Nova Vaga') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3
                        class="text-lg font-medium mb-4 flex items-center border-b border-gray-200 dark:border-gray-700 pb-2">
                        Preencha os dados da nova vaga
                    </h3>

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded relative"
                            role="alert" aria-live="assertive">
                            <strong class="font-bold">Ops!</strong>
                            <span class="block sm:inline">Ocorreram alguns problemas com os dados informados.</span>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('vagas.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div>
                            <label for="titulo"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título da Vaga <span
                                    class="text-red-500" aria-hidden="true">*</span></label>
                            <input type="text" id="titulo" name="titulo" value="{{ old('titulo') }}" required
                                aria-required="true"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="descricao"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição <span
                                    class="text-red-500" aria-hidden="true">*</span></label>
                            <textarea id="descricao" name="descricao" rows="4" required aria-required="true"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('descricao') }}</textarea>
                        </div>

                        <div>
                            <label for="tipo_contratacao"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Contratação
                                <span class="text-red-500" aria-hidden="true">*</span></label>
                            <select id="tipo_contratacao" name="tipo_contratacao" required aria-required="true"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Selecione um tipo</option>
                                <option value="CLT" @if(old('tipo_contratacao') == 'CLT') selected @endif>CLT</option>
                                <option value="PJ" @if(old('tipo_contratacao') == 'PJ') selected @endif>Pessoa Jurídica
                                </option>
                                <option value="Freelancer" @if(old('tipo_contratacao') == 'Freelancer') selected @endif>
                                    Freelancer</option>
                            </select>
                        </div>

                        <div
                            class="flex items-center justify-end pt-4 border-t border-gray-200 dark:border-gray-700 space-x-3">
                            <a href="{{ route('vagas.index') }}"
                                class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2 px-4 text-sm font-medium text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Cancelar
                            </a>
                            <button type="submit"
                                class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Salvar Vaga
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>