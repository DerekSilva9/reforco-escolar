<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                    <h2 class="font-semibold text-xl text-slate-900 dark:text-slate-50 leading-tight">
                        Perfil do aluno
                    </h2>
                    <div class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                </div>
            </div>

            <div class="flex flex-wrap gap-2">
                @if (auth()->user()?->isAdmin())
                    <a href="{{ route('alunos.edit', $student) }}" class="inline-flex items-center px-4 py-2 bg-blue-900 dark:bg-blue-800 rounded-md text-amber-50 hover:bg-blue-800 dark:hover:bg-blue-700 shadow-sm">
                        Editar
                    </a>

                    <form method="POST" action="{{ route('alunos.destroy', $student) }}" onsubmit="return confirm('Excluir este aluno? Isso apaga presenças e pagamentos vinculados.');">
                        @csrf
                        @method('DELETE')
                        <x-danger-button type="submit">Excluir</x-danger-button>
                    </form>
                @endif
                <a href="{{ route('alunos.index') }}" class="inline-flex items-center px-4 py-2 bg-amber-50 dark:bg-slate-700 border border-blue-200 dark:border-slate-600 rounded-md text-blue-950 dark:text-slate-200 hover:bg-amber-100 dark:hover:bg-slate-600 shadow-sm">
                    Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100 dark:border-slate-700">
                        <div class="p-6 text-slate-900 dark:text-slate-50">
                            <div class="flex items-center justify-between gap-4 mb-4">
                                <div class="font-semibold text-slate-900 dark:text-slate-50">Dados</div>
                                @if ($student->team)
                                    <a href="{{ route('presenca.index', ['team_id' => $student->team_id, 'date' => now()->toDateString()]) }}" class="text-sm text-slate-700 dark:text-slate-300 hover:text-blue-950 dark:hover:text-slate-100">
                                        Marcar presença (turma)
                                    </a>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <div class="text-slate-600 dark:text-slate-400">Responsável</div>
                                    <div class="font-medium">{{ $student->responsavel?->name ?? $student->parent_name ?? '-' }}</div>
                                </div>
                                <div>
                                    <div class="text-slate-600 dark:text-slate-400">Telefone</div>
                                    <div class="font-medium">{{ $student->responsavel?->phone ?? $student->phone ?? '-' }}</div>
                                </div>
                                <div>
                                    <div class="text-slate-600 dark:text-slate-400">Nascimento</div>
                                    <div class="font-medium">
                                        {{ $student->birth_date?->format('d/m/Y') ?? '-' }}
                                    </div>
                                </div>
                                <div>
                                    <div class="text-slate-600 dark:text-slate-400">Turma</div>
                                    <div class="font-medium">{{ $student->team?->name ?? '-' }}</div>
                                </div>
                                <div>
                                    <div class="text-slate-600 dark:text-slate-400">Mensalidade</div>
                                    <div class="font-medium">R$ {{ number_format((float) $student->fee, 2, ',', '.') }}</div>
                                </div>
                                <div>
                                    <div class="text-slate-600 dark:text-slate-400">Vencimento</div>
                                    <div class="font-medium">Dia {{ $student->due_day }}</div>
                                </div>
                                <div>
                                    <div class="text-slate-600">Status</div>
                                    <div class="font-medium">
                                        @if ($student->active)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-900 dark:text-emerald-300">Ativo</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-300">Inativo</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100 dark:border-slate-700">
                        <div class="p-6 text-slate-900 dark:text-slate-50">
                            <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                                <div class="font-semibold text-slate-900 dark:text-slate-50">Financeiro</div>

                                @if (auth()->user()?->isAdmin())
                                    @if ($currentMonthPayment)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-900 dark:text-emerald-300">
                                            Pago este mês
                                        </span>
                                    @else
                                        <form method="POST" action="{{ route('financeiro.pay', $student) }}">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-blue-900 rounded-md text-amber-50 hover:bg-blue-800 shadow-sm">
                                                Marcar como pago (mês atual)
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>

                            @if ($student->payments->isEmpty())
                                <div class="text-slate-600 dark:text-slate-400 text-sm">
                                    Nenhum pagamento registrado ainda.
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-sm">
                                        <thead>
                                            <tr class="text-left text-slate-600 dark:text-slate-400 border-b border-blue-100 dark:border-slate-700">
                                                <th class="py-2 pe-4">Competência</th>
                                                <th class="py-2 pe-4">Valor</th>
                                                <th class="py-2">Pago em</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($student->payments as $payment)
                                                <tr class="border-b border-blue-50 last:border-0">
                                                    <td class="py-3 pe-4 font-medium">
                                                        {{ str_pad((string) $payment->month, 2, '0', STR_PAD_LEFT) }}/{{ $payment->year }}
                                                    </td>
                                                    <td class="py-3 pe-4">R$ {{ number_format((float) $payment->amount, 2, ',', '.') }}</td>
                                                    <td class="py-3">{{ $payment->paid_at?->format('d/m/Y H:i') ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100 dark:border-slate-700">
                        <div class="p-6 text-slate-900 dark:text-slate-50">
                            <div class="font-semibold text-slate-900 dark:text-slate-50 mb-4">Presença</div>

                            @if ($student->attendances->isEmpty())
                                <div class="text-slate-600 dark:text-slate-400 text-sm">
                                    Nenhuma presença registrada ainda.
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full text-sm">
                                        <thead>
                                            <tr class="text-left text-slate-600 dark:text-slate-400 border-b border-blue-100 dark:border-slate-700">
                                                <th class="py-2 pe-4">Data</th>
                                                <th class="py-2 pe-4">Status</th>
                                                <th class="py-2">Obs</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($student->attendances as $attendance)
                                                <tr class="border-b border-blue-50 dark:border-slate-700 last:border-0">
                                                    <td class="py-3 pe-4 font-medium">{{ $attendance->date?->format('d/m/Y') }}</td>
                                                    <td class="py-3 pe-4">
                                                        @if ($attendance->present)
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-900/40 text-emerald-900 dark:text-emerald-300">Presente</span>
                                                        @else
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-100 dark:bg-rose-900/40 text-rose-900 dark:text-rose-300">Faltou</span>
                                                        @endif
                                                    </td>
                                                    <td class="py-3">{{ $attendance->obs ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100 dark:border-slate-700">
                        <div class="p-6 text-slate-900 dark:text-slate-50">
                            <div class="font-semibold text-slate-900 dark:text-slate-50 mb-4">Observações</div>

                            <form method="POST" action="{{ route('alunos.notes', $student) }}" class="space-y-3">
                                @csrf
                                <textarea name="notes" rows="8" class="block w-full border-blue-200 dark:border-slate-600 focus:border-blue-700 dark:focus:border-blue-500 focus:ring-blue-700 dark:focus:ring-blue-500 rounded-md shadow-sm bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-50 placeholder:text-slate-400 dark:placeholder:text-slate-500" placeholder="Observações rápidas sobre o aluno...">{{ old('notes', $student->notes) }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('notes')" />

                                <div class="flex items-center justify-between gap-3">
                                    <x-primary-button>Salvar</x-primary-button>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">
                                        Salva uma observação geral (não por dia).
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
