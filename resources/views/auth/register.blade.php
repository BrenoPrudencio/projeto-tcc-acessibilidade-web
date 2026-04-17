<x-guest-layout>

    {{-- Live region para alertas de erro (leitores de tela anunciam automaticamente) --}}
    @if ($errors->any())
        <div role="alert" aria-live="assertive" aria-atomic="true"
            class="mb-5 rounded-lg border border-red-300 bg-red-50 p-4 text-sm text-red-700">
            <p class="font-semibold mb-1">Por favor, corrija os erros abaixo:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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

    <form method="POST" action="{{ route('register') }}" novalidate
        x-data="registerForm()" @submit="validateAll">
        @csrf

        {{-- ── SEÇÃO: Dados de Acesso ── --}}
        <fieldset class="mb-6">
            <legend class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">
                Dados de Acesso
            </legend>

            {{-- Nome completo --}}
            <div class="mb-5">
                <x-input-label for="name" value="Nome completo" />
                <x-text-input
                    id="name"
                    class="block mt-1 w-full"
                    type="text"
                    name="name"
                    :value="old('name')"
                    required
                    autofocus
                    autocomplete="name"
                    aria-required="true"
                    :aria-invalid="$errors->has('name') ? 'true' : 'false'"
                    aria-describedby="{{ $errors->has('name') ? 'name-error' : '' }}"
                />
                @error('name')
                    <p id="name-error" role="alert" class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- E-mail --}}
            <div class="mb-5">
                <x-input-label for="email" value="E-mail" />
                <x-text-input
                    id="email"
                    class="block mt-1 w-full"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autocomplete="username"
                    aria-required="true"
                    :aria-invalid="$errors->has('email') ? 'true' : 'false'"
                    aria-describedby="{{ $errors->has('email') ? 'email-error' : '' }}"
                />
                @error('email')
                    <p id="email-error" role="alert" class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Senha --}}
            <div class="mb-5">
                <x-input-label for="password" value="Senha" />
                <x-text-input
                    id="password"
                    class="block mt-1 w-full"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    aria-required="true"
                    aria-describedby="password-hint {{ $errors->has('password') ? 'password-error' : '' }}"
                    :aria-invalid="$errors->has('password') ? 'true' : 'false'"
                />
                <p id="password-hint" class="mt-1 text-xs text-gray-500">
                    Mínimo de 8 caracteres, com letras e números.
                </p>
                @error('password')
                    <p id="password-error" role="alert" class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirmar senha --}}
            <div>
                <x-input-label for="password_confirmation" value="Confirmar senha" />
                <x-text-input
                    id="password_confirmation"
                    class="block mt-1 w-full"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    aria-required="true"
                    :aria-invalid="$errors->has('password_confirmation') ? 'true' : 'false'"
                    aria-describedby="{{ $errors->has('password_confirmation') ? 'password-confirm-error' : '' }}"
                />
                @error('password_confirmation')
                    <p id="password-confirm-error" role="alert" class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </fieldset>

        {{-- ── SEÇÃO: Dados de Contato ── --}}
        <fieldset class="mb-6 border-t border-gray-200 pt-6">
            <legend class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">
                Dados de Contato
            </legend>

            {{-- Telefone --}}
            <div>
                <x-input-label for="telefone" value="Telefone" />
                <x-text-input
                    id="telefone"
                    class="block mt-1 w-full"
                    type="tel"
                    name="telefone"
                    :value="old('telefone')"
                    required
                    autocomplete="tel"
                    placeholder="(00) 00000-0000"
                    aria-required="true"
                    aria-describedby="telefone-hint {{ $errors->has('telefone') ? 'telefone-error' : '' }}"
                    :aria-invalid="$errors->has('telefone') ? 'true' : 'false'"
                    x-on:input="maskPhone($event)"
                />
                <p id="telefone-hint" class="mt-1 text-xs text-gray-500">
                    Formato: (00) 00000-0000
                </p>
                @error('telefone')
                    <p id="telefone-error" role="alert" class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </fieldset>

        {{-- ── SEÇÃO: Acessibilidade e PCD ── --}}
        <fieldset class="mb-6 border-t border-gray-200 pt-6" x-data="{ isPcd: {{ old('pcd') ? 'true' : 'false' }} }">
            <legend class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-4">
                Acessibilidade
            </legend>

            {{-- Checkbox PCD --}}
            <div class="flex items-start gap-3 mb-5">
                <input
                    id="pcd"
                    type="checkbox"
                    name="pcd"
                    value="1"
                    {{ old('pcd') ? 'checked' : '' }}
                    class="mt-0.5 h-4 w-4 rounded border-gray-300 text-blue-700 focus:ring-2 focus:ring-blue-500"
                    role="checkbox"
                    :aria-checked="isPcd.toString()"
                    aria-describedby="pcd-desc"
                    x-model="isPcd"
                />
                <div>
                    <label for="pcd" class="text-sm font-medium text-gray-800 cursor-pointer">
                        Sou Pessoa com Deficiência (PCD)
                    </label>
                    <p id="pcd-desc" class="text-xs text-gray-500 mt-0.5">
                        Marque se você se enquadra como PCD conforme a Lei nº 13.146/2015.
                    </p>
                </div>
            </div>

            {{-- Tipo de Deficiência (condicional) --}}
            <div x-show="isPcd" x-transition class="mb-5">
                <x-input-label for="tipo_deficiencia" value="Tipo de Deficiência" />
                <x-text-input
                    id="tipo_deficiencia"
                    class="block mt-1 w-full"
                    type="text"
                    name="tipo_deficiencia"
                    :value="old('tipo_deficiencia')"
                    placeholder="Ex.: Visual, Auditiva, Motora, Intelectual…"
                    autocomplete="off"
                    x-bind:required="isPcd"
                    x-bind:aria-required="isPcd ? 'true' : 'false'"
                    :aria-invalid="$errors->has('tipo_deficiencia') ? 'true' : 'false'"
                    aria-describedby="{{ $errors->has('tipo_deficiencia') ? 'tipo-deficiencia-error' : '' }}"
                />
                @error('tipo_deficiencia')
                    <p id="tipo-deficiencia-error" role="alert" class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Necessidades de Acessibilidade --}}
            <div>
                <x-input-label for="acessibilidade" value="Necessidades de Acessibilidade (opcional)" />
                <textarea
                    id="acessibilidade"
                    name="acessibilidade"
                    rows="3"
                    placeholder="Descreva adaptações que possam facilitar seu processo seletivo…"
                    autocomplete="off"
                    aria-required="false"
                    :aria-invalid="$errors->has('acessibilidade') ? 'true' : 'false'"
                    aria-describedby="{{ $errors->has('acessibilidade') ? 'acessibilidade-error' : '' }}"
                    class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                >{{ old('acessibilidade') }}</textarea>
                @error('acessibilidade')
                    <p id="acessibilidade-error" role="alert" class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </fieldset>

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

    <script>
        function registerForm() {
            return {
                maskPhone(e) {
                    let v = e.target.value.replace(/\D/g, '').substring(0, 11);
                    if (v.length > 6) {
                        v = '(' + v.substring(0,2) + ') ' + v.substring(2,7) + '-' + v.substring(7);
                    } else if (v.length > 2) {
                        v = '(' + v.substring(0,2) + ') ' + v.substring(2);
                    } else if (v.length > 0) {
                        v = '(' + v;
                    }
                    e.target.value = v;
                },
                validateAll(e) {
                    // Validação nativa do browser habilitada via atributos required;
                    // aria-invalid é gerenciado pelo servidor (Blade) após submit.
                }
            };
        }
    </script>

</x-guest-layout>
