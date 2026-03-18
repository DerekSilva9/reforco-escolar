<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-900 leading-tight">
                Turmas
            </h2>

            <a href="{{ route('turmas.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-900 border border-blue-950 rounded-md font-semibold text-xs text-amber-50 uppercase tracking-widest hover:bg-blue-800 shadow-sm">
                Nova turma
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100">
                <div class="p-6 text-slate-900">
                    @if ($teams->isEmpty())
                        <div class="text-slate-600">
                            Nenhuma turma cadastrada ainda.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="text-left text-slate-600 border-b border-blue-100">
                                        <th class="py-2 pe-4">Turma</th>
                                        <th class="py-2 pe-4">Turno</th>
                                        <th class="py-2 pe-4">Professor</th>
                                        <th class="py-2 pe-4">Alunos</th>
                                        <th class="py-2">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($teams as $team)
                                        <tr class="border-b border-blue-50 last:border-0">
                                            <td class="py-3 pe-4 font-medium">{{ $team->name }}</td>
                                            <td class="py-3 pe-4">{{ $team->time }}</td>
                                            <td class="py-3 pe-4">{{ $team->teacher?->name ?? '-' }}</td>
                                            <td class="py-3 pe-4">{{ $team->students_count }}</td>
                                            <td class="py-3">
                                                <div class="flex flex-wrap gap-2">
                                                    <a href="{{ route('turmas.show', $team) }}" class="inline-flex items-center px-3 py-1.5 bg-green-600 rounded-md text-white hover:bg-green-700 border border-green-700 shadow-sm">
                                                        Gerenciar Turma
                                                    </a>
                                                    <a href="{{ route('presenca.index', ['team_id' => $team->id, 'date' => now()->toDateString()]) }}" class="inline-flex items-center px-3 py-1.5 bg-amber-200 rounded-md text-blue-950 hover:bg-amber-300 border border-amber-300 shadow-sm">
                                                        Marcar presença
                                                    </a>
                                                    <a href="{{ route('turmas.edit', $team) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-900 rounded-md text-amber-50 hover:bg-blue-800 shadow-sm">
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
        </div>
    </div>
</x-app-layout>
