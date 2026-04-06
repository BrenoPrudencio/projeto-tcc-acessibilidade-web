<x-guest-layout>

    {{-- Header --}}
    <div class="mb-7">
        <h2 class="text-2xl font-bold text-gray-900">Esqueceu sua senha?</h2>
        <p class="mt-1.5 text-sm text-gray-500">
            Informe seu e-mail e enviaremos um link para redefinição.
        </p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" value="E-mail" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3 text-sm font-semibold tracking-wide">
                Enviar link de redefinição
            </x-primary-button>
        </div>

        <div class="mt-5 text-center">
            <a href="{{ route('login') }}" class="text-sm font-medium text-blue-700 hover:text-blue-600 transition-colors">
                ← Voltar ao login
            </a>
        </div>
    </form>
</x-guest-layout>
