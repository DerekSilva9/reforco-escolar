<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-900 dark:text-slate-50 tracking-tight">Verificar email</h1>
        <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">Enviamos um link de verificação para o seu email. Confirme para continuar.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-200 dark:border-emerald-800 text-emerald-900 dark:text-emerald-200 px-4 py-3 rounded-xl shadow-sm text-sm font-medium">
            Um novo link de verificação foi enviado para o seu email.
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    Reenviar email
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-slate-700 dark:text-slate-300 hover:text-blue-950 dark:hover:text-slate-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-200 dark:focus:ring-offset-slate-900 dark:focus:ring-blue-500 focus:ring-offset-amber-50">
                Sair
            </button>
        </form>
    </div>
</x-guest-layout>
