<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            Editar turma
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100">
                <div class="p-6 text-slate-900">
                    <form method="POST" action="{{ route('turmas.update', $team) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        @if (auth()->user()?->isAdmin() && $teachers->isEmpty())
                            <div class="bg-amber-50 border border-amber-200 text-amber-900 px-4 py-3 rounded-md">
                                Nenhum professor cadastrado ainda. Cadastre um professor em
                                <a class="underline font-semibold" href="{{ route('admin.users.create') }}">Usuários</a>
                                para conseguir atribuir/alterar o professor da turma.
                            </div>
                        @endif

                        <div>
                            <x-input-label for="name" value="Nome" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $team->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="time" value="Turno" />
                            <x-text-input id="time" name="time" type="text" class="mt-1 block w-full" :value="old('time', $team->time)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('time')" />
                        </div>

                        @if (auth()->user()?->isAdmin())
                            <div>
                                <x-input-label for="user_id" value="Professor" />
                                <select id="user_id" name="user_id" class="mt-1 block w-full border-blue-200 focus:border-blue-700 focus:ring-blue-700 rounded-md shadow-sm bg-white" required @disabled($teachers->isEmpty())>
                                    <option value="">Selecione...</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" @selected((string) old('user_id', $team->user_id) === (string) $teacher->id)>
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
