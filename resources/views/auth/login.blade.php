<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-900 dark:text-slate-50 tracking-tight">Entrar</h1>
        <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">Acesse sua conta para acompanhar alunos, chamadas e recados.</p>
        <button type="button"
                data-pwa-install
                hidden
                class="mt-4 inline-flex items-center gap-2 rounded-lg border border-cyan-200 bg-cyan-50 px-4 py-2 text-sm font-semibold text-cyan-900 transition-colors hover:bg-cyan-100 focus:outline-none focus:ring-2 focus:ring-cyan-700/25 dark:border-cyan-800/50 dark:bg-cyan-950/40 dark:text-cyan-100 dark:hover:bg-cyan-950/60">
            Instalar app
            <span>+</span>
        </button>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Senha" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-blue-200 dark:border-slate-600 text-blue-900 dark:text-blue-600 shadow-sm focus:ring-blue-700 dark:focus:ring-blue-500" name="remember">
                <span class="ms-2 text-sm text-slate-600 dark:text-slate-300">Lembrar de mim</span>
            </label>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3 mt-6">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-slate-700 dark:text-slate-300 hover:text-blue-950 dark:hover:text-slate-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-200 dark:focus:ring-offset-slate-900 dark:focus:ring-blue-500 focus:ring-offset-amber-50" href="{{ route('password.request') }}">
                    Esqueci minha senha
                </a>
            @endif

            <x-primary-button>
                Entrar
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
