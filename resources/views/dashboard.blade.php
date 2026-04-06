<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between py-2">
            <div>
                <h1 class="text-xl font-bold text-slate-900 dark:text-slate-50 tracking-tight flex items-center gap-2">
                    <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                    Painel de Controle
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium ml-4">{{ now()->translatedFormat('l, d \d\e F') }}</p>
            </div>
            
            <a href="{{ route('profile.edit') }}" class="group flex items-center gap-3 p-1.5 pr-4 bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl hover:border-blue-400 dark:hover:border-blue-600 hover:shadow-md transition-all">
                <div class="w-8 h-8 bg-slate-900 dark:bg-slate-700 rounded-lg flex items-center justify-center text-white font-bold text-xs group-hover:bg-blue-600 transition-colors">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] uppercase tracking-wider font-bold text-slate-400 dark:text-slate-300 leading-none">Acesso</span>
                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 leading-tight">Meu Perfil</span>
                </div>
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-3 md:px-6 lg:px-8 py-4 md:py-6 lg:py-8">
        @php $modeValue = $mode ?? 'admin'; @endphp
        @php $noticeList = $notices ?? collect(); @endphp

        @if ($noticeList->isNotEmpty())
            <div class="mb-8 bg-white dark:bg-slate-800 rounded-2xl shadow-md border border-slate-300 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between gap-4 bg-slate-50/50 dark:bg-slate-700/50">
                    <div>
                        <h3 class="text-xs font-bold text-slate-600 dark:text-slate-100 uppercase tracking-widest">Mural de avisos</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Comunicados oficiais da escola.</p>
                    </div>
                    @if (Auth::user()?->isAdmin())
                        <a href="{{ route('admin.notices.index') }}" class="text-[11px] font-bold text-blue-700 dark:text-blue-300 hover:underline">Gerenciar</a>
                    @endif
                </div>
                <div class="p-6 space-y-4">
                    @foreach ($noticeList as $notice)
                        <div class="p-4 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/60 dark:bg-slate-900/20">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <div class="font-bold text-slate-900 dark:text-slate-50">{{ $notice->title }}</div>
                                        @if ($notice->pinned)
                                            <span class="text-[10px] font-bold uppercase tracking-widest px-2 py-1 rounded-full bg-amber-200 dark:bg-amber-700 text-blue-950 dark:text-amber-50">Fixado</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                        {{ $notice->published_at?->format('d/m/Y H:i') ?? '' }}
                                        @if ($notice->starts_at || $notice->ends_at)
                                            • {{ $notice->starts_at?->format('d/m/Y H:i') ?? '-' }} → {{ $notice->ends_at?->format('d/m/Y H:i') ?? '-' }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="text-sm text-slate-700 dark:text-slate-200 mt-3 whitespace-pre-line">{{ $notice->body }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($modeValue === 'admin')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-md border border-slate-300 dark:border-slate-700 transition-all dark:hover:border-blue-600 hover:shadow-lg">
                    <p class="text-[11px] font-bold text-slate-400 dark:text-slate-300 uppercase tracking-widest">Total de Alunos</p>
                    <div class="flex items-end justify-between mt-2">
                        <h2 class="text-4xl font-bold text-slate-900 dark:text-slate-50">{{ $totalStudentsCount }}</h2>
                        <span class="text-[10px] font-bold text-blue-600 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/30 px-2 py-1 rounded-md border border-blue-100 dark:border-blue-800 uppercase">Matriculados</span>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-md border border-slate-300 dark:border-slate-700">
                    <p class="text-[11px] font-bold text-amber-600/80 dark:text-amber-400/80 uppercase tracking-widest">Pendências</p>
                    <div class="flex items-center justify-between mt-2">
                        <h2 class="text-4xl font-bold text-slate-900 dark:text-slate-50">{{ $pendingFeesCount }}</h2>
                        <div class="p-2 bg-amber-50 dark:bg-amber-900/30 rounded-lg text-amber-600 dark:text-amber-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-md border border-slate-300 dark:border-slate-700">
                    <p class="text-[11px] font-bold text-indigo-600/80 dark:text-indigo-400/80 uppercase tracking-widest">Aulas Hoje</p>
                    <div class="flex items-center justify-between mt-2">
                        <h2 class="text-4xl font-bold text-slate-900 dark:text-slate-50">{{ $classesTodayCount }}</h2>
                        <div class="p-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg text-indigo-600 dark:text-indigo-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <a href="{{ route('presenca.index') }}" class="group p-6 bg-slate-900 dark:bg-slate-800 rounded-2xl hover:bg-slate-800 dark:hover:bg-slate-700 transition-all shadow-lg border border-slate-800 dark:border-slate-700">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Fazer Chamada</h3>
                            <p class="text-slate-400 dark:text-slate-300 text-xs font-medium uppercase tracking-wider">Controle de presença diária</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('financeiro.index') }}" class="group p-6 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-2xl dark:hover:border-blue-600 hover:shadow-lg transition-all">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded-xl flex items-center justify-center text-slate-600 dark:text-slate-400 group-hover:bg-emerald-500 dark:group-hover:bg-emerald-600 group-hover:text-white transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-50">Financeiro</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-xs font-medium uppercase tracking-wider">Gestão de mensalidades</p>
                        </div>
                    </div>
                </a>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-md border border-slate-300 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-700/50">
                    <h3 class="text-xs font-bold text-slate-600 dark:text-slate-100 uppercase tracking-widest">Pagamentos Recentes</h3>
                    <a href="{{ route('financeiro.index') }}" class="text-[11px] font-bold text-blue-600 dark:text-blue-400 hover:underline">Ver tudo</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-bold text-slate-600 dark:text-slate-100 uppercase tracking-widest">
                                <th class="px-6 py-3">Aluno</th>
                                <th class="px-6 py-3 text-right">Valor</th>
                                <th class="px-6 py-3 text-right">Data</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @forelse ($latestPayments->take(5) as $payment)
                                <tr class="hover:bg-slate-100/60 dark:hover:bg-slate-700/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center text-[10px] font-bold text-slate-500 dark:text-slate-300 border border-slate-300 dark:border-slate-600">
                                                {{ substr($payment->student->name, 0, 2) }}
                                            </div>
                                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $payment->student->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-bold text-slate-900 dark:text-slate-50">
                                        R$ {{ number_format($payment->amount, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-xs font-medium text-slate-600 dark:text-slate-300">
                                        {{ $payment->paid_at->format('d/m/y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center text-slate-400 dark:text-slate-500 text-xs font-medium italic">Sem registros.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @elseif ($modeValue === 'professor')
            <!-- Cards de Resumo -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-md border border-slate-300 dark:border-slate-700 transition-all dark:hover:border-blue-600 hover:shadow-lg">
                    <p class="text-[11px] font-bold text-slate-400 dark:text-slate-300 uppercase tracking-widest">Total de Alunos</p>
                    <div class="flex items-end justify-between mt-2">
                        <h2 class="text-4xl font-bold text-slate-900 dark:text-slate-50">{{ $totalStudentsCount }}</h2>
                        <span class="text-[10px] font-bold text-blue-600 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/30 px-2 py-1 rounded-md border border-blue-100 dark:border-blue-800 uppercase">Ativos</span>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-md border border-slate-300 dark:border-slate-700">
                    <p class="text-[11px] font-bold text-green-600/80 dark:text-green-400/80 uppercase tracking-widest">Taxa de Presença</p>
                    <div class="flex items-end justify-between mt-2">
                        <h2 class="text-4xl font-bold text-slate-900 dark:text-slate-50">{{ $attendanceRate }}%</h2>
                        <span class="text-[10px] font-bold text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/30 px-2 py-1 rounded-md border border-green-100 dark:border-green-800 uppercase">Este mês</span>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-md border border-slate-300 dark:border-slate-700">
                    <p class="text-[11px] font-bold text-indigo-600/80 dark:text-indigo-400/80 uppercase tracking-widest">Minhas Turmas</p>
                    <div class="flex items-end justify-between mt-2">
                        <h2 class="text-4xl font-bold text-slate-900 dark:text-slate-50">{{ $teams->count() }}</h2>
                        <div class="p-2 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg text-indigo-600 dark:text-indigo-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <a href="{{ route('presenca.index') }}" class="group p-6 bg-slate-900 dark:bg-slate-800 rounded-2xl hover:bg-slate-800 dark:hover:bg-slate-700 transition-all shadow-lg border border-slate-800 dark:border-slate-700">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Fazer Chamada</h3>
                            <p class="text-slate-400 dark:text-slate-300 text-xs font-medium uppercase tracking-wider">Controle de presença diária</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('alunos.index') }}" class="group p-6 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-2xl dark:hover:border-blue-600 hover:shadow-lg transition-all">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded-xl flex items-center justify-center text-slate-600 dark:text-slate-400 group-hover:bg-purple-500 dark:group-hover:bg-purple-600 group-hover:text-white transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-slate-50">Meus Alunos</h3>
                            <p class="text-slate-500 dark:text-slate-400 text-xs font-medium uppercase tracking-wider">Visualizar e gerenciar</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Minhas Turmas -->
            @if($teams->isNotEmpty())
            <div class="mb-8">
                <h3 class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-widest mb-4">Minhas Turmas</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($teams as $team)
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-300 dark:border-slate-700 shadow-md hover:shadow-lg transition-all">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h4 class="text-base font-bold text-slate-900 dark:text-slate-50">{{ $team->name }}</h4>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">
                                    {{ $team->time ?? 'Sem horário' }}
                                </p>
                            </div>
                            @if($teamsWithAttendanceToday->contains($team->id))
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-green-600 dark:bg-green-700 text-white dark:text-white text-[10px] font-bold border border-green-500 dark:border-green-600 shadow-sm">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                Aula hoje
                            </span>
                            @endif
                        </div>
                        <div class="pt-3 border-t border-slate-200 dark:border-slate-700">
                            <p class="text-2xl font-bold text-slate-900 dark:text-slate-50">{{ $team->students_count }}</p>
                            <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">alunos ativos</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Alertas -->
            @if($teamsWithoutAttendanceToday->isNotEmpty() || $studentsWithHighAbsence->isNotEmpty())
            <div class="mb-8 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-2xl p-6">
                <h3 class="text-sm font-bold text-amber-900 dark:text-amber-200 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    Atenção
                </h3>

                @if($teamsWithoutAttendanceToday->isNotEmpty())
                <div class="mb-4">
                    <p class="text-sm font-semibold text-amber-900 dark:text-amber-100 mb-2">Turmas sem chamada marcada hoje:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($teamsWithoutAttendanceToday as $team)
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-sm font-medium border border-amber-300 dark:border-amber-700">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            {{ $team->name }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($studentsWithHighAbsence->isNotEmpty())
                <div>
                    <p class="text-sm font-semibold text-amber-900 dark:text-amber-100 mb-2">Alunos com muitas faltas (>20% este mês):</p>
                    <div class="space-y-2">
                        @foreach($studentsWithHighAbsence->take(5) as $student)
                        <div class="flex items-center justify-between p-2 rounded-lg bg-white dark:bg-slate-800">
                            <span class="text-sm text-slate-700 dark:text-slate-300 font-medium">{{ $student->name }}</span>
                            <span class="text-xs font-bold text-amber-700 dark:text-amber-300">{{ $student->team->name }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Presença Recente -->
            @if($recentAttendances->isNotEmpty())
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-md border border-slate-300 dark:border-slate-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-700/50">
                    <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Presença Recente</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50 dark:bg-slate-700/50">
                                <th class="px-6 py-3">Aluno</th>
                                <th class="px-6 py-3">Turma</th>
                                <th class="px-6 py-3">Data</th>
                                <th class="px-6 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            @forelse ($recentAttendances as $attendance)
                                <tr class="hover:bg-slate-100/60 dark:hover:bg-slate-700/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-slate-100 dark:bg-slate-700 rounded-lg flex items-center justify-center text-[10px] font-bold text-slate-500 dark:text-slate-300 border border-slate-300 dark:border-slate-600">
                                                {{ substr($attendance->student->name, 0, 2) }}
                                            </div>
                                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $attendance->student->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm text-slate-600 dark:text-slate-400">{{ $attendance->student->team->name }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-xs font-medium text-slate-500 dark:text-slate-400">
                                            {{ $attendance->date->format('d/m/y') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($attendance->present)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-green-600 dark:bg-green-700 text-white dark:text-white text-[11px] font-bold border border-green-500 dark:border-green-600 shadow-sm">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Presente
                                        </span>
                                        @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-red-800 dark:bg-red-700 text-white text-[11px] font-bold border border-red-700 dark:border-red-600 shadow-sm" style="background-color: #991b1b;">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            Ausente
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-slate-400 dark:text-slate-500 text-xs font-medium italic">Sem registros de presença.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="bg-slate-100 dark:bg-slate-700/50 rounded-2xl p-8 text-center border border-slate-200 dark:border-slate-600">
                <svg class="w-12 h-12 text-slate-400 dark:text-slate-500 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M19 11a8 8 0 11-16 0 8 8 0 0116 0zm-9-3h.01M12 13h.01M15 11h.01M9 11h.01"></path></svg>
                <p class="text-slate-600 dark:text-slate-300 text-sm font-medium">Ainda não há registros de presença</p>
            </div>
            @endif
            @elseif($modeValue === 'responsavel')
            <div x-data="{ tab: (localStorage.getItem('resp_dash_tab') || 'resumo') }"
                 x-init="$watch('tab', value => localStorage.setItem('resp_dash_tab', value))"
                 class="space-y-8">
                <div class="inline-flex rounded-2xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-1 shadow-sm w-full md:w-auto">
                    <button type="button"
                            @click="tab = 'resumo'"
                            :class="tab === 'resumo' ? 'bg-slate-900 dark:bg-slate-700 text-white' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50'"
                            class="flex-1 md:flex-none px-4 py-2 rounded-xl text-sm font-bold transition-colors">
                        Resumo
                    </button>
                    <button type="button"
                            @click="tab = 'desempenho'"
                            :class="tab === 'desempenho' ? 'bg-slate-900 dark:bg-slate-700 text-white' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50'"
                            class="flex-1 md:flex-none px-4 py-2 rounded-xl text-sm font-bold transition-colors">
                        Desempenho
                    </button>
                    <button type="button"
                            @click="tab = 'observacoes'"
                            :class="tab === 'observacoes' ? 'bg-slate-900 dark:bg-slate-700 text-white' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700/50'"
                            class="flex-1 md:flex-none px-4 py-2 rounded-xl text-sm font-bold transition-colors">
                        Observações
                    </button>
                </div>

                <div x-show="tab === 'resumo'" class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-md border border-slate-300 dark:border-slate-700">
                        <p class="text-[11px] font-bold text-slate-400 dark:text-slate-300 uppercase tracking-widest">Alunos Vinculados</p>
                        <div class="flex items-end justify-between mt-2">
                            <h2 class="text-4xl font-bold text-slate-900 dark:text-slate-50">{{ $children->count() }}</h2>
                            <span class="text-[10px] font-bold text-blue-600 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/30 px-2 py-1 rounded-md border border-blue-100 dark:border-blue-800 uppercase">Dependentes</span>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-md border border-slate-300 dark:border-slate-700">
                        <p class="text-[11px] font-bold text-amber-600/80 dark:text-amber-400/80 uppercase tracking-widest">Mensalidades (Mês)</p>
                        <div class="flex items-end justify-between mt-2">
                            <div class="space-y-1">
                                <div class="text-sm font-bold text-slate-900 dark:text-slate-50">
                                    {{ ($paymentsCounts['pendentes'] ?? 0) + ($paymentsCounts['atrasados'] ?? 0) }} pendente(s)
                                </div>
                                <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                                    {{ $paymentsCounts['atrasados'] ?? 0 }} atrasada(s) • {{ $paymentsCounts['pagos'] ?? 0 }} paga(s)
                                </div>
                            </div>
                            <div class="p-2 bg-amber-50 dark:bg-amber-900/30 rounded-lg text-amber-600 dark:text-amber-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-md border border-slate-300 dark:border-slate-700">
                        <p class="text-[11px] font-bold text-emerald-600/80 dark:text-emerald-400/80 uppercase tracking-widest">Presença (Mês)</p>
                        <div class="flex items-end justify-between mt-2">
                            <div>
                                <div class="text-4xl font-bold text-slate-900 dark:text-slate-50">{{ number_format($overallAttendanceRate ?? 0, 1, ',', '.') }}%</div>
                                <div class="text-[10px] font-bold text-slate-400 dark:text-slate-300 uppercase tracking-widest mt-1">média geral</div>
                            </div>
                            <div class="p-2 bg-emerald-50 dark:bg-emerald-900/30 rounded-lg text-emerald-600 dark:text-emerald-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    @forelse($children as $student)
                        @php
                            $billing = $billingByStudent[$student->id] ?? null;
                            $billingStatus = $billing['status'] ?? 'pendente';
                            $billingDueDate = $billing['due_date'] ?? null;
                            $billingPaidAt = $billing['paid_at'] ?? null;
                            $billingAmount = $billing['amount'] ?? $student->fee;
                            $billingMethod = $billing['method'] ?? null;

                            $stats = $attendanceStatsByStudent[$student->id] ?? ['total' => 0, 'present' => 0, 'absent' => 0, 'last_date' => null];
                            $monthRate = ($stats['total'] ?? 0) > 0 ? round(($stats['present'] / $stats['total']) * 100, 1) : null;

                            $startTime = $student->class_start_time ? \Illuminate\Support\Carbon::parse($student->class_start_time)->format('H:i') : null;
                            $endTime = $student->class_end_time ? \Illuminate\Support\Carbon::parse($student->class_end_time)->format('H:i') : null;
                        @endphp

                        <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                            <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h2 class="text-lg font-bold text-slate-900 dark:text-slate-50">{{ $student->name }}</h2>
                                        <p class="text-xs text-slate-500 font-medium">
                                            Turma: {{ $student->team->name }} • {{ $student->team->time }}
                                            @if($startTime && $endTime)
                                                • {{ $startTime }}–{{ $endTime }}
                                            @endif
                                        </p>
                                        <p class="text-xs text-slate-500 font-medium mt-1">
                                            Professor(a): {{ $student->team->teacher?->name ?? '-' }}{{ $student->team->teacher?->phone ? ' • '.$student->team->teacher->phone : '' }}
                                        </p>
                                    </div>
                                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 text-blue-600 rounded-2xl flex items-center justify-center font-bold text-xl">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 space-y-6">
                                @php
                                    $billingBoxClasses = match ($billingStatus) {
                                        'pago' => 'bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-800',
                                        'atrasado' => 'bg-rose-50 dark:bg-rose-900/10 border border-rose-100 dark:border-rose-800',
                                        default => 'bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-800',
                                    };

                                    $billingTextClasses = match ($billingStatus) {
                                        'pago' => 'text-emerald-700',
                                        'atrasado' => 'text-rose-700',
                                        default => 'text-amber-700',
                                    };

                                    $billingLabel = match ($billingStatus) {
                                        'pago' => 'Pagamento Confirmado',
                                        'atrasado' => 'Mensalidade Atrasada',
                                        default => 'Aguardando Pagamento',
                                    };

                                    $billingIcon = match ($billingStatus) {
                                        'pago' => '✅',
                                        'atrasado' => '⚠️',
                                        default => '⏳',
                                    };
                                @endphp

                                <div class="flex items-center justify-between p-4 rounded-2xl {{ $billingBoxClasses }}">
                                    <div>
                                        <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Mensalidade de {{ now()->translatedFormat('F') }}</p>
                                        <p class="text-sm font-bold {{ $billingTextClasses }}">{{ $billingLabel }}</p>
                                        <p class="text-xs text-slate-500 font-medium mt-1">
                                            Valor: R$ {{ number_format($billingAmount, 2, ',', '.') }}
                                            @if($billingStatus === 'pago' && $billingPaidAt)
                                                • Pago em {{ $billingPaidAt->format('d/m') }}
                                            @elseif($billingDueDate)
                                                • {{ $billingStatus === 'atrasado' ? 'Venceu em' : 'Vence em' }} {{ $billingDueDate->format('d/m') }}
                                            @endif
                                            @if($billingStatus === 'pago' && $billingMethod)
                                                • {{ $billingMethod }}
                                            @endif
                                        </p>
                                    </div>
                                    <span class="text-lg">{{ $billingIcon }}</span>
                                </div>

                                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Presença no mês</p>
                                            @if($monthRate !== null)
                                                <p class="text-sm font-bold text-slate-900 dark:text-slate-50">{{ number_format($monthRate, 1, ',', '.') }}%</p>
                                                <p class="text-xs text-slate-500 font-medium mt-1">{{ $stats['present'] }} / {{ $stats['total'] }} presenças • {{ $stats['absent'] }} faltas</p>
                                            @else
                                                <p class="text-sm font-bold text-slate-600 dark:text-slate-300">Sem aulas registradas</p>
                                            @endif
                                        </div>
                                        @if($monthRate !== null)
                                            <div class="w-24">
                                                <div class="h-2 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">
                                                    <div class="h-2 bg-emerald-500" style="width: {{ min(100, max(0, $monthRate)) }}%"></div>
                                                </div>
                                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1 text-right">mês atual</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Últimas Presenças</h3>
                                    <div class="space-y-3">
                                        @forelse($student->attendances->take(5) as $attendance)
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-slate-600 dark:text-slate-400 font-medium">{{ $attendance->date->format('d/m/Y') }}</span>
                                                @if($attendance->present)
                                                    <span class="flex items-center gap-1.5 text-emerald-600 font-bold text-xs uppercase">
                                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Presente
                                                    </span>
                                                @else
                                                    <span class="flex items-center gap-1.5 text-rose-600 font-bold text-xs uppercase">
                                                        <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span> Ausente
                                                    </span>
                                                @endif
                                            </div>
                                            @if($attendance->obs)
                                                <p class="text-[11px] text-slate-500 italic mt-1 bg-slate-50 dark:bg-slate-900/50 p-2 rounded-lg border-l-2 border-slate-200">
                                                    "{{ $attendance->obs }}"
                                                </p>
                                            @endif
                                        @empty
                                            <p class="text-xs text-slate-400 italic">Nenhum registro de aula recente.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-20 bg-white dark:bg-slate-800 rounded-3xl border-2 border-dashed border-slate-200 dark:border-slate-700">
                            <p class="text-slate-400">Nenhum aluno vinculado a esta conta de responsável.</p>
                        </div>
                    @endforelse
                </div>
                </div>

                <div x-show="tab === 'desempenho'" class="space-y-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        @forelse($children as $student)
                            @php
                                $stats = $attendanceStatsByStudent[$student->id] ?? ['total' => 0, 'present' => 0, 'absent' => 0, 'last_date' => null];
                                $monthRate = ($stats['total'] ?? 0) > 0 ? round(($stats['present'] / $stats['total']) * 100, 1) : null;

                                $startTime = $student->class_start_time ? \Illuminate\Support\Carbon::parse($student->class_start_time)->format('H:i') : null;
                                $endTime = $student->class_end_time ? \Illuminate\Support\Carbon::parse($student->class_end_time)->format('H:i') : null;

                                $obsAttendances = $student->attendances->filter(fn ($attendance) => (bool) $attendance->obs);
                            @endphp

                            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                                <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                                    <h2 class="text-lg font-bold text-slate-900 dark:text-slate-50">{{ $student->name }}</h2>
                                    <p class="text-xs text-slate-500 font-medium mt-1">
                                        Turma: {{ $student->team->name }} • {{ $student->team->time }}
                                        @if($startTime && $endTime)
                                            • {{ $startTime }}–{{ $endTime }}
                                        @endif
                                    </p>
                                    <p class="text-xs text-slate-500 font-medium mt-1">
                                        Professor(a): {{ $student->team->teacher?->name ?? '-' }}{{ $student->team->teacher?->phone ? ' • '.$student->team->teacher->phone : '' }}
                                    </p>
                                </div>

                                <div class="p-6 space-y-6">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700">
                                            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Presença (mês)</p>
                                            @if($monthRate !== null)
                                                <p class="text-sm font-bold text-slate-900 dark:text-slate-50 mt-1">{{ number_format($monthRate, 1, ',', '.') }}%</p>
                                                <p class="text-xs text-slate-500 font-medium mt-1">{{ $stats['present'] }} / {{ $stats['total'] }} presenças</p>
                                            @else
                                                <p class="text-sm font-bold text-slate-600 dark:text-slate-300 mt-1">Sem aulas</p>
                                            @endif
                                        </div>

                                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700">
                                            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Faltas (mês)</p>
                                            <p class="text-sm font-bold text-slate-900 dark:text-slate-50 mt-1">{{ (int) ($stats['absent'] ?? 0) }}</p>
                                            <p class="text-xs text-slate-500 font-medium mt-1">Total de aulas: {{ (int) ($stats['total'] ?? 0) }}</p>
                                        </div>

                                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700">
                                            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Última aula</p>
                                            <p class="text-sm font-bold text-slate-900 dark:text-slate-50 mt-1">
                                                {{ $stats['last_date'] ? $stats['last_date']->format('d/m/Y') : '-' }}
                                            </p>
                                            <p class="text-xs text-slate-500 font-medium mt-1">Baseado nas chamadas registradas</p>
                                        </div>

                                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700">
                                            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Observações</p>
                                            <p class="text-sm font-bold text-slate-900 dark:text-slate-50 mt-1">{{ $obsAttendances->count() }}</p>
                                            <p class="text-xs text-slate-500 font-medium mt-1">Aulas com recado recente</p>
                                        </div>
                                    </div>

                                    <div class="p-4 rounded-2xl bg-white/60 dark:bg-slate-900/20 border border-slate-200 dark:border-slate-700">
                                        <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Observações gerais (professor)</p>
                                        @if($student->notes)
                                            <div class="text-sm text-slate-700 dark:text-slate-200 font-medium mt-2 leading-relaxed">{!! nl2br(e($student->notes)) !!}</div>
                                        @else
                                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 italic">Sem observações gerais cadastradas.</p>
                                        @endif
                                    </div>

                                    <div>
                                        <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Observações de aula</p>
                                        <div class="space-y-3 mt-3">
                                            @forelse($obsAttendances as $attendance)
                                                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700">
                                                    <div class="flex items-center justify-between">
                                                        <span class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ $attendance->date->format('d/m/Y') }}</span>
                                                        <span class="text-[10px] font-bold uppercase tracking-widest {{ $attendance->present ? 'text-emerald-600' : 'text-rose-600' }}">
                                                            {{ $attendance->present ? 'Presente' : 'Ausente' }}
                                                        </span>
                                                    </div>
                                                    <p class="text-sm text-slate-700 dark:text-slate-200 font-medium mt-2">"{{ $attendance->obs }}"</p>
                                                </div>
                                            @empty
                                                <p class="text-sm text-slate-500 dark:text-slate-400 italic">Nenhuma observação recente nas chamadas.</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-20 bg-white dark:bg-slate-800 rounded-3xl border-2 border-dashed border-slate-200 dark:border-slate-700">
                                <p class="text-slate-400">Nenhum aluno vinculado a esta conta de responsável.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div x-show="tab === 'observacoes'" class="space-y-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        @forelse($children as $student)
                            @php
                                $startTime = $student->class_start_time ? \Illuminate\Support\Carbon::parse($student->class_start_time)->format('H:i') : null;
                                $endTime = $student->class_end_time ? \Illuminate\Support\Carbon::parse($student->class_end_time)->format('H:i') : null;

                                $obsAttendances = $student->attendances->filter(fn ($attendance) => (bool) $attendance->obs);
                                $obsPreview = $obsAttendances->take(6);
                                $obsHasMore = $obsAttendances->count() > $obsPreview->count();
                                $lastObsDate = $obsAttendances->first()?->date;
                            @endphp

                            <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                                <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <h2 class="text-lg font-bold text-slate-900 dark:text-slate-50">{{ $student->name }}</h2>
                                            <p class="text-xs text-slate-500 font-medium mt-1">
                                                Turma: {{ $student->team->name }} • {{ $student->team->time }}
                                                @if($startTime && $endTime)
                                                    • {{ $startTime }}–{{ $endTime }}
                                                @endif
                                            </p>
                                            <p class="text-xs text-slate-500 font-medium mt-1">
                                                Professor(a): {{ $student->team->teacher?->name ?? '-' }}{{ $student->team->teacher?->phone ? ' • '.$student->team->teacher->phone : '' }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Último recado</p>
                                            <p class="text-sm font-bold text-slate-900 dark:text-slate-50 mt-1">{{ $lastObsDate?->format('d/m/Y') ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-6 space-y-6">
                                    <div class="p-4 rounded-2xl bg-white/60 dark:bg-slate-900/20 border border-slate-200 dark:border-slate-700">
                                        <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Observações gerais (professor)</p>
                                        @if($student->notes)
                                            <div class="text-sm text-slate-700 dark:text-slate-200 font-medium mt-2 leading-relaxed">{!! nl2br(e($student->notes)) !!}</div>
                                        @else
                                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 italic">Sem observações gerais cadastradas.</p>
                                        @endif
                                    </div>

                                    <div x-data="{ open: false }">
                                        <div class="flex items-center justify-between gap-4">
                                            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-400">Recados por aula</p>
                                            @if($obsHasMore)
                                                <button type="button" @click="open = !open" class="text-[11px] font-bold text-blue-700 dark:text-blue-300 hover:underline">
                                                    <span x-show="!open">Ver mais</span>
                                                    <span x-show="open">Ver menos</span>
                                                </button>
                                            @endif
                                        </div>

                                        <div class="space-y-3 mt-3">
                                            @forelse($obsPreview as $attendance)
                                                <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700">
                                                    <div class="flex items-center justify-between">
                                                        <span class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ $attendance->date->format('d/m/Y') }}</span>
                                                        <span class="text-[10px] font-bold uppercase tracking-widest {{ $attendance->present ? 'text-emerald-600' : 'text-rose-600' }}">
                                                            {{ $attendance->present ? 'Presente' : 'Ausente' }}
                                                        </span>
                                                    </div>
                                                    <div class="text-sm text-slate-700 dark:text-slate-200 font-medium mt-2 leading-relaxed">"{!! nl2br(e($attendance->obs)) !!}"</div>
                                                </div>
                                            @empty
                                                <p class="text-sm text-slate-500 dark:text-slate-400 italic">Nenhum recado ainda nas chamadas.</p>
                                            @endforelse
                                        </div>

                                        @if($obsHasMore)
                                            <div x-show="open" class="space-y-3 mt-3">
                                                @foreach($obsAttendances->skip($obsPreview->count()) as $attendance)
                                                    <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-slate-700">
                                                        <div class="flex items-center justify-between">
                                                            <span class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ $attendance->date->format('d/m/Y') }}</span>
                                                            <span class="text-[10px] font-bold uppercase tracking-widest {{ $attendance->present ? 'text-emerald-600' : 'text-rose-600' }}">
                                                                {{ $attendance->present ? 'Presente' : 'Ausente' }}
                                                            </span>
                                                        </div>
                                                        <div class="text-sm text-slate-700 dark:text-slate-200 font-medium mt-2 leading-relaxed">"{!! nl2br(e($attendance->obs)) !!}"</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-20 bg-white dark:bg-slate-800 rounded-3xl border-2 border-dashed border-slate-200 dark:border-slate-700">
                                <p class="text-slate-400">Nenhum aluno vinculado a esta conta de responsável.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
