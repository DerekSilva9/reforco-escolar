<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-slate-900 leading-tight">
                    Usuários
                </h2>
                <div class="text-sm text-slate-600 mt-1">
                    Professores e responsáveis (cadastro e exclusão).
                </div>
            </div>

            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-900 border border-blue-950 rounded-md font-semibold text-xs text-amber-50 uppercase tracking-widest hover:bg-blue-800 shadow-sm">
                Novo usuário
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100">
                <div class="p-6 text-slate-900">
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('admin.users.index', ['role' => 'professor']) }}"
                            class="px-3 py-2 rounded-md border text-sm font-semibold {{ $role === 'professor' ? 'bg-amber-200 border-amber-300 text-blue-950' : 'bg-white border-blue-200 text-slate-700 hover:bg-amber-50' }}">
                            Professores
                        </a>
                        <a href="{{ route('admin.users.index', ['role' => 'responsavel']) }}"
                            class="px-3 py-2 rounded-md border text-sm font-semibold {{ $role === 'responsavel' ? 'bg-amber-200 border-amber-300 text-blue-950' : 'bg-white border-blue-200 text-slate-700 hover:bg-amber-50' }}">
                            Responsáveis
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100">
                <div class="p-6 text-slate-900">
                    @if ($users->isEmpty())
                        <div class="text-slate-600">
                            Nenhum usuário nesse filtro.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead>
                                    <tr class="text-left text-slate-600 border-b border-blue-100">
                                        <th class="py-2 pe-4">Nome</th>
                                        <th class="py-2 pe-4">Email</th>
                                        <th class="py-2 pe-4">Telefone</th>
                                        <th class="py-2 pe-4">
                                            {{ $role === 'professor' ? 'Turmas' : 'Alunos' }}
                                        </th>
                                        <th class="py-2">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $u)
                                        @php
                                            $count = $role === 'professor' ? ($u->teams_count ?? 0) : ($u->students_as_responsavel_count ?? 0);
                                        @endphp
                                        <tr class="border-b border-blue-50 last:border-0">
                                            <td class="py-3 pe-4 font-medium text-slate-900">{{ $u->name }}</td>
                                            <td class="py-3 pe-4">{{ $u->email }}</td>
                                            <td class="py-3 pe-4">{{ $u->phone ?? '-' }}</td>
                                            <td class="py-3 pe-4">{{ $count }}</td>
                                            <td class="py-3">
                                                <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Excluir este usuário?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <x-danger-button type="submit">Excluir</x-danger-button>
                                                </form>
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

