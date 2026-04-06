<x-guest-layout>

    {{-- Header --}}
    <div class="mb-7">
        <h2 class="text-2xl font-bold text-gray-900">Criar sua conta</h2>
        <p class="mt-1.5 text-sm text-gray-500">
            Já tem uma conta?
            <a href="{{ route('login') }}" class="font-semibold text-blue-700 hover:text-blue-600 transition-colors">
                Entrar
            </a>
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Nome completo --}}
        <div>
            <x-input-label for="name" value="Nome completo" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- E-mail --}}
        <div class="mt-5">
            <x-input-label for="email" value="E-mail" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Senha --}}
        <div class="mt-5">
            <x-input-label for="password" value="Senha" />
            <x-text-input id="password" class="block mt-1 w-full" type="password"
                name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Confirmar senha --}}
        <div class="mt-5">
            <x-input-label for="password_confirmation" value="Confirmar senha" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Botão cadastrar --}}
        <div class="mt-7">
            <x-primary-button class="w-full justify-center py-3 text-sm font-semibold tracking-wide">
                Criar conta
            </x-primary-button>
        </div>

        <p class="mt-4 text-xs text-center text-gray-400">
            Ao se cadastrar, você concorda com nossos termos de uso.
        </p>
    </form>
</x-guest-layout>
