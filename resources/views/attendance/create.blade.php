<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-slate-900 dark:text-slate-50 leading-tight">
                    Chamada
                </h2>
                <div class="text-sm text-gray-600 dark:text-slate-400 mt-1">
                    {{ $team->name }} • {{ $team->time }}
                </div>
            </div>

            <a href="{{ route('teams.index') }}" class="text-sm text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200">
                Voltar para turmas
            </a>
        </div>
    </x-slot>

    <div class="py-6 md:py-8 lg:py-12">
        <div class="max-w-5xl mx-auto px-3 md:px-6 lg:px-8 space-y-4 md:space-y-6">
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg border border-slate-200 dark:border-slate-700">
                <div class="p-6 text-gray-900 dark:text-slate-50">
                    <form method="GET" action="{{ route('teams.attendance.create', $team) }}" class="flex flex-wrap items-end gap-3">
                        <div>
                            <x-input-label for="date" value="Data" />
                            <x-text-input id="date" name="date" type="date" class="mt-1 block" :value="$date" required />
                            <x-input-error class="mt-2" :messages="$errors->get('date')" />
                        </div>

                        <x-primary-button>Ir</x-primary-button>

                        <div class="text-sm text-slate-500 dark:text-slate-400">
                            Dica: por padrão, todo mundo começa como "presente".
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg border border-slate-200 dark:border-slate-700">
                <div class="p-6 text-slate-900 dark:text-slate-50">
                    @if ($students->isEmpty())
                        <div class="text-slate-600 dark:text-slate-400">
                            Nenhum aluno ativo nessa turma.
                        </div>
                    @else
                        <div class="flex flex-wrap gap-2 mb-4">
                            <button type="button" id="check-all" class="inline-flex items-center px-3 py-1.5 bg-slate-100 dark:bg-slate-700 rounded-md text-slate-900 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-600">
                                Marcar todos presentes
                            </button>
                            <button type="button" id="uncheck-all" class="inline-flex items-center px-3 py-1.5 bg-slate-100 dark:bg-slate-700 rounded-md text-slate-900 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-600">
                                Desmarcar todos
                            </button>
                        </div>

                        <form method="POST" action="{{ route('teams.attendance.store', $team) }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="date" value="{{ $date }}" />

                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                        <tr class="text-left text-slate-600 dark:text-slate-400 border-b dark:border-slate-700">
                                            <th class="py-2 pe-4">Aluno</th>
                                            <th class="py-2 pe-4">Presente</th>
                                            <th class="py-2">Observação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($students as $student)
                                            @php
                                                $attendance = $attendances->get($student->id);
                                                $defaultPresent = $attendance ? (int) $attendance->present : 1;
                                                $presentValue = (int) old("present.{$student->id}", $defaultPresent);
                                            @endphp
                                            <tr class="border-b last:border-0">
                                                <td class="py-3 pe-4 font-medium">{{ $student->name }}</td>
                                                <td class="py-3 pe-4">
                                                    <input type="hidden" name="present[{{ $student->id }}]" value="0">
                                                    <input
                                                        type="checkbox"
                                                        class="attendance-present rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                        name="present[{{ $student->id }}]"
                                                        value="1"
                                                        @checked($presentValue === 1)
                                                    />
                                                </td>
                                                <td class="py-3">
                                                    <x-text-input
                                                        name="obs[{{ $student->id }}]"
                                                        type="text"
                                                        class="block w-full"
                                                        :value="old(\"obs.{$student->id}\", $attendance?->obs)"
                                                        placeholder="(opcional)"
                                                    />
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="flex items-center gap-3">
                                <x-primary-button>Salvar chamada</x-primary-button>
                                <div class="text-sm text-gray-500">
                                    Salva/atualiza a presença do dia.
                                </div>
                            </div>
                        </form>

                        <script>
                            document.getElementById('check-all')?.addEventListener('click', () => {
                                document.querySelectorAll('.attendance-present').forEach((el) => { el.checked = true; });
                            });
                            document.getElementById('uncheck-all')?.addEventListener('click', () => {
                                document.querySelectorAll('.attendance-present').forEach((el) => { el.checked = false; });
                            });
                        </script>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

