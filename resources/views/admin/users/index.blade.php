<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-slate-900 dark:text-slate-50 leading-tight">
                    Usuários
                </h2>
                <div class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Professores e responsáveis (cadastro e exclusão).
                </div>
            </div>

            <a href="{{ route('admin.users.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center sm:justify-start px-3 sm:px-4 py-2 bg-blue-900 dark:bg-blue-950 border border-blue-950 dark:border-blue-900 rounded-md font-semibold text-xs text-amber-50 uppercase tracking-widest hover:bg-blue-800 dark:hover:bg-blue-900 shadow-sm">
                + Novo usuário
            </a>
        </div>
    </x-slot>

    <div class="py-6 md:py-8 lg:py-12">
        <div class="max-w-7xl mx-auto px-3 md:px-6 lg:px-8 space-y-4 md:space-y-6">
            <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100 dark:border-slate-700">
                <div class="p-4 md:p-6 text-slate-900 dark:text-slate-50">
                    <div class="flex flex-wrap items-center gap-2 mb-6">
                        <a href="{{ route('admin.users.index', ['role' => 'professor']) }}"
                            class="px-3 py-2 rounded-md border text-xs md:text-sm font-semibold {{ $role === 'professor' ? 'bg-amber-200 dark:bg-amber-700 border-amber-300 dark:border-amber-600 text-blue-950 dark:text-amber-50' : 'bg-white dark:bg-slate-700 border-blue-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-amber-50 dark:hover:bg-slate-600' }}">
                            👨‍🏫 Professores
                        </a>
                        <a href="{{ route('admin.users.index', ['role' => 'responsavel']) }}"
                            class="px-3 py-2 rounded-md border text-xs md:text-sm font-semibold {{ $role === 'responsavel' ? 'bg-amber-200 dark:bg-amber-700 border-amber-300 dark:border-amber-600 text-blue-950 dark:text-amber-50' : 'bg-white dark:bg-slate-700 border-blue-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-amber-50 dark:hover:bg-slate-600' }}">
                            👨‍👩‍👧 Responsáveis
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100 dark:border-slate-700">
                <div class="p-4 md:p-6 text-slate-900 dark:text-slate-50">
                    @if ($users->isEmpty())
                        <div class="text-sm md:text-base text-slate-600 dark:text-slate-400 py-8 text-center">
                            Nenhum usuário nesse filtro.
                        </div>
                    @else
                        <!-- Desktop Table View -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="text-left text-slate-600 dark:text-slate-300 border-b border-blue-100 dark:border-slate-700">
                                        <th class="py-3 px-4 font-semibold">Nome</th>
                                        <th class="py-3 px-4 font-semibold">Email</th>
                                        <th class="py-3 px-4 font-semibold">Telefone</th>
                                        <th class="py-3 px-4 font-semibold text-center">
                                            {{ $role === 'professor' ? 'Turmas' : 'Alunos' }}
                                        </th>
                                        <th class="py-3 px-4 font-semibold text-right">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-blue-50 dark:divide-slate-700">
                                    @foreach ($users as $u)
                                        @php
                                            $count = $role === 'professor' ? ($u->teams_count ?? 0) : ($u->students_as_responsavel_count ?? 0);
                                        @endphp
                                        <tr class="hover:bg-blue-50 dark:hover:bg-slate-700 transition-colors">
                                            <td class="py-3 px-4 font-medium text-slate-900 dark:text-slate-50">{{ $u->name }}</td>
                                            <td class="py-3 px-4 text-slate-700 dark:text-slate-300 text-sm">{{ $u->email }}</td>
                                            <td class="py-3 px-4 text-slate-700 dark:text-slate-300">{{ $u->phone ?? '-' }}</td>
                                            <td class="py-3 px-4 text-center">
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-900 dark:text-blue-300 font-semibold text-sm">
                                                    {{ $count }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 text-right">
                                                <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Excluir este usuário?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1 bg-red-600 dark:bg-red-700 hover:bg-red-700 dark:hover:bg-red-600 text-white rounded text-xs font-semibold transition-colors">
                                                        Excluir
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Card View -->
                        <div class="md:hidden space-y-3">
                            @foreach ($users as $u)
                                @php
                                    $count = $role === 'professor' ? ($u->teams_count ?? 0) : ($u->students_as_responsavel_count ?? 0);
                                @endphp
                                <div class="bg-gradient-to-br from-blue-50 dark:from-slate-700 to-indigo-50 dark:to-slate-700 rounded-lg border border-blue-200 dark:border-slate-600 p-4">
                                    <!-- Nome -->
                                    <div class="mb-3">
                                        <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold uppercase tracking-wide">Nome</p>
                                        <p class="text-base font-semibold text-slate-900 dark:text-slate-50">{{ $u->name }}</p>
                                    </div>

                                    <!-- Grid de informações -->
                                    <div class="grid grid-cols-2 gap-3 mb-4">
                                        <div class="bg-white dark:bg-slate-800 rounded p-3 border border-blue-100 dark:border-slate-600">
                                            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mb-1">Email</p>
                                            <p class="text-xs md:text-sm text-slate-900 dark:text-slate-50 break-all">{{ $u->email }}</p>
                                        </div>
                                        <div class="bg-white dark:bg-slate-800 rounded p-3 border border-blue-100 dark:border-slate-600">
                                            <p class="text-xs text-slate-600 dark:text-slate-400 font-semibold mb-1">Telefone</p>
                                            <p class="text-xs md:text-sm text-slate-900 dark:text-slate-50">{{ $u->phone ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <!-- Count and Action -->
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 dark:bg-blue-700 text-white font-bold text-sm">
                                                {{ $count }}
                                            </span>
                                            <span class="text-xs text-slate-600 dark:text-slate-400">
                                                {{ $role === 'professor' ? 'turma(s)' : 'aluno(s)' }}
                                            </span>
                                        </div>
                                        <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Excluir este usuário?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-red-600 dark:bg-red-700 hover:bg-red-700 dark:hover:bg-red-600 text-white rounded text-xs font-semibold transition-colors">
                                                Excluir
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

