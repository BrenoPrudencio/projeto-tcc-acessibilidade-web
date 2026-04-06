<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'AcessoVagas') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex">

            {{-- Left brand panel (desktop only) --}}
            <div class="hidden lg:flex lg:w-[45%] bg-gradient-to-br from-blue-700 via-blue-800 to-blue-900 flex-col justify-between p-12 relative overflow-hidden">

                {{-- Background decorative circles --}}
                <div class="absolute -bottom-24 -left-24 w-80 h-80 bg-white opacity-5 rounded-full"></div>
                <div class="absolute -top-16 -right-16 w-64 h-64 bg-green-400 opacity-10 rounded-full"></div>
                <div class="absolute bottom-32 right-8 w-40 h-40 bg-blue-500 opacity-20 rounded-full"></div>

                {{-- Logo (white version on dark) --}}
                <div class="z-10">
                    <a href="/" class="flex items-center">
                        <svg viewBox="0 0 200 56" xmlns="http://www.w3.org/2000/svg" fill="none" class="h-12 w-auto">
                            <circle cx="21" cy="7" r="3.5" fill="#fdba74"/>
                            <circle cx="30" cy="4" r="3.5" fill="#86efac"/>
                            <circle cx="39" cy="7" r="3.5" fill="#93c5fd"/>
                            <path d="M11 29 Q10 16 21 10" stroke="#fdba74" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                            <path d="M49 29 Q50 16 39 10" stroke="#93c5fd" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                            <path d="M30 12 C20.6 12 13 19.6 13 29 C13 40 30 52 30 52 C30 52 47 40 47 29 C47 19.6 39.4 12 30 12Z" fill="white" opacity="0.9"/>
                            <path d="M30 12 C38.5 12 45.5 18.5 46.8 27" stroke="#86efac" stroke-width="3" fill="none" stroke-linecap="round"/>
                            <circle cx="30" cy="28" r="9" fill="#1d4ed8"/>
                            <path d="M25 28 L28.5 31.5 L36 23.5" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <text y="38" font-family="ui-sans-serif, system-ui, sans-serif" font-weight="800" font-size="23" letter-spacing="-0.3">
                                <tspan x="58" fill="white">Acesso</tspan><tspan fill="#86efac">Vagas</tspan>
                            </text>
                        </svg>
                    </a>
                </div>

                {{-- Main brand content --}}
                <div class="z-10">
                    <h1 class="text-4xl font-bold text-white leading-snug mb-4">
                        Conectando talentos<br>a oportunidades
                    </h1>
                    <p class="text-blue-200 text-lg mb-10">
                        Plataforma inclusiva de emprego para pessoas com deficiência.
                    </p>

                    <div class="space-y-5">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-green-500 bg-opacity-30 border border-green-400 border-opacity-40 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <span class="text-blue-100 text-sm font-medium">Vagas exclusivamente inclusivas</span>
                        </div>

                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-orange-400 bg-opacity-20 border border-orange-300 border-opacity-30 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-orange-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <span class="text-blue-100 text-sm font-medium">Ambiente seguro e acessível</span>
                        </div>

                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-400 bg-opacity-20 border border-blue-300 border-opacity-30 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <span class="text-blue-100 text-sm font-medium">Gerencie candidaturas com facilidade</span>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="z-10">
                    <p class="text-blue-400 text-xs">© {{ date('Y') }} AcessoVagas. Todos os direitos reservados.</p>
                </div>
            </div>

            {{-- Right form panel --}}
            <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 bg-gray-50">

                {{-- Mobile logo --}}
                <div class="lg:hidden flex justify-center mb-8">
                    <a href="/">
                        <x-application-logo class="h-10 w-auto" />
                    </a>
                </div>

                <div class="mx-auto w-full max-w-md">
                    <div class="bg-white py-8 px-8 shadow-lg rounded-2xl border border-gray-100">
                        {{ $slot }}
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>
