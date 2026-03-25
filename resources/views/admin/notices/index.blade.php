<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-slate-900 dark:text-slate-50 leading-tight">
                    Recados da escola
                </h2>
                <div class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Publicações que aparecem no mural da dashboard.
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100 dark:border-slate-700">
                    <div class="p-6 text-slate-900 dark:text-slate-50">
                        <h3 class="font-semibold text-lg">Novo recado</h3>
                        <form method="POST" action="{{ route('admin.notices.store') }}" class="space-y-5 mt-4">
                            @csrf
                            <input type="hidden" name="publish_now" value="0">

                            <div>
                                <x-input-label for="title" value="Título" />
                                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required />
                                <x-input-error class="mt-2" :messages="$errors->get('title')" />
                            </div>

                            <div>
                                <x-input-label for="body" value="Mensagem" />
                                <textarea id="body" name="body" rows="6" class="mt-1 block w-full border-blue-200 dark:border-slate-600 focus:border-blue-700 dark:focus:border-blue-500 focus:ring-blue-700 dark:focus:ring-blue-500 rounded-md shadow-sm bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-50" required>{{ old('body') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('body')" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="starts_at" value="Início (opcional)" />
                                    <x-text-input id="starts_at" name="starts_at" type="datetime-local" class="mt-1 block w-full" :value="old('starts_at')" />
                                    <x-input-error class="mt-2" :messages="$errors->get('starts_at')" />
                                </div>
                                <div>
                                    <x-input-label for="ends_at" value="Fim (opcional)" />
                                    <x-text-input id="ends_at" name="ends_at" type="datetime-local" class="mt-1 block w-full" :value="old('ends_at')" />
                                    <x-input-error class="mt-2" :messages="$errors->get('ends_at')" />
                                </div>
                            </div>

                            <div class="flex flex-wrap items-center gap-6">
                                <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                                    <input type="checkbox" name="pinned" value="1" class="rounded border-slate-300 text-blue-700 shadow-sm focus:ring-blue-700" @checked(old('pinned'))>
                                    Fixar no topo
                                </label>
                                <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                                    <input type="checkbox" name="publish_now" value="1" class="rounded border-slate-300 text-blue-700 shadow-sm focus:ring-blue-700" @checked(old('publish_now', '1'))>
                                    Publicar agora
                                </label>
                            </div>

                            <div class="flex items-center gap-3">
                                <x-primary-button>Publicar</x-primary-button>
                                <a href="{{ route('dashboard') }}" class="text-sm text-slate-700 dark:text-slate-300 hover:text-blue-950 dark:hover:text-slate-50">Voltar</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100 dark:border-slate-700">
                    <div class="p-6 text-slate-900 dark:text-slate-50">
                        <div class="flex items-center justify-between gap-4">
                            <h3 class="font-semibold text-lg">Recados publicados</h3>
                            <div class="text-xs text-slate-500 dark:text-slate-400">
                                Total: {{ $notices->count() }}
                            </div>
                        </div>

                        @if ($notices->isEmpty())
                            <div class="text-slate-600 dark:text-slate-400 mt-4">
                                Nenhum recado ainda.
                            </div>
                        @else
                            <div class="space-y-4 mt-4">
                                @foreach ($notices as $notice)
                                    <div class="p-4 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/20">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <div class="font-bold text-slate-900 dark:text-slate-50">{{ $notice->title }}</div>
                                                    @if ($notice->pinned)
                                                        <span class="text-[10px] font-bold uppercase tracking-widest px-2 py-1 rounded-full bg-amber-200 dark:bg-amber-700 text-blue-950 dark:text-amber-50">Fixado</span>
                                                    @endif
                                                    @if (! $notice->published_at)
                                                        <span class="text-[10px] font-bold uppercase tracking-widest px-2 py-1 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200">Rascunho</span>
                                                    @endif
                                                </div>
                                                <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                                    Publicado: {{ $notice->published_at?->format('d/m/Y H:i') ?? '-' }}
                                                    @if ($notice->starts_at || $notice->ends_at)
                                                        • Janela: {{ $notice->starts_at?->format('d/m/Y H:i') ?? '-' }} → {{ $notice->ends_at?->format('d/m/Y H:i') ?? '-' }}
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('admin.notices.edit', $notice) }}" class="text-xs font-bold text-blue-700 dark:text-blue-300 hover:underline">Editar</a>
                                                <form method="POST" action="{{ route('admin.notices.destroy', $notice) }}" onsubmit="return confirm('Excluir este recado?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs font-bold text-rose-700 dark:text-rose-300 hover:underline">Excluir</button>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="text-sm text-slate-700 dark:text-slate-200 mt-3 whitespace-pre-line">{{ $notice->body }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-3">
                                            Autor: {{ $notice->author?->name ?? '-' }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
