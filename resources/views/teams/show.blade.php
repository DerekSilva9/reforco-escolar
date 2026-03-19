<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-slate-900 dark:text-slate-50 leading-tight">
                    Gerenciar Turma: {{ $team->name }}
                </h2>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                    {{ $team->time }} • 
                    <span class="font-medium">{{ $team->students->count() }} aluno(s)</span>
                    • Professor: {{ $team->teacher?->name ?? '-' }}
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('presenca.index', ['team_id' => $team->id, 'date' => now()->toDateString()]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-green-700 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 shadow-md">
                    ✓ Marcar Presença
                </a>
                <a href="{{ route('turmas.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 border border-slate-700 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 shadow-sm">
                    ← Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if ($students->isEmpty() && $search === '' && $status === '' && $timeFilter === '')
                <div class="bg-white dark:bg-slate-800/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100 dark:border-slate-700 p-8">
                    <div class="text-center text-slate-600 dark:text-slate-400">
                        <p class="text-lg">Nenhum aluno nesta turma ainda.</p>
                        <a href="{{ route('alunos.create', ['team_id' => $team->id]) }}" class="mt-4 inline-flex items-center px-4 py-2 bg-blue-900 border border-blue-950 rounded-md font-semibold text-xs text-amber-50 uppercase tracking-widest hover:bg-blue-800 shadow-sm">
                            Adicionar Aluno
                        </a>
                    </div>
                </div>
            @else
                <div class="bg-gradient-to-br from-blue-50 dark:from-slate-800 to-indigo-50 dark:to-slate-900 overflow-hidden shadow-lg sm:rounded-lg border border-blue-200 dark:border-slate-700">
                    <div class="p-8">
                        <!-- Filtros -->
                        <div class="bg-white dark:bg-slate-800 rounded-lg p-6 border border-blue-100 dark:border-slate-700 shadow-md mb-8">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-50 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                                Filtros
                            </h3>

                            <form method="GET" action="{{ route('turmas.show', $team) }}" class="space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Busca por Nome/Responsável -->
                                    <div>
                                        <label for="search" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Buscar por Nome ou Responsável</label>
                                        <input 
                                            type="text" 
                                            id="search" 
                                            name="search" 
                                            value="{{ $search }}"
                                            placeholder="Digite aqui..."
                                            class="w-full px-4 py-2 border border-blue-200 dark:border-slate-600 rounded-md focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500 transition-colors bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-50"
                                        >
                                    </div>

                                    <!-- Filtro por Status -->
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Status</label>
                                        <select id="status" name="status" class="w-full px-4 py-2 border border-blue-200 dark:border-slate-600 rounded-md focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500 transition-colors bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-50">
                                            <option value="">Todos</option>
                                            <option value="active" @selected($status === 'active')>✓ Ativos</option>
                                            <option value="inactive" @selected($status === 'inactive')>✗ Inativos</option>
                                        </select>
                                    </div>

                                    <!-- Filtro por Horário -->
                                    <div>
                                        <label for="time_filter" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Aulas a partir de (opcional)</label>
                                        <input 
                                            type="time" 
                                            id="time_filter" 
                                            name="time_filter" 
                                            value="{{ $timeFilter }}"
                                            class="w-full px-4 py-2 border border-blue-200 dark:border-slate-600 rounded-md focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500 transition-colors bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-50"
                                        >
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Ex: 14:00 mostrará apenas alunos com aulas às 14h ou depois</p>
                                    </div>
                                </div>

                                <div class="flex gap-3">
                                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md font-medium hover:bg-blue-700 transition-colors shadow-sm">
                                        🔍 Filtrar
                                    </button>
                                    <a href="{{ route('turmas.show', $team) }}" class="px-6 py-2 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-md font-medium hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors shadow-sm">
                                        Limpar Filtros
                                    </a>
                                </div>
                            </form>
                        </div>

                        <!-- Filtros Ativos -->
                        @if ($search !== '' || $status !== '' || $timeFilter !== '')
                            <div class="bg-blue-50 dark:bg-slate-700/50 border border-blue-200 dark:border-slate-600 rounded-lg p-4 mb-6 flex items-center justify-between">
                                <div class="flex gap-2 flex-wrap">
                                    @if ($search !== '')
                                        <span class="inline-flex items-center px-3 py-1 bg-blue-200 dark:bg-blue-900/40 text-blue-900 dark:text-blue-300 rounded-full text-xs font-medium">
                                            🔍 Busca: "{{ $search }}"
                                            <a href="{{ route('turmas.show', array_merge(request()->query(), ['search' => ''])) }}" class="ml-2 font-bold">×</a>
                                        </span>
                                    @endif
                                    @if ($status !== '')
                                        <span class="inline-flex items-center px-3 py-1 bg-blue-200 dark:bg-blue-900/40 text-blue-900 dark:text-blue-300 rounded-full text-xs font-medium">
                                            {{ $status === 'active' ? '✓ Ativos' : '✗ Inativos' }}
                                            <a href="{{ route('turmas.show', array_merge(request()->query(), ['status' => ''])) }}" class="ml-2 font-bold">×</a>
                                        </span>
                                    @endif
                                    @if ($timeFilter !== '')
                                        <span class="inline-flex items-center px-3 py-1 bg-blue-200 dark:bg-blue-900/40 text-blue-900 dark:text-blue-300 rounded-full text-xs font-medium">
                                            ⏰ A partir de {{ $timeFilter }}
                                            <a href="{{ route('turmas.show', array_merge(request()->query(), ['time_filter' => ''])) }}" class="ml-2 font-bold">×</a>
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-slate-600 dark:text-slate-300 font-medium">
                                    {{ $students->count() }} aluno(s) encontrado(s)
                                </p>
                            </div>
                        @endif

                        @if ($students->isEmpty())
                            <div class="text-center bg-white dark:bg-slate-800 rounded-lg p-8 border border-blue-100 dark:border-slate-700">
                                <p class="text-slate-600 dark:text-slate-400 text-lg">Nenhum aluno encontrado com os filtros aplicados.</p>
                            </div>
                        @else
                            <!-- Header Stats -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                                <div class="bg-white dark:bg-slate-800 rounded-lg p-4 border border-blue-100 dark:border-slate-700 shadow-sm">
                                    <p class="text-sm text-slate-600 dark:text-slate-400 font-semibold uppercase tracking-wide">Total de Alunos</p>
                                    <p class="text-3xl font-bold text-blue-900 mt-2">{{ $students->count() }}</p>
                                </div>
                                <div class="bg-white dark:bg-slate-800 rounded-lg p-4 border border-blue-100 dark:border-slate-700 shadow-sm">
                                    <p class="text-sm text-slate-600 dark:text-slate-400 font-semibold uppercase tracking-wide">Primeiro Horário</p>
                                    <p class="text-2xl font-bold text-blue-900 mt-2">
                                        {{ $students->first()?->class_start_time?->format('H:i') ?? '-' }}
                                    </p>
                                </div>
                                <div class="bg-white dark:bg-slate-800 rounded-lg p-4 border border-blue-100 dark:border-slate-700 shadow-sm">
                                    <p class="text-sm text-slate-600 dark:text-slate-400 font-semibold uppercase tracking-wide">Último Horário</p>
                                    <p class="text-2xl font-bold text-blue-900 mt-2">
                                        {{ $students->last()?->class_end_time?->format('H:i') ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Students Table -->
                            <div class="bg-white dark:bg-slate-800 rounded-lg overflow-hidden border border-blue-100 dark:border-slate-700 shadow-md">
                                <table class="min-w-full text-sm">
                                    <thead class="bg-gradient-to-r from-blue-700 to-indigo-700">
                                        <tr class="text-white">
                                            <th class="py-4 px-6 text-left font-semibold">Aluno</th>
                                            <th class="py-4 px-6 text-left font-semibold">Responsável</th>
                                            <th class="py-4 px-6 text-center font-semibold">Horário</th>
                                            <th class="py-4 px-6 text-right font-semibold">Mensalidade</th>
                                            <th class="py-4 px-6 text-center font-semibold">Status</th>
                                            <th class="py-4 px-6 text-right font-semibold">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-blue-100 dark:divide-slate-700">
                                        @foreach ($students as $student)
                                            <tr class="hover:bg-blue-50 dark:hover:bg-slate-700 transition-colors duration-150">
                                                <td class="py-4 px-6">
                                                    <div class="flex items-center">
                                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold">
                                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                                        </div>
                                                        <div class="ml-3">
                                                            <p class="font-semibold text-slate-900 dark:text-slate-50">{{ $student->name }}</p>
                                                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                                                @if ($student->birth_date)
                                                                    {{ \Carbon\Carbon::parse($student->birth_date)->age }} anos
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-4 px-6">
                                                    <div>
                                                        <p class="font-medium text-slate-900 dark:text-slate-50">{{ $student->parent_name }}</p>
                                                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $student->phone }}</p>
                                                    </div>
                                                </td>
                                                <td class="py-4 px-6 text-center">
                                                    <div class="bg-blue-100 dark:bg-blue-900/40 rounded-lg py-2 px-3 inline-block">
                                                        <p class="font-bold text-blue-900 dark:text-blue-300">
                                                            @if ($student->class_start_time && $student->class_end_time)
                                                                {{ $student->class_start_time->format('H:i') }} - {{ $student->class_end_time->format('H:i') }}
                                                            @else
                                                                <span class="text-slate-500 dark:text-slate-400 text-sm">Não definido</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </td>
                                                <td class="py-4 px-6 text-right">
                                                    <p class="font-semibold text-slate-900 dark:text-slate-50">
                                                        R$ {{ number_format($student->fee, 2, ',', '.') }}
                                                    </p>
                                                    @if ($student->due_day)
                                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                                            Vence todo dia {{ $student->due_day }}
                                                        </p>
                                                    @endif
                                                </td>
                                                <td class="py-4 px-6 text-center">
                                                    @if ($student->active)
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800">
                                                            ✓ Ativo
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-800">
                                                            ✗ Inativo
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="py-4 px-6">
                                                    <div class="flex justify-end gap-2">
                                                        <a href="{{ route('alunos.edit', $student) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-500 dark:bg-blue-600 rounded-md text-white hover:bg-blue-600 dark:hover:bg-blue-500 shadow-sm text-xs font-medium transition-colors duration-150">
                                                            Editar
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Add Student Button -->
                <div class="mt-6 flex justify-center">
                    <a href="{{ route('alunos.create', ['team_id' => $team->id]) }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 rounded-lg font-semibold text-white uppercase tracking-widest hover:from-green-700 hover:to-emerald-700 shadow-lg hover:shadow-xl transition-all duration-150">
                        + Adicionar Novo Aluno
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
