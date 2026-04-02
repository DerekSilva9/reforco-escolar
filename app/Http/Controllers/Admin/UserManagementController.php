<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $role = $request->string('role')->toString();
        if (! in_array($role, [User::ROLE_PROFESSOR, User::ROLE_RESPONSAVEL], true)) {
            $role = User::ROLE_PROFESSOR;
        }

        $users = User::query()
            ->where('role', $role)
            ->when($role === User::ROLE_PROFESSOR, fn ($q) => $q->withCount('teams'))
            ->when($role === User::ROLE_RESPONSAVEL, fn ($q) => $q->withCount('studentsAsResponsavel'))
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'phone', 'role', 'created_at']);

        return view('admin.users.index', [
            'role' => $role,
            'users' => $users,
        ]);
    }

    public function create(Request $request)
    {
        $this->authorize('create', User::class);

        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::in([User::ROLE_PROFESSOR, User::ROLE_RESPONSAVEL])],
            'phone' => [
                Rule::requiredIf(fn () => $request->string('role')->toString() === User::ROLE_RESPONSAVEL),
                'nullable',
                'string',
                'max:30',
                'unique:users,phone',
            ],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('admin.users.index', ['role' => $validated['role']])
            ->with('success', 'Usuário criado com sucesso.');
    }

    public function destroy(Request $request, User $user)
    {
        $this->authorize('delete', $user);

        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Você não pode excluir seu próprio usuário.');
        }

        if ($user->role === User::ROLE_ADMIN) {
            return back()->with('error', 'Não é permitido excluir um admin.');
        }

        if ($user->role === User::ROLE_PROFESSOR && $user->teams()->exists()) {
            return back()->with('error', 'Esse professor possui turmas. Reatribua as turmas antes de excluir.');
        }

        if ($user->role === User::ROLE_RESPONSAVEL && $user->studentsAsResponsavel()->exists()) {
            return back()->with('error', 'Esse responsável possui alunos vinculados. Reatribua os alunos antes de excluir.');
        }

        $role = $user->role;
        $user->delete();

        return redirect()
            ->route('admin.users.index', ['role' => $role])
            ->with('success', 'Usuário excluído.');
    }
}
