<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                    <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                    Turmas
                </h1>
                <p class="text-xs text-slate-500 font-medium ml-4">{{ $teams->count() }} turma{{ $teams->count() !== 1 ? 's' : '' }}</p>
            </div>
            <a href="{{ route('turmas.create') }}" class="group flex items-center gap-3 p-1.5 pr-4 bg-slate-100 border border-slate-300 rounded-xl hover:border-blue-400 hover:shadow-md transition-all">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-xs group-hover:bg-blue-700 transition-colors">
                    +
                </div>
                <div class="flex flex-col">
                    <span class="text-[10px] uppercase tracking-wider font-bold text-slate-400 leading-none">Ação</span>
                    <span class="text-sm font-semibold text-slate-700 leading-tight">Nova Turma</span>
                </div>
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if ($teams->isEmpty())
            <div class="bg-white rounded-2xl p-16 text-center shadow-md border border-slate-300">
                <svg class="w-20 h-20 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                </svg>
                <p class="text-slate-600 text-lg mb-6">Nenhuma turma cadastrada ainda</p>
                <a href="{{ route('turmas.create') }}" class="inline-flex items-center gap-2 bg-blue-600 text-white px-8 py-3 rounded-xl font-semibold hover:bg-blue-700 transition">
                    + Criar Primeira Turma
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($teams as $team)
                    <div class="bg-white rounded-2xl shadow-md border border-slate-300 hover:border-slate-400 hover:shadow-lg transition-all group overflow-hidden">
                        <!-- Header da Turma -->
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-lg font-bold text-slate-900">{{ $team->name }}</h3>
                            <p class="text-slate-500 text-sm mt-1 font-medium">{{ $team->time }}</p>
                        </div>

                        <!-- Conteúdo -->
                        <div class="p-6">
                            <div class="space-y-4 mb-6">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Alunos</p>
                                        <p class="text-3xl font-bold text-slate-900 mt-1">{{ $team->students_count }}</p>
                                    </div>
                                    <div class="bg-slate-100 p-3 rounded-lg group-hover:bg-blue-50 transition">
                                        <svg class="w-6 h-6 text-slate-600 group-hover:text-blue-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="border-t border-slate-100 pt-4">
                                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest">Professor</p>
                                    <p class="text-sm font-semibold text-slate-900 mt-1">{{ $team->teacher?->name ?? '-' }}</p>
                                </div>
                            </div>

                            <!-- Ações -->
                            <div class="space-y-2">
                                <a href="{{ route('turmas.show', $team) }}" class="block w-full text-center px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-lg font-semibold text-slate-900 hover:bg-slate-100 hover:border-slate-400 transition text-sm">
                                    Gerenciar
                                </a>
                                <a href="{{ route('presenca.index', ['team_id' => $team->id, 'date' => now()->toDateString()]) }}" class="block w-full text-center px-4 py-2.5 bg-blue-50 border border-blue-200 rounded-lg font-semibold text-blue-700 hover:bg-blue-100 hover:border-blue-300 transition text-sm">
                                    Chamada
                                </a>
                                <a href="{{ route('turmas.edit', $team) }}" class="block w-full text-center px-4 py-2.5 bg-slate-100 border border-slate-300 rounded-lg font-semibold text-slate-900 hover:bg-slate-200 hover:border-slate-400 transition text-sm">
                                    Editar
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
