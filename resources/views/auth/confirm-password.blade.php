<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-900 dark:text-slate-50 tracking-tight">Confirmar senha</h1>
        <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">Por segurança, confirme sua senha para continuar.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" value="Senha" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-6">
            <x-primary-button>
                Confirmar
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
