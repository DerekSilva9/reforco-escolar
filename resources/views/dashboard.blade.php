<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @php
                $modeValue = $mode ?? 'professor';
            @endphp

            @if ($modeValue === 'admin')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100">
                        <div class="p-6">
                            <div class="text-sm text-slate-600">Total de alunos</div>
                            <div class="mt-2 text-3xl font-semibold text-slate-900">{{ $totalStudentsCount }}</div>
                            <div class="mt-2 text-sm text-slate-600">
                                Gerencie turmas e cadastros
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100">
                        <div class="p-6">
                            <div class="text-sm text-slate-600">Mensalidades pendentes</div>
                            <div class="mt-2 text-3xl font-semibold text-slate-900">{{ $pendingFeesCount }}</div>
                            <div class="mt-2 text-sm text-slate-600">
                                Mês atual (não pagos)
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100">
                        <div class="p-6">
                            <div class="text-sm text-slate-600">Aulas hoje</div>
                            <div class="mt-2 text-3xl font-semibold text-slate-900">{{ $classesTodayCount }}</div>
                            <div class="mt-2 text-sm text-slate-600">
                                Chamadas registradas em {{ now()->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100 mb-6">
                    <div class="p-6 flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <div class="text-sm text-slate-600">Ação rápida</div>
                            <div class="text-lg font-semibold text-slate-900">Marcar presença</div>
                        </div>
                        <a href="{{ route('presenca.index') }}" class="inline-flex items-center px-6 py-3 bg-amber-200 rounded-md text-blue-950 hover:bg-amber-300 border border-amber-300 shadow-sm font-semibold">
                            Marcar presença
                        </a>
                    </div>
                </div>

                <div class="bg-white/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <div class="font-semibold text-slate-900">Últimos pagamentos</div>
                            <a href="{{ route('financeiro.index') }}" class="text-sm text-slate-700 hover:text-blue-950">
                                Ver financeiro
                            </a>
                        </div>

                        @if ($latestPayments->isEmpty())
                            <div class="text-slate-600">
                                Ainda não há pagamentos registrados.
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                        <tr class="text-left text-slate-600 border-b border-blue-100">
                                            <th class="py-2 pe-4">Aluno</th>
                                            <th class="py-2 pe-4">Turma</th>
                                            <th class="py-2 pe-4">Valor</th>
                                            <th class="py-2">Pago em</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($latestPayments as $payment)
                                            <tr class="border-b border-blue-50 last:border-0">
                                                <td class="py-3 pe-4 font-medium text-slate-900">
                                                    <a class="hover:underline" href="{{ route('alunos.show', $payment->student) }}">
                                                        {{ $payment->student?->name ?? '-' }}
                                                    </a>
                                                </td>
                                                <td class="py-3 pe-4">{{ $payment->student?->team?->name ?? '-' }}</td>
                                                <td class="py-3 pe-4">R$ {{ number_format((float) $payment->amount, 2, ',', '.') }}</td>
                                                <td class="py-3">{{ $payment->paid_at?->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @elseif ($modeValue === 'responsavel')
                <div class="bg-white/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100">
                    <div class="p-6">
                        <div class="text-sm text-slate-600">Área do responsável</div>
                        <div class="mt-2 text-lg font-semibold text-slate-900">Em breve</div>
                        <div class="mt-2 text-sm text-slate-600">
                            Aqui você vai acompanhar presença, mensalidades e observações dos alunos vinculados.
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100">
                    <div class="p-6">
                        <div class="flex items-center justify-between gap-4 mb-4">
                            <div>
                                <div class="text-sm text-slate-600">Suas turmas</div>
                                <div class="text-lg font-semibold text-slate-900">Marcar presença hoje</div>
                            </div>
                            <a href="{{ route('presenca.index') }}" class="inline-flex items-center px-4 py-2 bg-amber-200 rounded-md text-blue-950 hover:bg-amber-300 border border-amber-300 shadow-sm font-semibold">
                                Marcar presença
                            </a>
                        </div>

                        @if ($teams->isEmpty())
                            <div class="text-slate-600">
                                Você ainda não tem turmas cadastradas.
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm">
                                    <thead>
                                        <tr class="text-left text-slate-600 border-b border-blue-100">
                                            <th class="py-2 pe-4">Turma</th>
                                            <th class="py-2 pe-4">Turno</th>
                                            <th class="py-2 pe-4">Alunos</th>
                                            <th class="py-2 pe-4">Hoje</th>
                                            <th class="py-2">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($teams as $team)
                                            @php
                                                $doneToday = $teamsWithAttendanceToday?->contains($team->id);
                                            @endphp
                                            <tr class="border-b border-blue-50 last:border-0">
                                                <td class="py-3 pe-4 font-medium text-slate-900">{{ $team->name }}</td>
                                                <td class="py-3 pe-4">{{ $team->time }}</td>
                                                <td class="py-3 pe-4">{{ $team->students_count }}</td>
                                                <td class="py-3 pe-4">
                                                    @if ($doneToday)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-900">Feita</span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-900">Pendente</span>
                                                    @endif
                                                </td>
                                                <td class="py-3">
                                                    <a href="{{ route('presenca.index', ['team_id' => $team->id, 'date' => now()->toDateString()]) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-900 rounded-md text-amber-50 hover:bg-blue-800 shadow-sm">
                                                        Marcar presença
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
