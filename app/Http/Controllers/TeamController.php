<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $this->authorize('viewAny', Team::class);

        $teams = Team::query()
            ->withTeacher()
            ->withCount('students')
            ->when(! $user->isAdmin(), fn ($query) => $query->where('user_id', $user->id))
            ->orderBy('name')
            ->get();

        return view('teams.index', [
            'teams' => $teams,
        ]);
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $this->authorize('create', Team::class);

        $teachers = $user->isAdmin()
            ? User::query()->where('role', User::ROLE_PROFESSOR)->orderBy('name')->get(['id', 'name'])
            : collect();

        return view('teams.create', [
            'teachers' => $teachers,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $this->authorize('create', Team::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'time' => ['required', 'string', 'max:255'],
            'user_id' => [
                Rule::requiredIf($user->isAdmin()),
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where('role', User::ROLE_PROFESSOR),
            ],
        ]);

        if (! $user->isAdmin()) {
            $validated['user_id'] = $user->id;
        }

        Team::create($validated);

        return redirect()
            ->route('turmas.index')
            ->with('success', 'Turma criada com sucesso.');
    }

    public function edit(Request $request, Team $team)
    {
        $user = $request->user();
        $this->authorize('update', $team);

        $teachers = $user->isAdmin()
            ? User::query()->where('role', User::ROLE_PROFESSOR)->orderBy('name')->get(['id', 'name'])
            : collect();

        return view('teams.edit', [
            'team' => $team,
            'teachers' => $teachers,
        ]);
    }

    public function update(Request $request, Team $team)
    {
        $user = $request->user();
        $this->authorize('update', $team);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'time' => ['required', 'string', 'max:255'],
            'user_id' => [
                Rule::requiredIf($user->isAdmin()),
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where('role', User::ROLE_PROFESSOR),
            ],
        ]);

        if (! $user->isAdmin()) {
            $validated['user_id'] = $user->id;
        }

        $team->update($validated);

        return redirect()
            ->route('turmas.index')
            ->with('success', 'Turma atualizada.');
    }

    public function show(Request $request, Team $team)
    {
        $this->authorize('view', $team);

        $query = $team->students()
            ->with(['responsavel:id,name,phone', 'payments:id,student_id,year,month,paid_at,amount']);

        // Filtro por status
        $status = $request->string('status')->toString();
        if ($status === 'active') {
            $query->where('active', true);
        } elseif ($status === 'inactive') {
            $query->where('active', false);
        }

        // Filtro por busca de nome
        $search = $request->string('search')->toString();
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('parent_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filtro por horário
        $timeFilter = $request->string('time_filter')->toString();
        if ($timeFilter !== '') {
            $query->whereNotNull('class_start_time')
                ->whereTime('class_start_time', '>=', $timeFilter);
        }

        $students = $query->orderBy('class_start_time')
            ->orderBy('name')
            ->get();

        return view('teams.show', [
            'team' => $team,
            'students' => $students,
            'status' => $status,
            'search' => $search,
            'timeFilter' => $timeFilter,
        ]);
    }
}
