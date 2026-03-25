<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-900 dark:text-slate-50 tracking-tight">Criar conta</h1>
        <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">Cadastre-se para acessar o painel.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" value="Nome" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Senha" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Confirmar senha" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 mt-6">
            <a class="underline text-sm text-slate-700 dark:text-slate-300 hover:text-blue-950 dark:hover:text-slate-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-200 dark:focus:ring-offset-slate-900 dark:focus:ring-blue-500 focus:ring-offset-amber-50" href="{{ route('login') }}">
                Já tenho conta
            </a>

            <x-primary-button>
                Criar conta
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
