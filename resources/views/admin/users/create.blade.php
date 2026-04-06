<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-slate-900 dark:text-slate-50 leading-tight">
                    Novo usuário
                </h2>
                <div class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Somente admin pode criar professores e responsáveis.
                </div>
            </div>

            <a href="{{ route('admin.users.index') }}" class="text-sm text-slate-700 dark:text-slate-300 hover:text-blue-950 dark:hover:text-slate-50">
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-6 md:py-8 lg:py-12">
        <div class="max-w-2xl mx-auto px-3 md:px-6 lg:px-8">
            <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100 dark:border-slate-700">
                <div class="p-4 md:p-6 text-slate-900 dark:text-slate-50">
                    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4 md:space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="role" value="Cargo" />
                            <select id="role" name="role" class="mt-1 block w-full border-blue-200 dark:border-slate-600 focus:border-blue-700 dark:focus:border-blue-500 focus:ring-blue-700 dark:focus:ring-blue-500 rounded-md shadow-sm bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-50" required>
                                <option value="professor" @selected(old('role', 'professor') === 'professor')>Professor</option>
                                <option value="responsavel" @selected(old('role') === 'responsavel')>Responsável</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('role')" />
                        </div>

                        <div>
                            <x-input-label for="name" value="Nome" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="email" value="Email (login)" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <div>
                            <x-input-label for="phone" value="Telefone (WhatsApp)" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone')" placeholder="(11) 99999-9999" />
                            <div class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                Obrigatório para responsável.
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="password" value="Senha" />
                                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required />
                                <x-input-error class="mt-2" :messages="$errors->get('password')" />
                            </div>

                            <div>
                                <x-input-label for="password_confirmation" value="Confirmar senha" />
                                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required />
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <x-primary-button>Criar</x-primary-button>
                            <a href="{{ route('admin.users.index') }}" class="text-sm text-slate-700 dark:text-slate-300 hover:text-blue-950 dark:hover:text-slate-50">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
