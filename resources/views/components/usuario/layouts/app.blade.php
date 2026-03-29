<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title') @yield('title') — @endif{{ config('app.name', 'Painel de Vagas') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        // Tema claro/escuro
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        // Tamanho de fonte
        const tamanhos = { normal: '100%', large: '112.5%', xlarge: '125%' };
        const fs = localStorage.getItem('a11y_fontSize') || 'normal';
        document.documentElement.style.fontSize = tamanhos[fs] || '100%';
        // Reduzir animações
        if (localStorage.getItem('a11y_reduceMotion') === 'true') {
            document.documentElement.classList.add('reduce-motion');
        }
    </script>
    <style>
        .reduce-motion *, .reduce-motion *::before, .reduce-motion *::after {
            animation-duration: 0.01ms !important;
            transition-duration: 0.01ms !important;
        }
    </style>

    @stack('styles')

    <style>
        :focus-visible {
            outline: 3px solid #0d6efd !important;
            outline-offset: 2px !important;
        }

        .skip-link {
            position: absolute;
            top: -40px;
            left: 0;
            background: #0d6efd;
            color: white;
            padding: 8px;
            z-index: 100;
            transition: top 0.2s;
        }

        .skip-link:focus {
            top: 0;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <a href="#conteudo-principal" class="skip-link">Pular para o conteudo principal</a>

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">

        {{-- Navegacao principal --}}
        <nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700" aria-label="Navegacao principal">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">

                    {{-- Logo + Links desktop --}}
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('usuario.vagas.index') }}" aria-label="Pagina inicial - Vagas">
                                <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                            </a>
                        </div>

                        <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                            <x-nav-link :href="route('usuario.vagas.index')" :active="request()->routeIs('usuario.vagas.*')">
                                {{ __('Vagas') }}
                            </x-nav-link>

                            @auth
                                @if(auth()->user()->isCandidato())
                                    <x-nav-link :href="route('usuario.dashboard')" :active="request()->routeIs('usuario.dashboard')">
                                        {{ __('Meu Painel') }}
                                    </x-nav-link>
                                    <x-nav-link :href="route('usuario.candidaturas.index')" :active="request()->routeIs('usuario.candidaturas.*')">
                                        {{ __('Candidaturas') }}
                                    </x-nav-link>
                                @endif
                            @endauth
                        </div>
                    </div>

                    {{-- Acoes desktop: dark mode + auth --}}
                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                        {{-- Botao dark mode --}}
                        <button type="button" x-data="{ isDark: document.documentElement.classList.contains('dark') }"
                            x-on:click="isDark = !isDark; localStorage.theme = isDark ? 'dark' : 'light'; document.documentElement.classList.toggle('dark', isDark)"
                            class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5 mr-3"
                            :aria-label="isDark ? 'Alternar para Modo Claro' : 'Alternar para Modo Escuro'">
                            <svg x-show="isDark" style="display: none;" class="w-5 h-5 mx-auto" fill="currentColor"
                                viewBox="0 0 20 20" aria-hidden="true">
                                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                    fill-rule="evenodd" clip-rule="evenodd"></path>
                            </svg>
                            <svg x-show="!isDark" class="w-5 h-5 mx-auto" fill="currentColor" viewBox="0 0 20 20"
                                aria-hidden="true">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
                        </button>

                        @auth
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                        <div>{{ Auth::user()->name }}</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" viewBox="0 0 20 20" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('usuario.perfil.edit')">
                                        {{ __('Meu Perfil') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('usuario.acessibilidade')">
                                        {{ __('Acessibilidade') }}
                                    </x-dropdown-link>
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Conta') }}
                                    </x-dropdown-link>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                            {{ __('Sair') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 px-3 py-2">
                                {{ __('Entrar') }}
                            </a>
                            <a href="{{ route('register') }}" class="text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md px-4 py-2 ml-2 transition">
                                {{ __('Criar Conta') }}
                            </a>
                        @endauth
                    </div>

                    {{-- Botao hamburger mobile --}}
                    <div class="-me-2 flex items-center sm:hidden">
                        <button @click="open = !open"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out"
                            aria-label="Abrir menu de navegacao"
                            :aria-expanded="open.toString()">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                                <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden"
                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Menu mobile --}}
            <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden">
                <div class="pt-2 pb-3 space-y-1">
                    <x-responsive-nav-link :href="route('usuario.vagas.index')" :active="request()->routeIs('usuario.vagas.*')">
                        {{ __('Vagas') }}
                    </x-responsive-nav-link>

                    @auth
                        @if(auth()->user()->isCandidato())
                            <x-responsive-nav-link :href="route('usuario.dashboard')" :active="request()->routeIs('usuario.dashboard')">
                                {{ __('Meu Painel') }}
                            </x-responsive-nav-link>
                            <x-responsive-nav-link :href="route('usuario.candidaturas.index')" :active="request()->routeIs('usuario.candidaturas.*')">
                                {{ __('Candidaturas') }}
                            </x-responsive-nav-link>
                        @endif
                    @endauth
                </div>

                <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                    @auth
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                            <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <x-responsive-nav-link :href="route('usuario.perfil.edit')">
                                {{ __('Meu Perfil') }}
                            </x-responsive-nav-link>
                            <x-responsive-nav-link :href="route('usuario.acessibilidade')">
                                {{ __('Acessibilidade') }}
                            </x-responsive-nav-link>
                            <x-responsive-nav-link :href="route('profile.edit')">
                                {{ __('Conta') }}
                            </x-responsive-nav-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Sair') }}
                                </x-responsive-nav-link>
                            </form>
                        </div>
                    @else
                        <div class="px-4 space-y-2 py-2">
                            <a href="{{ route('login') }}" class="block text-center text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 py-2">
                                {{ __('Entrar') }}
                            </a>
                            <a href="{{ route('register') }}" class="block text-center text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md px-4 py-2 transition">
                                {{ __('Criar Conta') }}
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>

        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main id="conteudo-principal">
            {{ $slot }}
        </main>

        <footer class="bg-white dark:bg-gray-800 border-t border-gray-100 dark:border-gray-700 text-center p-4 mt-8">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                &copy; {{ date('Y') }} {{ config('app.name', 'Painel de Vagas') }} &mdash;
                <a href="#" class="text-blue-600 dark:text-blue-400 hover:underline">Politica de Acessibilidade</a>
            </p>
        </footer>
    </div>

    {{-- VLibras — Tradutor para Língua Brasileira de Sinais --}}
    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>

    <script src="https://unpkg.com/imask"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var telefoneEl = document.getElementById('telefone');
            if (telefoneEl) {
                IMask(telefoneEl, { mask: '(00) 00000-0000' });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
