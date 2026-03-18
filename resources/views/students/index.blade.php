<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-900 leading-tight">
                Alunos
            </h2>

            @if (auth()->user()?->isAdmin())
                <a href="{{ route('alunos.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-900 border border-blue-950 rounded-md font-semibold text-xs text-amber-50 uppercase tracking-widest hover:bg-blue-800 shadow-sm">
                    Novo aluno
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100">
                <div class="p-6 text-slate-900">
                    <form method="GET" action="{{ route('alunos.index') }}" class="flex flex-wrap items-end gap-3">
                        <div>
                            <x-input-label for="team_id" value="Filtrar por turma" />
                            <select id="team_id" name="team_id" class="mt-1 block w-72 border-blue-200 focus:border-blue-700 focus:ring-blue-700 rounded-md shadow-sm bg-white">
                                <option value="">Todas</option>
                                @foreach ($teams as $team)
                                    <option value="{{ $team->id }}" @selected((string) $selectedTeamId === (string) $team->id)>
                                        {{ $team->name }} ({{ $team->time }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <x-primary-button>Aplicar</x-primary-button>

                        @if ($selectedTeamId)
                            <a href="{{ route('alunos.index') }}" class="text-sm text-slate-700 hover:text-blue-950">Limpar</a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="bg-white/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100">
                <div class="p-6 text-slate-900">
                    @if ($students->isEmpty())
                        <div class="text-slate-600">
                            Nenhum aluno encontrado.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="text-left text-slate-600 border-b border-blue-100">
                                        <th class="py-2 pe-4">Aluno</th>
                                        <th class="py-2 pe-4">Responsável</th>
                                        <th class="py-2 pe-4">Telefone</th>
                                        <th class="py-2 pe-4">Turma</th>
                                        <th class="py-2 pe-4">Status</th>
                                        <th class="py-2">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($students as $student)
                                        <tr class="border-b border-blue-50 last:border-0">
                                            <td class="py-3 pe-4 font-medium">{{ $student->name }}</td>
                                            <td class="py-3 pe-4">{{ $student->responsavel?->name ?? $student->parent_name ?? '-' }}</td>
                                            <td class="py-3 pe-4">{{ $student->responsavel?->phone ?? $student->phone ?? '-' }}</td>
                                            <td class="py-3 pe-4">{{ $student->team?->name ?? '-' }}</td>
                                            <td class="py-3 pe-4">
                                                @if ($student->active)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-900">Ativo</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-800">Inativo</span>
                                                @endif
                                            </td>
                                            <td class="py-3">
                                                <div class="flex flex-wrap gap-2">
                                                    <a href="{{ route('alunos.show', $student) }}" class="inline-flex items-center px-3 py-1.5 bg-amber-200 rounded-md text-blue-950 hover:bg-amber-300 border border-amber-300 shadow-sm">
                                                        Ver perfil
                                                    </a>

                                                    @if (auth()->user()?->isAdmin())
                                                        <a href="{{ route('alunos.edit', $student) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-900 rounded-md text-amber-50 hover:bg-blue-800 shadow-sm">
                                                            Editar
                                                        </a>

                                                        <form method="POST" action="{{ route('alunos.destroy', $student) }}" onsubmit="return confirm('Excluir este aluno? Isso apaga presenças e pagamentos vinculados.');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <x-danger-button type="submit">Excluir</x-danger-button>
                                                        </form>
                                                    @endif
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
        </div>
    </div>
</x-app-layout>
