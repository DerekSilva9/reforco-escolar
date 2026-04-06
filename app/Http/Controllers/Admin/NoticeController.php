<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Notice::class);

        $notices = Notice::query()
            ->with('author')
            ->orderByDesc('pinned')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->get();

        return view('admin.notices.index', [
            'notices' => $notices,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Notice::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'pinned' => ['nullable', 'boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'publish_now' => ['nullable', 'boolean'],
        ]);

        $publishNow = $request->boolean('publish_now', true);

        Notice::create([
            'created_by' => $request->user()->id,
            'title' => $validated['title'],
            'body' => $validated['body'],
            'pinned' => (bool) ($validated['pinned'] ?? false),
            'starts_at' => $validated['starts_at'] ?? null,
            'ends_at' => $validated['ends_at'] ?? null,
            'published_at' => $publishNow ? now() : null,
        ]);

        return redirect()
            ->route('admin.notices.index')
            ->with('success', 'Recado publicado.');
    }

    public function edit(Request $request, Notice $notice)
    {
        $this->authorize('update', $notice);

        return view('admin.notices.edit', [
            'notice' => $notice,
        ]);
    }

    public function update(Request $request, Notice $notice)
    {
        $this->authorize('update', $notice);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'pinned' => ['nullable', 'boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'published_at' => ['nullable', 'date'],
        ]);

        $notice->update([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'pinned' => (bool) ($validated['pinned'] ?? false),
            'starts_at' => $validated['starts_at'] ?? null,
            'ends_at' => $validated['ends_at'] ?? null,
            'published_at' => $validated['published_at'] ?? null,
        ]);

        return redirect()
            ->route('admin.notices.index')
            ->with('success', 'Recado atualizado.');
    }

    public function destroy(Request $request, Notice $notice)
    {
        $this->authorize('delete', $notice);

        $notice->delete();

        return redirect()
            ->route('admin.notices.index')
            ->with('success', 'Recado excluído.');
    }
}
