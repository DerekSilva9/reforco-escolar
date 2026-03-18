<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                    Mensalidades
                </h1>
                <p class="text-xs text-slate-500 font-medium ml-4">{{ str_pad((string) $month, 2, '0', STR_PAD_LEFT) }}/{{ $year }}</p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-slate-600 hover:text-slate-900 font-semibold text-sm">← Voltar ao Dashboard</a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Cards de Status -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <!-- Pagos -->
            <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-300 hover:border-emerald-300 transition-colors">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[11px] font-bold text-emerald-600 uppercase tracking-widest">Pagos</p>
                    <div class="bg-emerald-50 p-2.5 rounded-lg">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <h2 class="text-3xl font-bold text-slate-900">{{ $counts['pagos'] }}</h2>
                <p class="text-emerald-600 text-xs mt-2 font-medium">✓ Tudo em dia</p>
            </div>

            <!-- Pendentes -->
            <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-300 hover:border-amber-300 transition-colors">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[11px] font-bold text-amber-600 uppercase tracking-widest">Pendentes</p>
                    <div class="bg-amber-50 p-2.5 rounded-lg">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <h2 class="text-3xl font-bold text-slate-900">{{ $counts['pendentes'] }}</h2>
                <p class="text-amber-600 text-xs mt-2 font-medium">⚠️ Atenção - Cobrar</p>
            </div>

            <!-- Atrasados -->
            <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-300 hover:border-red-300 transition-colors">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-[11px] font-bold text-red-600 uppercase tracking-widest">Atrasados</p>
                    <div class="bg-red-50 p-2.5 rounded-lg">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 5v.01M12 3a9 9 0 100 18 9 9 0 000-18z"></path>
                        </svg>
                    </div>
                </div>
                <h2 class="text-3xl font-bold text-slate-900">{{ $counts['atrasados'] }}</h2>
                <p class="text-red-600 text-xs mt-2 font-medium">🚨 URGENTE - Atrasado</p>
            </div>
        </div>

        <!-- Filtros -->
        <div class="bg-white rounded-2xl p-4 shadow-md border border-slate-300 mb-6">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('financeiro.index', ['status' => 'pagos']) }}"
                    class="px-4 py-2 rounded-full text-sm font-medium transition {{ $status === 'pagos' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border border-transparent' }}">
                    ✓ Pagos
                </a>
                <a href="{{ route('financeiro.index', ['status' => 'pendentes']) }}"
                    class="px-4 py-2 rounded-full text-sm font-medium transition {{ $status === 'pendentes' ? 'bg-amber-100 text-amber-700 border border-amber-200' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border border-transparent' }}">
                    ⚠️ Pendentes
                </a>
                <a href="{{ route('financeiro.index', ['status' => 'atrasados']) }}"
                    class="px-4 py-2 rounded-full text-sm font-medium transition {{ $status === 'atrasados' ? 'bg-red-100 text-red-700 border border-red-200' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border border-transparent' }}">
                    🚨 Atrasados
                </a>
                <a href="{{ route('financeiro.index') }}"
                    class="px-4 py-2 rounded-full text-sm font-medium transition {{ $status === '' ? 'bg-slate-200 text-slate-900 border border-slate-300' : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border border-transparent' }}">
                    Todos
                </a>
            </div>
        </div>

        <!-- Lista de Mensalidades -->
        @if ($rows->isEmpty())
            <div class="bg-white rounded-2xl p-12 text-center shadow-md border border-slate-300">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-slate-600 text-lg">Nenhuma mensalidade encontrada</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($rows as $row)
                    @php
                        $student = $row['student'];
                        $dueDate = \Illuminate\Support\Carbon::parse($row['due_date']);
                        $today = \Illuminate\Support\Carbon::today();
                        
                        if ($row['status'] === 'pago') {
                            $statusColor = 'emerald';
                            $statusBg = 'emerald-50';
                            $statusIcon = '✓';
                            $statusLabel = 'Pago';
                        } elseif ($row['status'] === 'atrasado') {
                            $statusColor = 'red';
                            $statusBg = 'red-50';
                            $statusIcon = '🚨';
                            $statusLabel = 'Atrasado';
                        } else {
                            $statusColor = 'amber';
                            $statusBg = 'amber-50';
                            $statusIcon = '⚠️';
                            $statusLabel = 'Pendente';
                        }
                    @endphp
                    <div class="bg-white rounded-2xl p-4 shadow-md border border-slate-300 hover:border-slate-400 hover:shadow-lg transition">
                        <div class="flex items-center justify-between gap-4">
                            <!-- Informações do Aluno -->
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('alunos.show', $student) }}" class="block hover:text-blue-600 transition">
                                    <p class="font-bold text-slate-900 truncate text-sm">{{ $student->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $student->team->name ?? 'N/A' }}</p>
                                </a>
                            </div>

                            <!-- Valor e Vencimento -->
                            <div class="text-right min-w-fit">
                                <p class="font-bold text-slate-900">R$ {{ number_format($row['amount'], 2, ',', '.') }}</p>
                                <p class="text-xs text-slate-500">Vence: {{ $dueDate->format('d/m') }}</p>
                            </div>
                            
                            <!-- Status Badge -->
                            <div class="bg-{{ $statusBg }} text-{{ $statusColor }}-700 px-3 py-2 rounded-lg font-bold text-center whitespace-nowrap border border-{{ $statusColor }}-100 min-w-fit">
                                <div class="text-sm">{{ $statusIcon }}</div>
                                <div class="text-xs mt-0.5">{{ $statusLabel }}</div>
                            </div>

                            <!-- Ação -->
                            @if ($row['status'] !== 'pago')
                                <form method="POST" action="{{ route('financeiro.pay', $student) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-2 bg-blue-50 border border-blue-300 rounded-lg font-semibold text-blue-700 hover:bg-blue-100 hover:border-blue-400 transition text-sm whitespace-nowrap">
                                        Pagar
                                    </button>
                                </form>
                            @else
                                <div class="text-emerald-600 font-bold">✓</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>

