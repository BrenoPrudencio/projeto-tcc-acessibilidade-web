<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Candidato') }}
        </h2>
    </x-slot>

    @push('styles')
    <style>
        .hc-mode, .hc-mode * { background-color: #000 !important; color: #fff !important; border-color: #fff !important; }
        .hc-mode label, .hc-mode span { color: #fff !important; }
        .hc-mode a, .hc-mode button { text-decoration: underline !important; color: #ff0 !important; }
        .required-indicator { color: #dc2626; }
    </style>
    @endpush

    <div class="py-12" x-data="{ 
        highContrast: false,
        isPcd: {{ old('pcd', $candidato->pcd) ? 'true' : 'false' }},
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
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                        <h3 class="text-lg font-medium">Atualizar dados do candidato</h3>
                        <button type="button" @click="toggleContrast()" class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 shadow-sm text-xs font-medium rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Alto contraste
                        </button>
                    </div>

                    @if ($errors->any())
                        <div class="mb-5 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded relative" role="alert" aria-live="assertive">
                            <strong class="font-bold">Há problemas no formulário:</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mb-5 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded relative" role="status" aria-live="polite">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <form action="{{ route('candidatos.update', $candidato->id) }}" method="POST" novalidate class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nome Completo <span class="required-indicator" aria-hidden="true">*</span>
                            </label>
                            <input type="text" id="nome" name="nome" value="{{ old('nome', $candidato->nome) }}" required aria-required="true" autocomplete="name"
                                @error('nome') aria-describedby="erro-nome" @enderror
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('nome') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                            @error('nome')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400" id="erro-nome">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Email <span class="required-indicator" aria-hidden="true">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email', $candidato->email) }}" required aria-required="true" autocomplete="email" inputmode="email"
                                @error('email') aria-describedby="erro-email" @enderror
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('email') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400" id="erro-email">{{ $message }}</p>
                            @enderror
                        </div>

                        <div x-data x-init="IMask($refs.telefone, { mask: '(00) 00000-0000' })">
                            <label for="telefone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Telefone <span class="required-indicator" aria-hidden="true">*</span>
                                <span class="sr-only">(Formato: (99) 99999-9999)</span>
                            </label>
                            <input type="tel" id="telefone" name="telefone" x-ref="telefone" value="{{ old('telefone', $candidato->telefone_formatado ?? $candidato->telefone) }}" required aria-required="true" inputmode="tel" placeholder="(99) 99999-9999"
                                aria-describedby="ajuda-telefone @error('telefone') erro-telefone @enderror"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('telefone') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" id="ajuda-telefone">Formato esperado: (11) 98888-7777</p>
                            @error('telefone')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400" id="erro-telefone">{{ $message }}</p>
                            @enderror
                        </div>

                        <fieldset class="border border-gray-200 dark:border-gray-700 rounded-md p-4 bg-gray-50 dark:bg-gray-800/50">
                            <legend class="text-base font-medium text-gray-900 dark:text-gray-100 px-2">Informações de Acessibilidade (opcional)</legend>
                            
                            <div class="mt-4 flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" id="pcd" name="pcd" value="1" x-model="isPcd" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 dark:bg-gray-700 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="pcd" class="font-medium text-gray-700 dark:text-gray-300">Sou Pessoa com Deficiência (PCD)</label>
                                </div>
                            </div>

                            <div class="mt-4">
                                <label for="tipo_deficiencia" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de deficiência (opcional)</label>
                                <select id="tipo_deficiencia" name="tipo_deficiencia" x-bind:disabled="!isPcd" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md disabled:opacity-50 disabled:bg-gray-100 disabled:cursor-not-allowed dark:disabled:bg-gray-900">
                                    <option value="">Selecione...</option>
                                    <option value="visual" {{ old('tipo_deficiencia', $candidato->tipo_deficiencia) === 'visual' ? 'selected' : '' }}>Visual</option>
                                    <option value="auditiva" {{ old('tipo_deficiencia', $candidato->tipo_deficiencia) === 'auditiva' ? 'selected' : '' }}>Auditiva</option>
                                    <option value="motora" {{ old('tipo_deficiencia', $candidato->tipo_deficiencia) === 'motora' ? 'selected' : '' }}>Motora</option>
                                    <option value="intelectual" {{ old('tipo_deficiencia', $candidato->tipo_deficiencia) === 'intelectual' ? 'selected' : '' }}>Intelectual</option>
                                    <option value="autismo" {{ old('tipo_deficiencia', $candidato->tipo_deficiencia) === 'autismo' ? 'selected' : '' }}>TEA</option>
                                    <option value="outra" {{ old('tipo_deficiencia', $candidato->tipo_deficiencia) === 'outra' ? 'selected' : '' }}>Outra</option>
                                </select>
                            </div>

                            <div class="mt-4">
                                <label for="acessibilidade" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Necessidades de acessibilidade (opcional)</label>
                                <textarea id="acessibilidade" name="acessibilidade" rows="2" maxlength="500" x-bind:disabled="!isPcd" aria-describedby="ajuda-acess" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm disabled:opacity-50 disabled:bg-gray-100 disabled:cursor-not-allowed dark:disabled:bg-gray-900">{{ old('acessibilidade', $candidato->acessibilidade) }}</textarea>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" id="ajuda-acess">Ex: Intérprete de Libras, rampa, leitor de tela, tempo adicional.</p>
                            </div>
                        </fieldset>

                        <div class="flex items-center justify-end pt-4 border-t border-gray-200 dark:border-gray-700 space-x-3">
                            <a href="{{ route('candidatos.index') }}" class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2 px-4 text-sm font-medium text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Atualizar Candidato
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/imask"></script>
    @endpush
</x-app-layout>