<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- Header --}}
    <div class="mb-7">
        <h2 class="text-2xl font-bold text-gray-900">Bem-vindo de volta!</h2>
        <p class="mt-1.5 text-sm text-gray-500">
            Não tem uma conta?
            <a href="{{ route('register') }}" class="font-semibold text-blue-700 hover:text-blue-600 transition-colors">
                Cadastre-se gratuitamente
            </a>
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- E-mail --}}
        <div>
            <x-input-label for="email" value="E-mail" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Senha --}}
        <div class="mt-5">
            <div class="flex items-center justify-between mb-1">
                <x-input-label for="password" value="Senha" />
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-xs font-medium text-blue-700 hover:text-blue-600 transition-colors">
                        Esqueci minha senha
                    </a>
                @endif
            </div>
            <x-text-input id="password" class="block w-full" type="password"
                name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Lembrar-me --}}
        <div class="flex items-center mt-5">
            <input id="remember_me" type="checkbox" name="remember"
                class="w-4 h-4 rounded border-gray-300 text-blue-700 shadow-sm focus:ring-blue-500">
            <label for="remember_me" class="ml-2 text-sm text-gray-600 select-none cursor-pointer">
                Lembrar-me neste dispositivo
            </label>
        </div>

        {{-- Botão entrar --}}
        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3 text-sm font-semibold tracking-wide">
                Entrar
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
