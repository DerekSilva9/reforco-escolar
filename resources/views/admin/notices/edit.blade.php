<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-slate-900 dark:text-slate-50 leading-tight">
                    Editar recado
                </h2>
                <div class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Ajuste o conteúdo e a janela de exibição.
                </div>
            </div>

            <a href="{{ route('admin.notices.index') }}" class="text-sm text-slate-700 dark:text-slate-300 hover:text-blue-950 dark:hover:text-slate-50">
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur overflow-hidden shadow-sm sm:rounded-lg border border-blue-100 dark:border-slate-700">
                <div class="p-6 text-slate-900 dark:text-slate-50">
                    <form method="POST" action="{{ route('admin.notices.update', $notice) }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="title" value="Título" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $notice->title)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="body" value="Mensagem" />
                            <textarea id="body" name="body" rows="8" class="mt-1 block w-full border-blue-200 dark:border-slate-600 focus:border-blue-700 dark:focus:border-blue-500 focus:ring-blue-700 dark:focus:ring-blue-500 rounded-md shadow-sm bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-50" required>{{ old('body', $notice->body) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('body')" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="starts_at" value="Início (opcional)" />
                                <x-text-input id="starts_at" name="starts_at" type="datetime-local" class="mt-1 block w-full" :value="old('starts_at', optional($notice->starts_at)->format('Y-m-d\\TH:i'))" />
                                <x-input-error class="mt-2" :messages="$errors->get('starts_at')" />
                            </div>
                            <div>
                                <x-input-label for="ends_at" value="Fim (opcional)" />
                                <x-text-input id="ends_at" name="ends_at" type="datetime-local" class="mt-1 block w-full" :value="old('ends_at', optional($notice->ends_at)->format('Y-m-d\\TH:i'))" />
                                <x-input-error class="mt-2" :messages="$errors->get('ends_at')" />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="published_at" value="Publicado em (vazio = rascunho)" />
                            <x-text-input id="published_at" name="published_at" type="datetime-local" class="mt-1 block w-full" :value="old('published_at', optional($notice->published_at)->format('Y-m-d\\TH:i'))" />
                            <x-input-error class="mt-2" :messages="$errors->get('published_at')" />
                        </div>

                        <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-700 dark:text-slate-200">
                            <input type="checkbox" name="pinned" value="1" class="rounded border-slate-300 text-blue-700 shadow-sm focus:ring-blue-700" @checked(old('pinned', $notice->pinned))>
                            Fixar no topo
                        </label>

                        <div class="flex items-center gap-3">
                            <x-primary-button>Salvar</x-primary-button>
                            <a href="{{ route('admin.notices.index') }}" class="text-sm text-slate-700 dark:text-slate-300 hover:text-blue-950 dark:hover:text-slate-50">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

