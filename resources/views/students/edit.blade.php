<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            Editar aluno
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100">
                <div class="p-6 text-slate-900">
                    <form method="POST" action="{{ route('alunos.update', $student) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" value="Nome" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $student->name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="birth_date" value="Data de nascimento (opcional)" />
                            <x-text-input id="birth_date" name="birth_date" type="date" class="mt-1 block" :value="old('birth_date', optional($student->birth_date)->toDateString())" />
                            <x-input-error class="mt-2" :messages="$errors->get('birth_date')" />
                        </div>

                        @if ($responsaveis->isEmpty())
                            <div class="bg-amber-50 border border-amber-200 text-amber-900 px-4 py-3 rounded-md">
                                Nenhum responsável cadastrado ainda. Cadastre um responsável em
                                <a class="underline font-semibold" href="{{ route('admin.users.create') }}">Usuários</a>.
                            </div>
                        @endif

                        <div>
                            <x-input-label for="responsavel_id" value="Responsável" />
                            <select id="responsavel_id" name="responsavel_id" class="mt-1 block w-full border-blue-200 focus:border-blue-700 focus:ring-blue-700 rounded-md shadow-sm bg-white" required>
                                <option value="">Selecione...</option>
                                @foreach ($responsaveis as $responsavel)
                                    <option value="{{ $responsavel->id }}" @selected((string) old('responsavel_id', $student->responsavel_id) === (string) $responsavel->id)>
                                        {{ $responsavel->name }}{{ $responsavel->phone ? ' • '.$responsavel->phone : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('responsavel_id')" />
                        </div>

                        <div>
                            <x-input-label for="team_id" value="Turma" />
                            <select id="team_id" name="team_id" class="mt-1 block w-full border-blue-200 focus:border-blue-700 focus:ring-blue-700 rounded-md shadow-sm bg-white" required>
                                <option value="">Selecione...</option>
                                @foreach ($teams as $team)
                                    <option value="{{ $team->id }}" @selected((string) old('team_id', $student->team_id) === (string) $team->id)>
                                        {{ $team->name }} ({{ $team->time }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('team_id')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="class_start_time" value="Hora de início da aula (opcional)" />
                                <x-text-input id="class_start_time" name="class_start_time" type="time" class="mt-1 block w-full" :value="old('class_start_time', optional($student->class_start_time)->format('H:i'))" />
                                <x-input-error class="mt-2" :messages="$errors->get('class_start_time')" />
                            </div>

                            <div>
                                <x-input-label for="class_end_time" value="Hora de término da aula (opcional)" />
                                <x-text-input id="class_end_time" name="class_end_time" type="time" class="mt-1 block w-full" :value="old('class_end_time', optional($student->class_end_time)->format('H:i'))" />
                                <x-input-error class="mt-2" :messages="$errors->get('class_end_time')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="fee" value="Mensalidade (R$)" />
                                <x-text-input id="fee" name="fee" type="number" step="0.01" min="0" class="mt-1 block w-full" :value="old('fee', $student->fee)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('fee')" />
                            </div>

                            <div>
                                <x-input-label for="due_day" value="Dia do vencimento" />
                                <x-text-input id="due_day" name="due_day" type="number" min="1" max="31" class="mt-1 block w-full" :value="old('due_day', $student->due_day)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('due_day')" />
                            </div>

                            <div class="flex items-center gap-2 mt-6">
                                <input id="active" name="active" type="checkbox" value="1" class="rounded border-blue-200 text-blue-900 shadow-sm focus:ring-blue-700" @checked(old('active', $student->active)) />
                                <label for="active" class="text-sm text-gray-700">Ativo</label>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="notes" value="Observações (opcional)" />
                            <textarea id="notes" name="notes" rows="4" class="mt-1 block w-full border-blue-200 focus:border-blue-700 focus:ring-blue-700 rounded-md shadow-sm bg-white">{{ old('notes', $student->notes) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('notes')" />
                        </div>

                        <div class="flex items-center gap-3">
                            <x-primary-button>Salvar</x-primary-button>
                            <a href="{{ route('alunos.index') }}" class="text-sm text-slate-700 hover:text-blue-950">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
