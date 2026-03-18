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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @php $modeValue = $mode ?? 'admin'; @endphp

        @if ($modeValue === 'admin')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-md border border-slate-300 dark:border-slate-700 transition-all hover:border-blue-300 dark:hover:border-blue-600 hover:shadow-lg">
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

                <a href="{{ route('financeiro.index') }}" class="group p-6 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-2xl hover:border-blue-400 dark:hover:border-blue-600 hover:shadow-lg transition-all">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 bg-slate-100 dark:bg-slate-700 rounded-xl flex items-center justify-center text-slate-600 dark:text-slate-400 group-hover:bg-emerald-500 group-hover:text-white transition-all">
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
                    <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Pagamentos Recentes</h3>
                    <a href="{{ route('financeiro.index') }}" class="text-[11px] font-bold text-blue-600 dark:text-blue-400 hover:underline">Ver tudo</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                <th class="px-6 py-3">Aluno</th>
                                <th class="px-6 py-3 text-right">Valor</th>
                                <th class="px-6 py-3 text-right">Data</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($latestPayments->take(5) as $payment)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-[10px] font-bold text-slate-500 border border-slate-300">
                                                {{ substr($payment->student->name, 0, 2) }}
                                            </div>
                                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $payment->student->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-bold text-slate-900">
                                        R$ {{ number_format($payment->amount, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-xs font-medium text-slate-500">
                                        {{ $payment->paid_at->format('d/m/y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-10 text-center text-slate-400 text-xs font-medium italic">Sem registros.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>