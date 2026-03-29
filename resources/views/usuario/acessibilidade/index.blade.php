<x-usuario.layouts.app>
    @section('title', 'Preferências de Acessibilidade')

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
                <li aria-current="page" class="text-gray-900 dark:text-gray-100 font-medium">Acessibilidade</li>
            </ol>
        </nav>
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Preferências de Acessibilidade
        </h1>
    </x-slot>

    <div class="py-8" x-data="acessibilidade()">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Feedback de salvo --}}
            <div role="status" aria-live="polite" aria-atomic="true"
                 x-show="salvo" x-transition
                 class="rounded-md bg-green-50 dark:bg-green-900/30 p-4 border border-green-200 dark:border-green-800">
                <p class="text-sm font-medium text-green-800 dark:text-green-200">
                    Preferências salvas com sucesso!
                </p>
            </div>

            {{-- Tamanho da fonte --}}
            <section class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden" aria-labelledby="fonte-titulo">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 id="fonte-titulo" class="text-base font-semibold text-gray-900 dark:text-gray-100">
                        Tamanho do texto
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Ajuste o tamanho da fonte para facilitar a leitura.
                    </p>
                </div>
                <div class="p-6">
                    <fieldset>
                        <legend class="sr-only">Selecione o tamanho do texto</legend>
                        <div class="flex flex-wrap gap-3" role="radiogroup" aria-labelledby="fonte-titulo">
                            <label class="relative flex-1 min-w-[100px]">
                                <input type="radio" name="fontSize" value="normal" x-model="prefs.fontSize"
                                    class="sr-only peer"
                                    @change="aplicar">
                                <span class="flex flex-col items-center justify-center p-4 border-2 rounded-lg cursor-pointer transition
                                    border-gray-200 dark:border-gray-700 hover:border-blue-400
                                    peer-checked:border-blue-600 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/30
                                    focus-within:ring-2 focus-within:ring-blue-500">
                                    <span class="text-base font-medium text-gray-700 dark:text-gray-300">Aa</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">Normal</span>
                                </span>
                            </label>
                            <label class="relative flex-1 min-w-[100px]">
                                <input type="radio" name="fontSize" value="large" x-model="prefs.fontSize"
                                    class="sr-only peer"
                                    @change="aplicar">
                                <span class="flex flex-col items-center justify-center p-4 border-2 rounded-lg cursor-pointer transition
                                    border-gray-200 dark:border-gray-700 hover:border-blue-400
                                    peer-checked:border-blue-600 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/30
                                    focus-within:ring-2 focus-within:ring-blue-500">
                                    <span class="text-lg font-medium text-gray-700 dark:text-gray-300">Aa</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">Grande</span>
                                </span>
                            </label>
                            <label class="relative flex-1 min-w-[100px]">
                                <input type="radio" name="fontSize" value="xlarge" x-model="prefs.fontSize"
                                    class="sr-only peer"
                                    @change="aplicar">
                                <span class="flex flex-col items-center justify-center p-4 border-2 rounded-lg cursor-pointer transition
                                    border-gray-200 dark:border-gray-700 hover:border-blue-400
                                    peer-checked:border-blue-600 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/30
                                    focus-within:ring-2 focus-within:ring-blue-500">
                                    <span class="text-2xl font-medium text-gray-700 dark:text-gray-300">Aa</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">Extra grande</span>
                                </span>
                            </label>
                        </div>
                    </fieldset>
                </div>
            </section>

            {{-- Modo escuro --}}
            <section class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden" aria-labelledby="tema-titulo">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 id="tema-titulo" class="text-base font-semibold text-gray-900 dark:text-gray-100">
                        Tema de cores
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Escolha entre o tema claro e escuro.
                    </p>
                </div>
                <div class="p-6">
                    <fieldset>
                        <legend class="sr-only">Selecione o tema de cores</legend>
                        <div class="flex flex-wrap gap-3">
                            <label class="relative flex-1 min-w-[120px]">
                                <input type="radio" name="theme" value="light" x-model="prefs.theme"
                                    class="sr-only peer"
                                    @change="aplicar">
                                <span class="flex items-center gap-3 p-4 border-2 rounded-lg cursor-pointer transition
                                    border-gray-200 dark:border-gray-700 hover:border-blue-400
                                    peer-checked:border-blue-600 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/30
                                    focus-within:ring-2 focus-within:ring-blue-500">
                                    <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Claro</span>
                                </span>
                            </label>
                            <label class="relative flex-1 min-w-[120px]">
                                <input type="radio" name="theme" value="dark" x-model="prefs.theme"
                                    class="sr-only peer"
                                    @change="aplicar">
                                <span class="flex items-center gap-3 p-4 border-2 rounded-lg cursor-pointer transition
                                    border-gray-200 dark:border-gray-700 hover:border-blue-400
                                    peer-checked:border-blue-600 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/30
                                    focus-within:ring-2 focus-within:ring-blue-500">
                                    <svg class="w-5 h-5 text-indigo-400" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Escuro</span>
                                </span>
                            </label>
                        </div>
                    </fieldset>
                </div>
            </section>

            {{-- Reduzir animações --}}
            <section class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden" aria-labelledby="motion-titulo">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 id="motion-titulo" class="text-base font-semibold text-gray-900 dark:text-gray-100">
                        Animações e movimento
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Reduzir animações pode ajudar pessoas sensíveis a movimento.
                    </p>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <label for="reduceMotion" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Reduzir animações
                            </label>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                Desativa transições e animações na interface.
                            </p>
                        </div>
                        <button type="button" role="switch"
                            id="reduceMotion"
                            x-on:click="prefs.reduceMotion = !prefs.reduceMotion; aplicar()"
                            :aria-checked="prefs.reduceMotion.toString()"
                            :class="prefs.reduceMotion
                                ? 'bg-blue-600'
                                : 'bg-gray-200 dark:bg-gray-700'"
                            class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent
                                transition-colors duration-200 ease-in-out
                                focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span class="sr-only">Reduzir animações</span>
                            <span :class="prefs.reduceMotion ? 'translate-x-5' : 'translate-x-0'"
                                class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </section>

            {{-- Botão salvar --}}
            <div class="flex justify-end">
                <button type="button"
                    x-on:click="salvar()"
                    class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                    Salvar preferências
                </button>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        function acessibilidade() {
            return {
                salvo: false,
                prefs: {
                    fontSize:     localStorage.getItem('a11y_fontSize')     || 'normal',
                    theme:        localStorage.getItem('theme')             || 'light',
                    reduceMotion: localStorage.getItem('a11y_reduceMotion') === 'true',
                },
                aplicar() {
                    // Tamanho de fonte
                    const tamanhos = { normal: '100%', large: '112.5%', xlarge: '125%' };
                    document.documentElement.style.fontSize = tamanhos[this.prefs.fontSize] || '100%';

                    // Tema claro/escuro
                    document.documentElement.classList.toggle('dark', this.prefs.theme === 'dark');

                    // Reduzir animações
                    document.documentElement.classList.toggle('reduce-motion', this.prefs.reduceMotion);
                },
                salvar() {
                    localStorage.setItem('a11y_fontSize',     this.prefs.fontSize);
                    localStorage.setItem('theme',             this.prefs.theme);
                    localStorage.setItem('a11y_reduceMotion', this.prefs.reduceMotion);
                    this.aplicar();
                    this.salvo = true;
                    setTimeout(() => { this.salvo = false; }, 3000);
                }
            }
        }
    </script>
    @endpush
</x-usuario.layouts.app>
