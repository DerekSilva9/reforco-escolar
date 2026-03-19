<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 dark:text-slate-50 leading-tight">
            Nova turma
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100 dark:border-slate-700">
                <div class="p-6 text-slate-900 dark:text-slate-50">
                    <form method="POST" action="{{ route('turmas.store') }}" class="space-y-6">
                        @csrf

                        @if (auth()->user()?->isAdmin() && $teachers->isEmpty())
                            <div class="bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 text-amber-900 dark:text-amber-200 px-4 py-3 rounded-md">
                                Nenhum professor cadastrado ainda. Cadastre um professor em
                                <a class="underline font-semibold" href="{{ route('admin.users.create') }}">Usuários</a>
                                e volte aqui para criar a turma.
                            </div>
                        @endif

                        <div>
                            <x-input-label for="name" value="Nome" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="time" value="Turno" />
                            <x-text-input id="time" name="time" type="text" class="mt-1 block w-full" :value="old('time')" required />
                            <x-input-error class="mt-2" :messages="$errors->get('time')" />
                        </div>

                        @if (auth()->user()?->isAdmin())
                            <div>
                                <x-input-label for="user_id" value="Professor" />
                                <select id="user_id" name="user_id" class="mt-1 block w-full border-blue-200 dark:border-slate-600 focus:border-blue-700 dark:focus:border-blue-500 focus:ring-blue-700 dark:focus:ring-blue-500 rounded-md shadow-sm bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-50" required @disabled($teachers->isEmpty())>
                                    <option value="">Selecione...</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" @selected((string) old('user_id') === (string) $teacher->id)>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                            </div>
                        @endif

                        <div class="flex items-center gap-3">
                            <x-primary-button :disabled="auth()->user()?->isAdmin() && $teachers->isEmpty()">Salvar</x-primary-button>
                            <a href="{{ route('turmas.index') }}" class="text-sm text-slate-700 hover:text-blue-950">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
