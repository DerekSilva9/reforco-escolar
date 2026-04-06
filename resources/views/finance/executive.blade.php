<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-900 dark:text-slate-50 tracking-tight flex items-center gap-2">
                    <span class="w-2 h-6 bg-emerald-600 rounded-full"></span>
                    Financeiro Executivo
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium ml-4">Dashboard gerencial da escola</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-3 md:px-6 lg:px-8 py-4 md:py-6 lg:py-8">
        <!-- Month Selector -->
        <div class="mb-6 flex flex-col sm:flex-row gap-2 sm:gap-3">
            <form method="GET" action="{{ route('financeiro.executive') }}" class="flex flex-col sm:flex-row gap-2 sm:gap-3 items-stretch sm:items-center">
                <select name="month" class="text-sm border border-slate-300 dark:border-slate-600 rounded-lg px-3 py-2 focus:border-emerald-500 dark:focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 dark:focus:ring-emerald-500/30 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-50">
                    @foreach ($available_months as $m)
                        <option value="{{ $m->month }}" @selected($m->month === $month && $m->year === $year)>
                            {{ $m->translatedFormat('F Y') }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="px-3 py-2 text-sm sm:text-base bg-emerald-600 dark:bg-emerald-700 rounded-lg font-semibold text-white hover:bg-emerald-700 dark:hover:bg-emerald-600 transition">
                    Filtrar
                </button>
            </form>
            <a href="{{ route('financeiro.executive') }}" class="px-3 py-2 text-sm sm:text-base text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 font-semibold text-center sm:text-left">
                Hoje
            </a>
        </div>

        <!-- KPI Cards -->
        <div class="grid gap-3 sm:gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 mb-6">
            <!-- Receita -->
            <div class="bg-white dark:bg-slate-800 rounded-lg p-4 sm:p-6 shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-400">Receita do Mês</span>
                    <span class="text-xl sm:text-2xl">💰</span>
                </div>
                <div class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-slate-50">
                    R$ {{ number_format($kpis['revenue'], 2, ',', '.') }}
                </div>
                <div class="mt-2 flex items-center justify-between">
                    <span class="text-xs text-slate-600 dark:text-slate-400">Meta: R$ {{ number_format($kpis['target'], 2, ',', '.') }}</span>
                    @if ($kpis['revenue_percentage'] >= 90)
                        <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ $kpis['revenue_percentage'] }}% ↑</span>
                    @elseif ($kpis['revenue_percentage'] >= 70)
                        <span class="text-xs font-bold text-yellow-600 dark:text-yellow-400">{{ $kpis['revenue_percentage'] }}% →</span>
                    @else
                        <span class="text-xs font-bold text-red-600 dark:text-red-400">{{ $kpis['revenue_percentage'] }}% ↓</span>
                    @endif
                </div>
            </div>

            <!-- Inadimplência -->
            <div class="bg-white dark:bg-slate-800 rounded-lg p-4 sm:p-6 shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-400">Inadimplência</span>
                    <span class="text-xl sm:text-2xl">⚠️</span>
                </div>
                <div class="text-2xl sm:text-3xl font-bold text-red-600 dark:text-red-400">
                    R$ {{ number_format($kpis['delinquency_amount'], 2, ',', '.') }}
                </div>
                <div class="mt-2 text-xs text-slate-600 dark:text-slate-400">
                    {{ $kpis['delinquency_count'] }} aluno{{ $kpis['delinquency_count'] !== 1 ? 's' : '' }} atrasado{{ $kpis['delinquency_count'] !== 1 ? 's' : '' }}
                </div>
            </div>

            <!-- Ocupação -->
            <div class="bg-white dark:bg-slate-800 rounded-lg p-4 sm:p-6 shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-400">Alunos Ativos</span>
                    <span class="text-xl sm:text-2xl">👥</span>
                </div>
                <div class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-slate-50">
                    {{ $kpis['occupancy_rate']['active'] }}
                </div>
                <div class="mt-2 text-xs text-slate-600 dark:text-slate-400">
                    Aluno{{ $kpis['occupancy_rate']['active'] !== 1 ? 's' : '' }} matriculado{{ $kpis['occupancy_rate']['active'] !== 1 ? 's' : '' }}
                </div>
            </div>

            <!-- Novos Alunos -->
            <div class="bg-white dark:bg-slate-800 rounded-lg p-4 sm:p-6 shadow-sm border border-slate-200 dark:border-slate-700">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-400">Novos Alunos</span>
                    <span class="text-xl sm:text-2xl">🆕</span>
                </div>
                <div class="text-2xl sm:text-3xl font-bold text-cyan-600 dark:text-cyan-400">
                    +{{ $kpis['new_students'] }}
                </div>
                <div class="mt-2 text-xs text-slate-600 dark:text-slate-400">
                    neste mês
                </div>
            </div>
        </div>

        <!-- Gráfico de Receita (Texto/Tabela para simplicidade) -->
        <div class="bg-white dark:bg-slate-800 rounded-lg p-4 sm:p-6 shadow-sm border border-slate-200 dark:border-slate-700 mb-6">
            <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-slate-50 mb-4">Receita vs Meta (últimos 6 meses)</h2>
            <div class="space-y-3">
                @foreach ($revenue_trend as $period)
                    <div>
                        <div class="flex justify-between mb-1">
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                                {{ $period['full_month'] }} {{ $period['year'] }}
                            </span>
                            <span class="text-sm font-semibold text-slate-900 dark:text-slate-50">
                                R$ {{ number_format($period['revenue'], 2, ',', '.') }}
                            </span>
                        </div>
                        <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2">
                            <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ min(100, ($period['revenue'] / max(1, $period['target'])) * 100) }}%"></div>
                        </div>
                        <div class="flex justify-between mt-1">
                            <span class="text-xs text-slate-500 dark:text-slate-400">Meta: R$ {{ number_format($period['target'], 2, ',', '.') }}</span>
                            <span class="text-xs font-semibold {{ ($period['revenue'] / max(1, $period['target'])) >= 0.9 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ round(($period['revenue'] / max(1, $period['target'])) * 100, 0) }}%
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Devedores Tab -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 mb-6">
            <div class="px-4 sm:px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-slate-50">
                    ⚠️ Devedores ({{ count($delinquents) }})
                </h2>
            </div>
            
            @if (count($delinquents) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-xs sm:text-sm">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-700 border-b border-slate-200 dark:border-slate-600">
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left font-bold text-slate-900 dark:text-slate-100 uppercase">Aluno</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left font-bold text-slate-900 dark:text-slate-100 uppercase hidden sm:table-cell">Responsável</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left font-bold text-slate-900 dark:text-slate-100 uppercase">Período</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left font-bold text-slate-900 dark:text-slate-100 uppercase">Valor</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left font-bold text-slate-900 dark:text-slate-100 uppercase hidden md:table-cell">Dias</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left font-bold text-slate-900 dark:text-slate-100 uppercase">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach ($delinquents as $debtor)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                                    <td class="px-3 sm:px-6 py-3 font-semibold text-slate-900 dark:text-slate-50">
                                        {{ $debtor['student_name'] }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 text-slate-600 dark:text-slate-400 hidden sm:table-cell">
                                        {{ $debtor['responsavel_name'] }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 text-slate-600 dark:text-slate-400">
                                        {{ $debtor['period'] }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 font-semibold text-red-600 dark:text-red-400">
                                        R$ {{ number_format($debtor['amount'], 2, ',', '.') }}
                                    </td>
                                    <td class="px-3 sm:px-6 py-3 hidden md:table-cell">
                                        <span class="inline-flex px-2 sm:px-3 py-1 rounded-full text-xs font-bold {{ $debtor['days_overdue'] > 60 ? 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-100' : ($debtor['days_overdue'] > 30 ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-100' : 'bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-100') }}">
                                            {{ $debtor['days_overdue'] }}
                                        </span>
                                    </td>
                                    <td class="px-3 sm:px-6 py-3">
                                        @if ($debtor['responsavel_phone'])
                                            <a href="https://wa.me/55{{ preg_replace('/[^0-9]/', '', $debtor['responsavel_phone']) }}?text=Olá%20{{ urlencode($debtor['responsavel_name']) }},%20temos%20uma%20mensalidade%20pendente%20de%20{{ urlencode($debtor['period']) }}%20referente%20ao%20aluno%20{{ urlencode($debtor['student_name']) }}%20no%20valor%20de%20R$%20{{ number_format($debtor['amount'], 2) }}.%20Pode%20regularizar?" target="_blank" rel="noreferrer" class="inline-flex items-center gap-1 px-2 sm:px-3 py-1.5 bg-green-600 dark:bg-green-700 rounded-lg text-white text-xs font-semibold hover:bg-green-700 dark:hover:bg-green-600 transition">
                                                💬
                                            </a>
                                        @else
                                            <span class="text-xs text-slate-500">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-4 sm:px-6 py-8 text-center">
                    <p class="text-slate-600 dark:text-slate-400 text-base sm:text-lg">✓ Nenhum devedor! Todos os pagamentos em dia.</p>
                </div>
            @endif
        </div>

        <!-- Ocupação por Turma -->
        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
            <div class="px-4 sm:px-6 py-4 border-b border-slate-200 dark:border-slate-700">
                <h2 class="text-base sm:text-lg font-bold text-slate-900 dark:text-slate-50">Alunos por Turma</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-700 border-b border-slate-200 dark:border-slate-600">
                            <th class="px-3 sm:px-6 py-2 sm:py-3 text-left font-bold text-slate-900 dark:text-slate-100 uppercase">Turma</th>
                            <th class="px-3 sm:px-6 py-2 sm:py-3 text-left font-bold text-slate-900 dark:text-slate-100 uppercase">Alunos</th>
                            <th class="px-3 sm:px-6 py-2 sm:py-3 text-left font-bold text-slate-900 dark:text-slate-100 uppercase">Receita</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @foreach ($occupancy_by_team as $team)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                                <td class="px-3 sm:px-6 py-3">
                                    <div class="font-semibold text-slate-900 dark:text-slate-50">{{ $team['team_name'] }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ $team['team_time'] }}</div>
                                </td>
                                <td class="px-3 sm:px-6 py-3">
                                    <span class="inline-flex px-2 sm:px-3 py-1 rounded bg-emerald-100 dark:bg-emerald-900 text-emerald-800 dark:text-emerald-100 font-semibold text-xs sm:text-sm">
                                        {{ $team['occupied'] }}
                                    </span>
                                </td>
                                <td class="px-3 sm:px-6 py-3 font-semibold text-slate-900 dark:text-slate-50">
                                    R$ {{ number_format($team['revenue'], 2, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
