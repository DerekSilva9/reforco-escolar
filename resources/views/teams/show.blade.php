<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-slate-900 dark:text-slate-50 leading-tight">
                    Gerenciar Turma: {{ $team->name }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
                    {{ $team->time }} • 
                    <span class="font-medium">{{ $team->students->count() }} aluno(s)</span>
                    • Professor: {{ $team->teacher?->name ?? '-' }}
                </p>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <a href="{{ route('presenca.index', ['team_id' => $team->id, 'date' => now()->toDateString()]) }}" class="flex-1 sm:flex-none inline-flex items-center justify-center sm:justify-start px-3 sm:px-4 py-2 bg-green-600 border border-green-700 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 shadow-md">
                    ✓ Presença
                </a>
                <a href="{{ route('turmas.index') }}" class="flex-1 sm:flex-none inline-flex items-center justify-center sm:justify-start px-3 sm:px-4 py-2 bg-slate-600 border border-slate-700 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 shadow-sm">
                    ← Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-8 lg:py-12">
        <div class="max-w-6xl mx-auto px-3 md:px-6 lg:px-8">
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
                        <div class="bg-white dark:bg-slate-800 rounded-lg p-4 md:p-6 border border-blue-100 dark:border-slate-700 shadow-md mb-6 md:mb-8">
                            <h3 class="text-base md:text-lg font-semibold text-slate-900 dark:text-slate-50 mb-3 md:mb-4 flex items-center">
                                <svg class="w-4 md:w-5 h-4 md:h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                                Filtros
                            </h3>

                            <form method="GET" action="{{ route('turmas.show', $team) }}" class="space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
                                    <!-- Busca por Nome/Responsável -->
                                    <div>
                                        <label for="search" class="block text-xs md:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1 md:mb-2">Buscar por Nome ou Responsável</label>
                                        <input 
                                            type="text" 
                                            id="search" 
                                            name="search" 
                                            value="{{ $search }}"
                                            placeholder="Digite aqui..."
                                            class="w-full px-3 md:px-4 py-1.5 md:py-2 text-sm border border-blue-200 dark:border-slate-600 rounded-md focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500 transition-colors bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-50"
                                        >
                                    </div>

                                    <!-- Filtro por Status -->
                                    <div>
                                        <label for="status" class="block text-xs md:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1 md:mb-2">Status</label>
                                        <select id="status" name="status" class="w-full px-3 md:px-4 py-1.5 md:py-2 text-sm border border-blue-200 dark:border-slate-600 rounded-md focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500 transition-colors bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-50">
                                            <option value="">Todos</option>
                                            <option value="active" @selected($status === 'active')>✓ Ativos</option>
                                            <option value="inactive" @selected($status === 'inactive')>✗ Inativos</option>
                                        </select>
                                    </div>

                                    <!-- Filtro por Horário -->
                                    <div>
                                        <label for="time_filter" class="block text-xs md:text-sm font-medium text-slate-700 dark:text-slate-300 mb-1 md:mb-2">Aulas a partir de (opcional)</label>
                                        <input 
                                            type="time" 
                                            id="time_filter" 
                                            name="time_filter" 
                                            value="{{ $timeFilter }}"
                                            class="w-full px-3 md:px-4 py-1.5 md:py-2 text-sm border border-blue-200 dark:border-slate-600 rounded-md focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500 transition-colors bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-50"
                                        >
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 md:mt-1">Ex: 14:00 mostrará apenas alunos com aulas às 14h ou depois</p>
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                                    <button type="submit" class="px-4 sm:px-6 py-2 bg-blue-600 text-white rounded-md font-medium text-sm hover:bg-blue-700 transition-colors shadow-sm">
                                        🔍 Filtrar
                                    </button>
                                    <a href="{{ route('turmas.show', $team) }}" class="px-4 sm:px-6 py-2 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-md font-medium text-sm hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors shadow-sm text-center">
                                        Limpar Filtros
                                    </a>
                                </div>
                            </form>
                        </div>

                        <!-- Filtros Ativos -->
                        @if ($search !== '' || $status !== '' || $timeFilter !== '')
                            <div class="bg-blue-50 dark:bg-slate-700/50 border border-blue-200 dark:border-slate-600 rounded-lg p-3 md:p-4 mb-4 md:mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                                <div class="flex gap-2 flex-wrap">
                                    @if ($search !== '')
                                        <span class="inline-flex items-center px-2 sm:px-3 py-1 bg-blue-200 dark:bg-blue-900/40 text-blue-900 dark:text-blue-300 rounded-full text-xs font-medium">
                                            🔍 <span class="hidden sm:inline ml-1">"{{ $search }}"</span><span class="sm:hidden">{{ substr($search, 0, 8) }}{{ strlen($search) > 8 ? '...' : '' }}</span>
                                            <a href="{{ route('turmas.show', array_merge(request()->query(), ['search' => ''])) }}" class="ml-1 font-bold">×</a>
                                        </span>
                                    @endif
                                    @if ($status !== '')
                                        <span class="inline-flex items-center px-2 sm:px-3 py-1 bg-blue-200 dark:bg-blue-900/40 text-blue-900 dark:text-blue-300 rounded-full text-xs font-medium">
                                            {{ $status === 'active' ? '✓' : '✗' }} <span class="hidden sm:inline ml-1">{{ $status === 'active' ? 'Ativos' : 'Inativos' }}</span>
                                            <a href="{{ route('turmas.show', array_merge(request()->query(), ['status' => ''])) }}" class="ml-1 font-bold">×</a>
                                        </span>
                                    @endif
                                    @if ($timeFilter !== '')
                                        <span class="inline-flex items-center px-2 sm:px-3 py-1 bg-blue-200 dark:bg-blue-900/40 text-blue-900 dark:text-blue-300 rounded-full text-xs font-medium">
                                            ⏰ {{ $timeFilter }}
                                            <a href="{{ route('turmas.show', array_merge(request()->query(), ['time_filter' => ''])) }}" class="ml-1 font-bold">×</a>
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 font-medium whitespace-nowrap">
                                    {{ $students->count() }} aluno(s)
                                </p>
                            </div>
                        @endif

                        @if ($students->isEmpty())
                            <div class="text-center bg-white dark:bg-slate-800 rounded-lg p-8 border border-blue-100 dark:border-slate-700">
                                <p class="text-slate-600 dark:text-slate-400 text-lg">Nenhum aluno encontrado com os filtros aplicados.</p>
                            </div>
                        @else
                            <!-- Header Stats -->
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 md:gap-4 mb-6 md:mb-8">
                                <div class="bg-white dark:bg-slate-800 rounded-lg p-3 md:p-4 border border-blue-100 dark:border-slate-700 shadow-sm">
                                    <p class="text-xs md:text-sm text-slate-600 dark:text-slate-400 font-semibold uppercase tracking-wide">Total de Alunos</p>
                                    <p class="text-2xl md:text-3xl font-bold text-blue-900 mt-1 md:mt-2">{{ $students->count() }}</p>
                                </div>
                                <div class="bg-white dark:bg-slate-800 rounded-lg p-3 md:p-4 border border-blue-100 dark:border-slate-700 shadow-sm">
                                    <p class="text-xs md:text-sm text-slate-600 dark:text-slate-400 font-semibold uppercase tracking-wide">Primeiro Horário</p>
                                    <p class="text-xl md:text-2xl font-bold text-blue-900 mt-1 md:mt-2">
                                        {{ $students->first()?->class_start_time?->format('H:i') ?? '-' }}
                                    </p>
                                </div>
                                <div class="col-span-2 md:col-span-1 bg-white dark:bg-slate-800 rounded-lg p-3 md:p-4 border border-blue-100 dark:border-slate-700 shadow-sm">
                                    <p class="text-xs md:text-sm text-slate-600 dark:text-slate-400 font-semibold uppercase tracking-wide">Último Horário</p>
                                    <p class="text-xl md:text-2xl font-bold text-blue-900 mt-1 md:mt-2">
                                        {{ $students->last()?->class_end_time?->format('H:i') ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Students Table -->
                            <div class="bg-white dark:bg-slate-800 rounded-lg overflow-x-auto border border-blue-100 dark:border-slate-700 shadow-md">
                                <table class="w-full min-w-max text-sm md:min-w-full">
                                    <thead class="bg-gradient-to-r from-blue-700 to-indigo-700">
                                        <tr class="text-white">
                                            <th class="py-3 sm:py-4 px-3 sm:px-6 text-left font-semibold text-xs sm:text-sm">Aluno</th>
                                            <th class="hidden sm:table-cell py-3 sm:py-4 px-3 sm:px-6 text-left font-semibold text-xs sm:text-sm">Responsável</th>
                                            <th class="py-3 sm:py-4 px-3 sm:px-6 text-center font-semibold text-xs sm:text-sm">Horário</th>
                                            <th class="py-3 sm:py-4 px-3 sm:px-6 text-right font-semibold text-xs sm:text-sm">Mensalidade</th>
                                            <th class="py-3 sm:py-4 px-3 sm:px-6 text-center font-semibold text-xs sm:text-sm">Status</th>
                                            <th class="py-3 sm:py-4 px-3 sm:px-6 text-right font-semibold text-xs sm:text-sm">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-blue-100 dark:divide-slate-700">
                                        @foreach ($students as $student)
                                            <tr class="hover:bg-blue-50 dark:hover:bg-slate-700 transition-colors duration-150">
                                                <td class="py-3 sm:py-4 px-3 sm:px-6">
                                                    <div class="flex items-center gap-2 sm:gap-3">
                                                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold text-xs sm:text-sm">
                                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <p class="font-semibold text-slate-900 dark:text-slate-50 text-sm sm:text-base">{{ $student->name }}</p>
                                                            <p class="text-xs text-slate-500 dark:text-slate-400">
                                                                @if ($student->birth_date)
                                                                    {{ \Carbon\Carbon::parse($student->birth_date)->age }} anos
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="hidden sm:table-cell py-3 sm:py-4 px-3 sm:px-6">
                                                    <div>
                                                        <p class="font-medium text-slate-900 dark:text-slate-50 text-sm">{{ $student->parent_name }}</p>
                                                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ $student->phone }}</p>
                                                    </div>
                                                </td>
                                                <td class="py-3 sm:py-4 px-3 sm:px-6 text-center">
                                                    <div class="bg-blue-100 dark:bg-blue-900/40 rounded-lg py-2 px-2 sm:px-3 inline-block">
                                                        <p class="font-bold text-blue-900 dark:text-blue-300 text-xs sm:text-sm">
                                                            @if ($student->class_start_time && $student->class_end_time)
                                                                {{ $student->class_start_time->format('H:i') }} - {{ $student->class_end_time->format('H:i') }}
                                                            @else
                                                                <span class="text-slate-500 dark:text-slate-400 text-xs">-</span>
                                                            @endif
                                                        </p>
                                                    </div>
                                                </td>
                                                <td class="py-3 sm:py-4 px-3 sm:px-6 text-right">
                                                    <p class="font-semibold text-slate-900 dark:text-slate-50 text-sm">
                                                        R$ {{ number_format($student->fee, 2, ',', '.') }}
                                                    </p>
                                                    @if ($student->due_day)
                                                        <p class="text-xs text-slate-500 dark:text-slate-400">
                                                            Dia {{ $student->due_day }}
                                                        </p>
                                                    @endif
                                                </td>
                                                <td class="py-3 sm:py-4 px-3 sm:px-6 text-center">
                                                    @if ($student->active)
                                                        <span class="inline-flex items-center px-2 py-1 sm:px-3 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/40 text-green-800 dark:text-green-300 border border-green-200 dark:border-green-800">
                                                            ✓
                                                            <span class="hidden sm:inline ml-1">Ativo</span>
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-1 sm:px-3 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/40 text-red-800 dark:text-red-300 border border-red-200 dark:border-red-800">
                                                            ✗
                                                            <span class="hidden sm:inline ml-1">Inativo</span>
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="py-3 sm:py-4 px-3 sm:px-6">
                                                    <div class="flex justify-end">
                                                        <a href="{{ route('alunos.edit', $student) }}" class="inline-flex items-center px-2 py-1 sm:px-3 sm:py-1.5 bg-blue-500 dark:bg-blue-600 rounded-md text-white hover:bg-blue-600 dark:hover:bg-blue-500 shadow-sm text-xs font-medium transition-colors duration-150">
                                                            ✏️
                                                            <span class="hidden sm:inline ml-1">Editar</span>
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
