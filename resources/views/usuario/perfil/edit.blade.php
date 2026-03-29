<x-usuario.layouts.app>
    @section('title', 'Meu Perfil')

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
                <li aria-current="page" class="text-gray-900 dark:text-gray-100 font-medium">Meu Perfil</li>
            </ol>
        </nav>
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Meu Perfil
        </h1>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div role="alert" aria-live="assertive" class="rounded-md bg-green-50 dark:bg-green-900/30 p-4 border border-green-200 dark:border-green-800">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden"
                 x-data="{ isPcd: {{ old('pcd', $candidato->pcd ?? false) ? 'true' : 'false' }} }">

                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                        Dados pessoais
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Campos com <span class="text-red-500" aria-hidden="true">*</span><span class="sr-only">asterisco</span> são obrigatórios.
                    </p>
                </div>

                @if($errors->any())
                    <div class="mx-6 mt-6 rounded-md bg-red-50 dark:bg-red-900/30 border border-red-300 dark:border-red-700 p-4"
                         role="alert" aria-live="assertive">
                        <p class="font-semibold text-sm text-red-800 dark:text-red-200">Corrija os erros abaixo:</p>
                        <ul class="mt-2 list-disc list-inside text-sm text-red-700 dark:text-red-300">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('usuario.perfil.update') }}" novalidate class="p-6 space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Nome --}}
                    <div>
                        <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nome completo <span class="text-red-500" aria-hidden="true">*</span>
                        </label>
                        <input type="text" id="nome" name="nome"
                            value="{{ old('nome', $candidato->nome ?? $user->name) }}"
                            required aria-required="true"
                            aria-describedby="@error('nome') erro-nome @enderror"
                            class="mt-1 block w-full rounded-md shadow-sm sm:text-sm
                                border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white
                                focus:border-blue-500 focus:ring-blue-500
                                @error('nome') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                        @error('nome')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400" id="erro-nome">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email (somente leitura) --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            E-mail
                        </label>
                        <input type="email" id="email" name="email"
                            value="{{ $user->email }}"
                            readonly disabled
                            aria-describedby="ajuda-email"
                            class="mt-1 block w-full rounded-md shadow-sm sm:text-sm
                                border-gray-300 dark:border-gray-600 dark:bg-gray-600 dark:text-gray-400
                                bg-gray-50 cursor-not-allowed opacity-75">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" id="ajuda-email">
                            Para alterar o e-mail, acesse
                            <a href="{{ route('profile.edit') }}" class="text-blue-600 dark:text-blue-400 hover:underline focus:outline-none focus:ring-2 focus:ring-blue-500 rounded">
                                Configurações da conta
                            </a>.
                        </p>
                    </div>

                    {{-- Telefone --}}
                    <div>
                        <label for="telefone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Telefone para contato
                        </label>
                        <input type="tel" id="telefone" name="telefone"
                            value="{{ old('telefone', $candidato->telefone_formatado ?? '') }}"
                            inputmode="tel"
                            placeholder="(99) 99999-9999"
                            aria-describedby="ajuda-telefone @error('telefone') erro-telefone @enderror"
                            class="mt-1 block w-full rounded-md shadow-sm sm:text-sm
                                border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white
                                focus:border-blue-500 focus:ring-blue-500
                                @error('telefone') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" id="ajuda-telefone">Formato: (11) 98888-7777</p>
                        @error('telefone')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400" id="erro-telefone">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Seção PcD --}}
                    <fieldset class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-800/50">
                        <legend class="text-base font-medium text-gray-900 dark:text-gray-100 px-2">
                            Informações de Acessibilidade
                        </legend>

                        <div class="mt-3 flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" id="pcd" name="pcd" value="1"
                                    x-model="isPcd"
                                    {{ old('pcd', $candidato->pcd ?? false) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700 rounded focus:ring-blue-500">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="pcd" class="font-medium text-gray-700 dark:text-gray-300">
                                    Sou Pessoa com Deficiência (PcD)
                                </label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    Marque para indicar seu tipo de deficiência e necessidades de acessibilidade.
                                </p>
                            </div>
                        </div>

                        <div class="mt-4" x-show="isPcd" x-transition>
                            <label for="tipo_deficiencia" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tipo de deficiência
                            </label>
                            <select id="tipo_deficiencia" name="tipo_deficiencia"
                                x-bind:disabled="!isPcd"
                                aria-describedby="ajuda-tipo"
                                class="mt-1 block w-full rounded-md sm:text-sm
                                    border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white
                                    focus:border-blue-500 focus:ring-blue-500
                                    disabled:opacity-50 disabled:cursor-not-allowed">
                                <option value="">Selecione...</option>
                                <option value="visual"      {{ old('tipo_deficiencia', $candidato->tipo_deficiencia ?? '') == 'visual'      ? 'selected' : '' }}>Visual</option>
                                <option value="auditiva"    {{ old('tipo_deficiencia', $candidato->tipo_deficiencia ?? '') == 'auditiva'    ? 'selected' : '' }}>Auditiva</option>
                                <option value="motora"      {{ old('tipo_deficiencia', $candidato->tipo_deficiencia ?? '') == 'motora'      ? 'selected' : '' }}>Motora</option>
                                <option value="intelectual" {{ old('tipo_deficiencia', $candidato->tipo_deficiencia ?? '') == 'intelectual' ? 'selected' : '' }}>Intelectual</option>
                                <option value="autismo"     {{ old('tipo_deficiencia', $candidato->tipo_deficiencia ?? '') == 'autismo'     ? 'selected' : '' }}>TEA (Transtorno do Espectro Autista)</option>
                                <option value="outra"       {{ old('tipo_deficiencia', $candidato->tipo_deficiencia ?? '') == 'outra'       ? 'selected' : '' }}>Outra</option>
                            </select>
                        </div>

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
                                Ex: Intérprete de Libras, rampa de acesso, leitor de tela, tempo adicional em provas. (máx. 500 caracteres)
                            </p>
                        </div>
                    </fieldset>

                    {{-- Botões --}}
                    <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('usuario.dashboard') }}"
                           class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                            Salvar alterações
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-usuario.layouts.app>
