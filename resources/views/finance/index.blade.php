<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-slate-900 leading-tight">
                    Financeiro
                </h2>
                <div class="text-sm text-slate-600 mt-1">
                    {{ str_pad((string) $month, 2, '0', STR_PAD_LEFT) }}/{{ $year }}
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
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('financeiro.index', ['status' => 'pendentes']) }}"
                            class="px-3 py-2 rounded-md border text-sm font-semibold {{ $status === 'pendentes' ? 'bg-amber-200 border-amber-300 text-blue-950' : 'bg-white border-blue-200 text-slate-700 hover:bg-amber-50' }}">
                            Pendentes ({{ $counts['pendentes'] }})
                        </a>
                        <a href="{{ route('financeiro.index', ['status' => 'atrasados']) }}"
                            class="px-3 py-2 rounded-md border text-sm font-semibold {{ $status === 'atrasados' ? 'bg-amber-200 border-amber-300 text-blue-950' : 'bg-white border-blue-200 text-slate-700 hover:bg-amber-50' }}">
                            Atrasados ({{ $counts['atrasados'] }})
                        </a>
                        <a href="{{ route('financeiro.index', ['status' => 'pagos']) }}"
                            class="px-3 py-2 rounded-md border text-sm font-semibold {{ $status === 'pagos' ? 'bg-amber-200 border-amber-300 text-blue-950' : 'bg-white border-blue-200 text-slate-700 hover:bg-amber-50' }}">
                            Pagos ({{ $counts['pagos'] }})
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100">
                <div class="p-6 text-slate-900">
                    @if ($rows->isEmpty())
                        <div class="text-slate-600">
                            Nada para mostrar nesse filtro.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="text-left text-slate-600 border-b border-blue-100">
                                        <th class="py-2 pe-4">Aluno</th>
                                        <th class="py-2 pe-4">Turma</th>
                                        <th class="py-2 pe-4">Venc.</th>
                                        <th class="py-2 pe-4">Valor</th>
                                        <th class="py-2 pe-4">Status</th>
                                        <th class="py-2">Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($rows as $row)
                                        @php
                                            $student = $row['student'];
                                            $dueDate = \Illuminate\Support\Carbon::parse($row['due_date'])->format('d/m/Y');
                                            $badge = match ($row['status']) {
                                                'pago' => 'bg-emerald-100 text-emerald-900',
                                                'atrasado' => 'bg-rose-100 text-rose-900',
                                                default => 'bg-amber-100 text-amber-900',
                                            };
                                            $statusLabel = match ($row['status']) {
                                                'pago' => 'Pago',
                                                'atrasado' => 'Atrasado',
                                                default => 'Pendente',
                                            };
                                        @endphp
                                        <tr class="border-b border-blue-50 last:border-0">
                                            <td class="py-3 pe-4 font-medium text-slate-900">
                                                <a class="hover:underline" href="{{ route('alunos.show', $student) }}">
                                                    {{ $student->name }}
                                                </a>
                                            </td>
                                            <td class="py-3 pe-4">{{ $student->team?->name ?? '-' }}</td>
                                            <td class="py-3 pe-4">{{ $dueDate }}</td>
                                            <td class="py-3 pe-4">R$ {{ number_format((float) $student->fee, 2, ',', '.') }}</td>
                                            <td class="py-3 pe-4">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $badge }}">
                                                    {{ $statusLabel }}
                                                </span>
                                            </td>
                                            <td class="py-3">
                                                @if ($row['status'] === 'pago')
                                                    <div class="text-xs text-slate-500">
                                                        Pago em {{ $row['payment']?->paid_at?->format('d/m/Y H:i') }}
                                                    </div>
                                                @else
                                                    <form method="POST" action="{{ route('financeiro.pay', $student) }}">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-blue-900 rounded-md text-amber-50 hover:bg-blue-800 shadow-sm">
                                                            Pagar
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

