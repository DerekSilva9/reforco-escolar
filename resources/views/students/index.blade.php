<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-900 dark:text-slate-50 tracking-tight flex items-center gap-2">
                    <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                    Alunos
                </h1>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium ml-4">{{ $students->total() }} aluno{{ $students->total() !== 1 ? 's' : '' }}</p>
            </div>
            @if (auth()->user()?->isAdmin())
                <a href="{{ route('alunos.create') }}" class="group flex items-center gap-3 px-4 py-2.5 bg-blue-600 dark:bg-blue-700 border border-blue-700 dark:border-blue-600 rounded-lg hover:bg-blue-700 dark:hover:bg-blue-600 shadow-md hover:shadow-lg transition-all">
                    <div class="w-5 h-5 flex items-center justify-center text-white font-bold">
                        +
                    </div>
                    <span class="text-sm font-semibold text-white">Novo Aluno</span>
                </a>
            @endif
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-3 md:px-6 lg:px-8 py-4 md:py-6 lg:py-8">
        <div class="bg-white dark:bg-slate-800 rounded-xl p-5 shadow-sm border border-slate-200 dark:border-slate-700 mb-6">
            <form method="GET" action="{{ route('alunos.index') }}" class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-xs">
                    <label class="block text-sm font-semibold text-slate-900 dark:text-slate-100 mb-2">Filtrar por Turma</label>
                    <select name="team_id" class="w-full border border-slate-300 dark:border-slate-600 rounded-lg px-4 py-2.5 focus:border-blue-500 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-500/30 transition bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-50 font-medium">
                        <option value="">Todas as turmas</option>
                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}" @selected((string) $selectedTeamId === (string) $team->id)>
                                {{ $team->name }} ({{ $team->time }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 dark:bg-blue-700 border border-blue-700 dark:border-blue-600 rounded-lg font-semibold text-white hover:bg-blue-700 dark:hover:bg-blue-600 transition">
                    Filtrar
                </button>
                @if ($selectedTeamId)
                    <a href="{{ route('alunos.index') }}" class="text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-200 font-semibold text-sm">← Limpar filtro</a>
                @endif
                @if ($students->isNotEmpty())
                    <a href="{{ route('alunos.export', request()->query()) }}" class="flex items-center gap-2 px-6 py-2.5 bg-emerald-600 dark:bg-emerald-700 border border-emerald-700 dark:border-emerald-600 rounded-lg font-semibold text-white hover:bg-emerald-700 dark:hover:bg-emerald-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Exportar para Excel
                    </a>
                @endif
            </form>
        </div>

        @if ($students->isEmpty())
            <div class="bg-white dark:bg-slate-800 rounded-xl p-16 text-center shadow-sm border border-slate-200 dark:border-slate-700">
                <svg class="w-20 h-20 text-slate-300 dark:text-slate-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                </svg>
                <p class="text-slate-600 dark:text-slate-400 text-lg">Nenhum aluno encontrado</p>
            </div>
        @else
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-700 dark:to-slate-600 border-b border-slate-300 dark:border-slate-600">
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider">Nome</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider">Turma</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider">Horário</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider">Ano Escolar</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider">Responsável</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider">Telefone</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider">Mensalidade</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-900 dark:text-slate-100 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @foreach ($students as $student)
                                <tr class="hover:bg-blue-50 dark:hover:bg-slate-700 transition duration-150">
                                    <td class="px-6 py-4 text-sm">
                                        <a href="{{ route('alunos.show', $student) }}" class="font-semibold text-slate-900 dark:text-slate-50 hover:text-blue-600 dark:hover:text-blue-400">
                                            {{ $student->name }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span class="inline-flex px-3 py-1.5 rounded-lg font-semibold bg-slate-100 dark:bg-slate-600 text-slate-700 dark:text-slate-100">
                                            {{ $student->team?->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                                        @if ($student->team?->time)
                                            {{ $student->team->time }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if ($student->school_year)
                                            <span class="inline-flex px-3 py-1.5 rounded-lg font-semibold bg-blue-100 dark:bg-blue-700 text-blue-700 dark:text-blue-100">
                                                {{ $student->school_year }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 dark:text-slate-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                                        {{ $student->responsavel?->name ?? $student->parent_name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                                        {{ $student->responsavel?->phone ?? $student->phone ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-slate-900 dark:text-slate-50">
                                        R$ {{ number_format((float) $student->fee, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        @if ($student->active)
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-100 dark:bg-emerald-700 text-emerald-800 dark:text-emerald-50 border border-emerald-300 dark:border-emerald-600">
                                                ✓ Ativo
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-slate-200 dark:bg-slate-600 text-slate-700 dark:text-slate-200">
                                                Inativo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('alunos.show', $student) }}" class="inline-flex items-center justify-center w-8 h-8 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded-lg transition" title="Ver perfil">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            @if (auth()->user()?->isAdmin())
                                                <a href="{{ route('alunos.edit', $student) }}" class="inline-flex items-center justify-center w-8 h-8 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/30 rounded-lg transition" title="Editar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                                <form method="POST" action="{{ route('alunos.destroy', $student) }}" onsubmit="return confirm('Tem certeza que deseja excluir este aluno?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-lg transition" title="Excluir">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if ($students->hasPages())
                    <div class="mt-6 bg-white dark:bg-slate-800 rounded-lg p-4 border border-slate-200 dark:border-slate-700">
                        <div class="flex justify-center">
                            {{ $students->links() }}
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>