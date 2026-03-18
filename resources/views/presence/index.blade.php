<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                    Chamada
                </h1>
                <p class="text-xs text-slate-500 font-medium ml-4">Controle de presença diária</p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-slate-600 hover:text-slate-900 font-semibold text-sm">← Voltar ao Dashboard</a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <form method="GET" action="{{ route('presenca.index') }}" class="bg-white rounded-2xl p-6 shadow-md border border-slate-300 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="team_id" class="block text-sm font-semibold text-slate-900 mb-2">Turma</label>
                    <select id="team_id" name="team_id" class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition bg-white text-slate-900 font-medium" required>
                        <option value="">Selecione uma turma...</option>
                        @foreach ($teams as $t)
                            <option value="{{ $t->id }}" @selected((string) $selectedTeamId === (string) $t->id)>
                                {{ $t->name }} ({{ $t->time }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="date" class="block text-sm font-semibold text-slate-900 mb-2">Data</label>
                    <input type="date" id="date" name="date" value="{{ $date }}" class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition bg-white text-slate-900 font-medium" required>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-slate-100 border border-slate-300 rounded-lg px-6 py-3 font-semibold text-slate-900 hover:bg-slate-200 hover:border-slate-400 transition">
                        Carregar Lista
                    </button>
                </div>
            </div>
        </form>

        @if ($team)
            <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-300 mb-8">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-2 h-5 bg-blue-600 rounded-full"></span>
                    {{ $team->name }}
                </h2>
                <p class="text-slate-500 text-sm mt-2 ml-4">{{ $team->time }} • {{ \Illuminate\Support\Carbon::parse($date)->translatedFormat('d \d\e F \d\e Y') }}</p>
            </div>

            @if ($students->isEmpty())
                <div class="bg-white rounded-2xl p-16 text-center shadow-md border border-slate-300">
                    <p class="text-slate-600 text-lg">Nenhum aluno ativo nesta turma.</p>
                </div>
            @else
                <form method="POST" action="{{ route('presenca.store') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="team_id" value="{{ $team->id }}" />
                    <input type="hidden" name="date" value="{{ $date }}" />

                    <div class="flex gap-3">
                        <button type="button" id="mark-all-present" class="flex-1 bg-emerald-50 border border-emerald-300 text-emerald-700 px-6 py-3 rounded-lg font-bold hover:bg-emerald-100 hover:border-emerald-400 transition">
                            ✓ Todos Presentes
                        </button>
                        <button type="button" id="mark-all-absent" class="flex-1 bg-rose-50 border border-rose-300 text-rose-700 px-6 py-3 rounded-lg font-bold hover:bg-rose-100 hover:border-rose-400 transition">
                            ✗ Todos Ausentes
                        </button>
                    </div>

                    <div class="space-y-3">
                        @foreach ($students as $student)
                            @php
                                $attendance = $attendances->firstWhere('student_id', $student->id);
                                $isPresent = old("present.{$student->id}", $attendance?->present ?? 1);
                            @endphp
                            <div class="student-card bg-white rounded-2xl p-4 shadow-md border border-slate-300 transition-all" 
                                 data-student-id="{{ $student->id }}">
                                
                                <input type="hidden" name="present[{{ $student->id }}]" class="present-input" value="{{ $isPresent }}">

                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div class="flex items-center gap-4 flex-1">
                                        <div class="w-12 h-12 rounded-full bg-slate-900 flex items-center justify-center text-white font-bold text-lg flex-shrink-0 border border-slate-800">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 text-sm leading-tight">{{ $student->name }}</p>
                                            <p class="text-xs text-slate-500">Responsável: {{ $student->parent_name }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button type="button" onclick="setPresence('{{ $student->id }}', 1)" 
                                                class="btn-present px-4 py-2 rounded-lg font-bold border border-slate-300 text-slate-600 bg-slate-50 transition-all w-full md:w-auto text-xs">
                                            ✓ Presente
                                        </button>
                                        <button type="button" onclick="setPresence('{{ $student->id }}', 0)" 
                                                class="btn-absent px-4 py-2 rounded-lg font-bold border border-slate-300 text-slate-600 bg-slate-50 transition-all w-full md:w-auto text-xs">
                                            ✗ Faltou
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-3 pt-3 border-t border-slate-100">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <div class="flex gap-1">
                                            @foreach(['👍', '⚠️', '👎', '⭐'] as $emoji)
                                                <button type="button" onclick="addEmoji('{{ $student->id }}', '{{ $emoji }}')" 
                                                        class="w-9 h-9 flex items-center justify-center bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded-lg transition text-base">
                                                    {{ $emoji }}
                                                </button>
                                            @endforeach
                                        </div>
                                        <input type="text" 
                                               id="obs-{{ $student->id }}"
                                               name="obs[{{ $student->id }}]" 
                                               value="{{ old("obs.{$student->id}", $attendance?->obs) }}"
                                               placeholder="Adicionar observação..."
                                               class="flex-1 min-w-[150px] border border-slate-300 rounded-lg text-xs px-3 py-1.5 focus:ring-blue-500 focus:border-blue-500 focus:ring-2">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="sticky bottom-6 mt-8">
                        <button type="submit" class="w-full bg-slate-900 text-white px-8 py-4 rounded-xl font-bold hover:bg-slate-800 transition-all shadow-lg flex items-center justify-center gap-2 text-sm">
                            <span>💾</span> <span>Salvar Chamada</span>
                        </button>
                    </div>
                </form>
            @endif
        @else
            <div class="bg-white rounded-2xl p-16 text-center shadow-md border border-slate-300">
                <p class="text-slate-500">Selecione uma turma para carregar a lista de alunos.</p>
            </div>
        @endif
    </div>

    <style>
        .card-present { border-color: #10b981 !important; background-color: #f0fdf4; }
        .card-absent { border-color: #f43f5e !important; background-color: #fff1f2; }
        
        .btn-active-present { background-color: #10b981; color: white; border-color: #10b981; }
        .btn-inactive-present { background-color: #ecfdf5; color: #10b981; border-color: #86efac; }
        
        .btn-active-absent { background-color: #f43f5e; color: white; border-color: #f43f5e; }
        .btn-inactive-absent { background-color: #fff1f2; color: #f43f5e; border-color: #fda4af; }
    </style>

    <script>
        function setPresence(studentId, isPresent) {
            const card = document.querySelector(`.student-card[data-student-id="${studentId}"]`);
            const input = card.querySelector('.present-input');
            const btnP = card.querySelector('.btn-present');
            const btnA = card.querySelector('.btn-absent');

            input.value = isPresent;

            if (isPresent == 1) {
                card.className = 'student-card bg-white rounded-2xl p-4 shadow-md border border-slate-300 transition-all card-present';
                btnP.className = 'btn-present px-4 py-2 rounded-lg font-bold border transition-all btn-active-present w-full md:w-auto text-xs';
                btnA.className = 'btn-absent px-4 py-2 rounded-lg font-bold border transition-all btn-inactive-absent w-full md:w-auto text-xs';
            } else {
                card.className = 'student-card bg-white rounded-2xl p-4 shadow-md border border-slate-300 transition-all card-absent';
                btnP.className = 'btn-present px-4 py-2 rounded-lg font-bold border transition-all btn-inactive-present w-full md:w-auto text-xs';
                btnA.className = 'btn-absent px-4 py-2 rounded-lg font-bold border transition-all btn-active-absent w-full md:w-auto text-xs';
            }
        }

        function addEmoji(studentId, emoji) {
            const input = document.getElementById(`obs-${studentId}`);
            const current = input.value.trim();
            input.value = current ? `${current} ${emoji}` : emoji;
            input.focus();
        }

        document.getElementById('mark-all-present').addEventListener('click', () => {
            document.querySelectorAll('.student-card').forEach(card => {
                setPresence(card.dataset.studentId, 1);
            });
        });

        document.getElementById('mark-all-absent').addEventListener('click', () => {
            document.querySelectorAll('.student-card').forEach(card => {
                setPresence(card.dataset.studentId, 0);
            });
        });

        // Inicialização
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.student-card').forEach(card => {
                const val = card.querySelector('.present-input').value;
                setPresence(card.dataset.studentId, val);
            });
        });
    </script>
</x-app-layout> 