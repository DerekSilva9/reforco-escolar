<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-slate-900 leading-tight">
                    Presença
                </h2>
                <div class="text-sm text-slate-600 mt-1">
                    Selecione a turma e a data, marque com 1 clique e salve.
                </div>
            </div>

            <a href="{{ route('dashboard') }}" class="text-sm text-slate-700 hover:text-blue-950">
                Voltar ao dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100">
                <div class="p-6 text-slate-900">
                    <form method="GET" action="{{ route('presenca.index') }}" class="flex flex-wrap items-end gap-4">
                        <div>
                            <x-input-label for="team_id" value="Turma" />
                            <select id="team_id" name="team_id" class="mt-1 block w-80 border-blue-200 focus:border-blue-700 focus:ring-blue-700 rounded-md shadow-sm bg-white" required>
                                <option value="">Selecione...</option>
                                @foreach ($teams as $t)
                                    <option value="{{ $t->id }}" @selected((string) $selectedTeamId === (string) $t->id)>
                                        {{ $t->name }} ({{ $t->time }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="date" value="Data" />
                            <x-text-input id="date" name="date" type="date" class="mt-1 block" :value="$date" required />
                        </div>

                        <x-primary-button>Carregar</x-primary-button>

                        @if ($selectedTeamId)
                            <a href="{{ route('presenca.index') }}" class="text-sm text-slate-700 hover:text-blue-950">
                                Limpar
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            @if ($team)
                <div class="bg-white/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100">
                    <div class="p-6 text-slate-900">
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                            <div class="font-semibold text-slate-900">
                                {{ $team->name }} • {{ $team->time }} • {{ \Illuminate\Support\Carbon::parse($date)->format('d/m/Y') }}
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <button type="button" id="mark-all-present" class="inline-flex items-center px-3 py-1.5 bg-amber-200 rounded-md text-blue-950 hover:bg-amber-300 border border-amber-300 shadow-sm">
                                    Marcar todos presentes
                                </button>
                                <button type="button" id="mark-all-absent" class="inline-flex items-center px-3 py-1.5 bg-white rounded-md text-blue-950 hover:bg-amber-50 border border-blue-200 shadow-sm">
                                    Marcar todos faltaram
                                </button>
                            </div>
                        </div>

                        @if ($students->isEmpty())
                            <div class="text-slate-600">
                                Nenhum aluno ativo nessa turma.
                            </div>
                        @else
                            <form method="POST" action="{{ route('presenca.store') }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="team_id" value="{{ $team->id }}" />
                                <input type="hidden" name="date" value="{{ $date }}" />

                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-sm">
                                        <thead>
                                            <tr class="text-left text-slate-600 border-b border-blue-100">
                                                <th class="py-2 pe-4">Aluno</th>
                                                <th class="py-2 pe-4">Status</th>
                                                <th class="py-2">Observação (opcional)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($students as $student)
                                                @php
                                                    $attendance = $attendances->get($student->id);
                                                    $defaultPresent = $attendance ? (int) $attendance->present : 1;
                                                    $presentValue = (int) old("present.{$student->id}", $defaultPresent);
                                                    $presentText = $presentValue === 1 ? 'present' : 'absent';
                                                @endphp
                                                <tr class="border-b border-blue-50 last:border-0">
                                                    <td class="py-3 pe-4 font-medium text-slate-900">
                                                        {{ $student->name }}
                                                    </td>
                                                    <td class="py-3 pe-4">
                                                        <input type="hidden" class="present-input" name="present[{{ $student->id }}]" value="{{ $presentValue }}" data-student-id="{{ $student->id }}" />
                                                        <div class="inline-flex rounded-md shadow-sm overflow-hidden border border-blue-200">
                                                            <button
                                                                type="button"
                                                                class="presence-btn presence-present px-3 py-2 text-sm font-semibold"
                                                                data-student-id="{{ $student->id }}"
                                                                data-value="1"
                                                            >
                                                                ✅ Presente
                                                            </button>
                                                            <button
                                                                type="button"
                                                                class="presence-btn presence-absent px-3 py-2 text-sm font-semibold"
                                                                data-student-id="{{ $student->id }}"
                                                                data-value="0"
                                                            >
                                                                ❌ Falta
                                                            </button>
                                                        </div>
                                                        <span class="ms-2 text-xs text-slate-500 status-pill" data-student-id="{{ $student->id }}">
                                                            {{ $presentText === 'present' ? 'Presente' : 'Faltou' }}
                                                        </span>
                                                    </td>
                                                    <td class="py-3">
                                                        <div class="flex flex-wrap items-center gap-2">
                                                            <button type="button" class="obs-emoji inline-flex items-center px-2 py-1 bg-amber-50 border border-blue-200 rounded-md hover:bg-amber-100" data-target="obs-{{ $student->id }}" data-value="👍">
                                                                👍
                                                            </button>
                                                            <button type="button" class="obs-emoji inline-flex items-center px-2 py-1 bg-amber-50 border border-blue-200 rounded-md hover:bg-amber-100" data-target="obs-{{ $student->id }}" data-value="⚠️">
                                                                ⚠️
                                                            </button>
                                                            <button type="button" class="obs-emoji inline-flex items-center px-2 py-1 bg-amber-50 border border-blue-200 rounded-md hover:bg-amber-100" data-target="obs-{{ $student->id }}" data-value="👎">
                                                                👎
                                                            </button>
                                                            <x-text-input
                                                                id="obs-{{ $student->id }}"
                                                                name="obs[{{ $student->id }}]"
                                                                type="text"
                                                                class="block w-full md:w-96"
                                                                :value="old('obs.' . $student->id, $attendance?->obs)"
                                                                placeholder="Ex: chegou atrasado / sem material..."
                                                            />
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="flex items-center justify-between gap-4 pt-2">
                                    <x-primary-button>Salvar tudo</x-primary-button>
                                    <div class="text-sm text-slate-600">
                                        Dica: marcar não salva — clique em “Salvar tudo” no final.
                                    </div>
                                </div>
                            </form>

                            <script>
                                const setRowState = (studentId, value) => {
                                    const input = document.querySelector(`.present-input[data-student-id="${studentId}"]`);
                                    if (!input) return;
                                    input.value = String(value);

                                    const presentBtn = document.querySelector(`.presence-present[data-student-id="${studentId}"]`);
                                    const absentBtn = document.querySelector(`.presence-absent[data-student-id="${studentId}"]`);
                                    const pill = document.querySelector(`.status-pill[data-student-id="${studentId}"]`);

                                    if (value === 1) {
                                        presentBtn?.classList.add('bg-emerald-600', 'text-white');
                                        presentBtn?.classList.remove('bg-emerald-50', 'text-emerald-800');
                                        absentBtn?.classList.add('bg-rose-50', 'text-rose-800');
                                        absentBtn?.classList.remove('bg-rose-600', 'text-white');
                                        if (pill) pill.textContent = 'Presente';
                                    } else {
                                        absentBtn?.classList.add('bg-rose-600', 'text-white');
                                        absentBtn?.classList.remove('bg-rose-50', 'text-rose-800');
                                        presentBtn?.classList.add('bg-emerald-50', 'text-emerald-800');
                                        presentBtn?.classList.remove('bg-emerald-600', 'text-white');
                                        if (pill) pill.textContent = 'Faltou';
                                    }
                                };

                                document.querySelectorAll('.presence-btn').forEach((btn) => {
                                    btn.addEventListener('click', () => {
                                        const studentId = btn.getAttribute('data-student-id');
                                        const value = Number(btn.getAttribute('data-value'));
                                        setRowState(studentId, value);
                                    });
                                });

                                document.getElementById('mark-all-present')?.addEventListener('click', () => {
                                    document.querySelectorAll('.present-input').forEach((input) => {
                                        setRowState(input.getAttribute('data-student-id'), 1);
                                    });
                                });

                                document.getElementById('mark-all-absent')?.addEventListener('click', () => {
                                    document.querySelectorAll('.present-input').forEach((input) => {
                                        setRowState(input.getAttribute('data-student-id'), 0);
                                    });
                                });

                                document.querySelectorAll('.obs-emoji').forEach((btn) => {
                                    btn.addEventListener('click', () => {
                                        const targetId = btn.getAttribute('data-target');
                                        const value = btn.getAttribute('data-value');
                                        const input = document.getElementById(targetId);
                                        if (!input) return;

                                        const current = (input.value || '').trim();
                                        input.value = current ? `${current} ${value}` : value;
                                        input.focus();
                                    });
                                });

                                // Initialize button colors from current state
                                document.querySelectorAll('.present-input').forEach((input) => {
                                    const studentId = input.getAttribute('data-student-id');
                                    const value = Number(input.value);
                                    setRowState(studentId, value === 1 ? 1 : 0);
                                });
                            </script>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
