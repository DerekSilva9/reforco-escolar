<?php

namespace App\Http\Controllers;

use App\Exports\StudentExport;
use App\Models\Student;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $this->authorize('viewAny', Student::class);

        $teams = Team::query()
            ->when(! $user->isAdmin(), fn ($query) => $query->where('user_id', $user->id))
            ->orderBy('name')
            ->select('id', 'name', 'time') // Selecionar apenas colunas necessárias
            ->get();

        $students = Student::query()
            ->select('id', 'name', 'team_id', 'responsavel_id', 'school_year', 'phone', 'fee', 'active', 'parent_name', 'birth_date')
            ->with([
                'team:id,name,time,user_id',
                'responsavel:id,name,phone'
            ])
            ->when(! $user->isAdmin(), fn ($query) => $query->whereHas('team', fn ($q) => $q->where('user_id', $user->id)))
            ->when($request->filled('team_id'), fn ($query) => $query->where('team_id', $request->integer('team_id')))
            ->orderBy('active', 'desc')
            ->orderBy('name')
            ->get();

        return view('students.index', [
            'students' => $students,
            'teams' => $teams,
            'selectedTeamId' => $request->filled('team_id') ? $request->integer('team_id') : null,
        ]);
    }

    public function export(Request $request)
    {
        $user = $request->user();
        
        // Usar Policy
        $this->authorize('export', Student::class);

        $teamId = $request->filled('team_id') ? $request->integer('team_id') : null;

        // Validar que o usuário tem permissão na turma
        if ($teamId) {
            $team = Team::find($teamId);
            if (! $team) {
                abort(404, 'Turma não encontrada');
            }
            
            $this->authorize('view', $team);
        }

        $teamName = $teamId ? Team::find($teamId)?->name : 'Todos';
        $fileName = "alunos_{$teamName}_" . now()->format('Y-m-d_H-i-s') . '.xlsx';
        $filePath = storage_path('app/exports/' . $fileName);

        // Criar diretório se não existir
        if (! is_dir(storage_path('app/exports'))) {
            mkdir(storage_path('app/exports'), 0755, true);
        }

        $export = new StudentExport($teamId);
        $export->export($filePath);

        return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
    }

    public function show(Request $request, Student $student)
    {
        $this->authorize('view', $student);

        $student->load([
            'team',
            'responsavel',
            'payments' => fn ($q) => $q->orderByDesc('paid_at')->limit(12),
            'attendances' => fn ($q) => $q->orderByDesc('date')->limit(30),
        ]);

        $now = now();
        $year = $now->year;
        $month = $now->month;

        $currentMonthPayment = $student->payments()
            ->where('year', $year)
            ->where('month', $month)
            ->whereNotNull('paid_at')
            ->first();

        return view('students.show', [
            'student' => $student,
            'currentMonthPayment' => $currentMonthPayment,
        ]);
    }

    public function updateNotes(Request $request, Student $student)
    {
        $this->authorize('update', $student);
        
        $student->load('team');

        $validated = $request->validate([
            'notes' => ['nullable', 'string'],
        ]);

        $student->update([
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('alunos.show', $student)
            ->with('success', 'Observações atualizadas.');
    }

    public function create(Request $request)
    {
        $this->authorize('create', Student::class);

        $teams = Team::query()
            ->orderBy('name')
            ->get(['id', 'name', 'time']);

        $responsaveis = User::query()
            ->where('role', User::ROLE_RESPONSAVEL)
            ->orderBy('name')
            ->get(['id', 'name', 'phone']);

        return view('students.create', [
            'teams' => $teams,
            'responsaveis' => $responsaveis,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Student::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'responsavel_id' => ['required', 'integer', Rule::exists('users', 'id')->where('role', User::ROLE_RESPONSAVEL)],
            'team_id' => ['required', 'integer', Rule::exists(Team::class, 'id')],
            'fee' => ['required', 'numeric', 'min:0'],
            'due_day' => ['required', 'integer', 'min:1', 'max:31'],
            'active' => ['nullable', 'boolean'],
            'class_start_time' => ['nullable', 'date_format:H:i'],
            'class_end_time' => ['nullable', 'date_format:H:i'],
            'notes' => ['nullable', 'string'],
            'school_year' => ['nullable', 'string', 'max:255'],
            'school' => ['nullable', 'string', 'max:255'],
        ]);

        $responsavel = User::query()
            ->where('role', User::ROLE_RESPONSAVEL)
            ->findOrFail($validated['responsavel_id']);

        $validated['active'] = (bool) ($validated['active'] ?? false);
        $validated['parent_name'] = $responsavel->name;
        $validated['phone'] = $responsavel->phone;

        Student::create($validated);

        return redirect()
            ->route('alunos.index')
            ->with('success', 'Aluno cadastrado.');
    }

    public function edit(Request $request, Student $student)
    {
        $this->authorize('update', $student);

        $teams = Team::query()
            ->orderBy('name')
            ->get(['id', 'name', 'time']);

        $responsaveis = User::query()
            ->where('role', User::ROLE_RESPONSAVEL)
            ->orderBy('name')
            ->get(['id', 'name', 'phone']);

        return view('students.edit', [
            'student' => $student,
            'teams' => $teams,
            'responsaveis' => $responsaveis,
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $this->authorize('update', $student);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'responsavel_id' => ['required', 'integer', Rule::exists('users', 'id')->where('role', User::ROLE_RESPONSAVEL)],
            'team_id' => ['required', 'integer', Rule::exists(Team::class, 'id')],
            'fee' => ['required', 'numeric', 'min:0'],
            'due_day' => ['required', 'integer', 'min:1', 'max:31'],
            'active' => ['nullable', 'boolean'],
            'class_start_time' => ['nullable', 'date_format:H:i'],
            'class_end_time' => ['nullable', 'date_format:H:i'],
            'notes' => ['nullable', 'string'],
            'school_year' => ['nullable', 'string', 'max:255'],
            'school' => ['nullable', 'string', 'max:255'],
        ]);

        $responsavel = User::query()
            ->where('role', User::ROLE_RESPONSAVEL)
            ->findOrFail($validated['responsavel_id']);

        $validated['active'] = (bool) ($validated['active'] ?? false);
        $validated['parent_name'] = $responsavel->name;
        $validated['phone'] = $responsavel->phone;

        $student->update($validated);

        return redirect()
            ->route('alunos.index')
            ->with('success', 'Aluno atualizado.');
    }

    public function destroy(Request $request, Student $student)
    {
        $this->authorize('delete', $student);

        $student->delete();

        return redirect()
            ->route('alunos.index')
            ->with('success', 'Aluno excluído.');
    }
}
