<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 dark:text-slate-50 leading-tight">
            Novo aluno
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100 dark:border-slate-700">
                <div class="p-6 text-slate-900 dark:text-slate-50">
                    <form method="POST" action="{{ route('alunos.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="name" value="Nome" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="birth_date" value="Data de nascimento (opcional)" />
                            <x-text-input id="birth_date" name="birth_date" type="date" class="mt-1 block" :value="old('birth_date')" />
                            <x-input-error class="mt-2" :messages="$errors->get('birth_date')" />
                        </div>

                        @if ($responsaveis->isEmpty())
                            <div class="bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 text-amber-900 dark:text-amber-200 px-4 py-3 rounded-md">
                                Nenhum responsável cadastrado ainda. Cadastre um responsável em
                                <a class="underline font-semibold" href="{{ route('admin.users.create') }}">Usuários</a>
                                para conseguir vincular o aluno.
                            </div>
                        @endif

                        <div>
                            <x-input-label for="responsavel_id" value="Responsável" />
                            <select id="responsavel_id" name="responsavel_id" class="mt-1 block w-full border-blue-200 dark:border-slate-600 focus:border-blue-700 dark:focus:border-blue-500 focus:ring-blue-700 dark:focus:ring-blue-500 rounded-md shadow-sm bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-50" required>
                                <option value="">Selecione...</option>
                                @foreach ($responsaveis as $responsavel)
                                    <option value="{{ $responsavel->id }}" @selected((string) old('responsavel_id') === (string) $responsavel->id)>
                                        {{ $responsavel->name }}{{ $responsavel->phone ? ' • '.$responsavel->phone : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('responsavel_id')" />
                        </div>

                        <div>
                            <x-input-label for="team_id" value="Turma" />
                            <select id="team_id" name="team_id" class="mt-1 block w-full border-blue-200 dark:border-slate-600 focus:border-blue-700 dark:focus:border-blue-500 focus:ring-blue-700 dark:focus:ring-blue-500 rounded-md shadow-sm bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-50" required>
                                <option value="">Selecione...</option>
                                @foreach ($teams as $team)
                                    <option value="{{ $team->id }}" @selected((string) old('team_id') === (string) $team->id)>
                                        {{ $team->name }} ({{ $team->time }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('team_id')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="class_start_time" value="Hora de início da aula (opcional)" />
                                <x-text-input id="class_start_time" name="class_start_time" type="time" class="mt-1 block w-full" :value="old('class_start_time')" />
                                <x-input-error class="mt-2" :messages="$errors->get('class_start_time')" />
                            </div>

                            <div>
                                <x-input-label for="class_end_time" value="Hora de término da aula (opcional)" />
                                <x-text-input id="class_end_time" name="class_end_time" type="time" class="mt-1 block w-full" :value="old('class_end_time')" />
                                <x-input-error class="mt-2" :messages="$errors->get('class_end_time')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="fee" value="Mensalidade (R$)" />
                                <x-text-input id="fee" name="fee" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('fee')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('fee')" />
                            </div>

                            <div>
                                <x-input-label for="due_day" value="Dia do vencimento" />
                                <x-text-input id="due_day" name="due_day" type="number" min="1" max="31" class="mt-1 block w-full" :value="old('due_day')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('due_day')" />
                            </div>

                            <div class="flex items-center gap-2 mt-6">
                                <input id="active" name="active" type="checkbox" value="1" class="rounded border-blue-200 dark:border-slate-600 text-blue-900 dark:text-blue-600 shadow-sm focus:ring-blue-700 dark:focus:ring-blue-500" @checked(old('active', true)) />
                                <label for="active" class="text-sm text-gray-700 dark:text-slate-300">Ativo</label>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="notes" value="Observações (opcional)" />
                            <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full border-blue-200 dark:border-slate-600 focus:border-blue-700 dark:focus:border-blue-500 focus:ring-blue-700 dark:focus:ring-blue-500 rounded-md shadow-sm bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-50 placeholder:text-slate-400 dark:placeholder:text-slate-500">{{ old('notes') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                        </div>

                        <div class="flex items-center gap-3">
                            <x-primary-button>Salvar</x-primary-button>
                            <a href="{{ route('alunos.index') }}" class="text-sm text-slate-700 dark:text-slate-300 hover:text-blue-950 dark:hover:text-slate-100">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
