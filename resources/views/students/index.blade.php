<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-900 dark:text-slate-50 tracking-tight flex items-center gap-2">
                    <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                    Alunos
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium ml-4">{{ $students->count() }} aluno{{ $students->count() !== 1 ? 's' : '' }}</p>
            </div>
            @if (auth()->user()?->isAdmin())
                <a href="{{ route('alunos.create') }}" class="group flex items-center gap-3 p-1.5 pr-4 bg-slate-100 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl hover:border-blue-400 dark:hover:border-blue-600 hover:shadow-md transition-all">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-xs group-hover:bg-blue-700 transition-colors">
                        +
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] uppercase tracking-wider font-bold text-slate-400 dark:text-slate-300 leading-none">Ação</span>
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 leading-tight">Novo Aluno</span>
                    </div>
                </a>
            @endif
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-md border border-slate-300 dark:border-slate-700 mb-6">
            <form method="GET" action="{{ route('alunos.index') }}" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-xs">
                    <label class="block text-sm font-semibold text-slate-900 dark:text-slate-50 mb-2">Filtrar por Turma</label>
                    <select name="team_id" class="w-full border border-slate-300 dark:border-slate-600 rounded-lg px-4 py-2 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 transition bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-50">
                        <option value="">Todas as turmas</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}" @selected((string) $selectedTeamId === (string) $team->id)>
                                {{ $team->name }} ({{ $team->time }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-6 py-2.5 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg font-semibold text-slate-900 dark:text-slate-50 hover:bg-slate-200 dark:hover:bg-slate-600 hover:border-slate-400 dark:hover:border-slate-500 transition">
                    Filtrar
                </button>
                @if ($selectedTeamId)
                    <a href="{{ route('alunos.index') }}" class="text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 font-semibold text-sm">← Limpar</a>
                @endif
            </form>
        </div>

        @if ($students->isEmpty())
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-16 text-center shadow-md border border-slate-300 dark:border-slate-700">
                <svg class="w-20 h-20 text-slate-300 dark:text-slate-700 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                </svg>
                <p class="text-slate-600 dark:text-slate-400 text-lg">Nenhum aluno encontrado</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($students as $student)
                    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-md border border-slate-300 dark:border-slate-700 hover:border-slate-400 dark:hover:border-slate-600 hover:shadow-lg transition">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4 flex-1 min-w-0">
                                <div class="w-14 h-14 rounded-full bg-slate-900 dark:bg-slate-700 flex items-center justify-center text-white font-bold text-xl flex-shrink-0 border border-slate-800 dark:border-slate-600">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('alunos.show', $student) }}" class="font-bold text-slate-900 dark:text-slate-50 hover:text-blue-600 dark:hover:text-blue-400 block truncate text-lg">
                                        {{ $student->name }}
                                    </a>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $student->responsavel?->name ?? $student->parent_name ?? '-' }}</p>
                                    <div class="flex gap-2 mt-2">
                                        <span class="text-xs bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 px-2.5 py-1 rounded-md font-medium">{{ $student->team?->name ?? '-' }}</span>
                                        @if ($student->active)
                                            <span class="text-xs bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 px-2.5 py-1 rounded-md font-semibold border border-emerald-100 dark:border-emerald-800">✓ Ativo</span>
                                        @else
                                            <span class="text-xs bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 px-2.5 py-1 rounded-md">Inativo</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2 justify-end">
                                <a href="{{ route('alunos.show', $student) }}" class="px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg font-semibold text-slate-900 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-600 hover:border-slate-400 dark:hover:border-slate-500 transition text-sm whitespace-nowrap">
                                    Perfil
                                </a>
                                @if (auth()->user()?->isAdmin())
                                    <a href="{{ route('alunos.edit', $student) }}" class="px-3 py-2 bg-slate-100 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg font-semibold text-slate-900 dark:text-slate-200 hover:bg-slate-200 dark:hover:bg-slate-600 hover:border-slate-400 dark:hover:border-slate-500 transition text-sm whitespace-nowrap">
                                        Editar
                                    </a>
                                    <form method="POST" action="{{ route('alunos.destroy', $student) }}" onsubmit="return confirm('Tem certeza que deseja excluir este aluno?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-2 bg-red-50 border border-red-300 rounded-lg font-semibold text-red-700 hover:bg-red-100 hover:border-red-400 transition text-sm whitespace-nowrap">
                                            Excluir
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>