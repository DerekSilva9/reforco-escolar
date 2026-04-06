<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $this->authorize('attendance-view');

        $teams = Team::query()
            ->when(! $user->isAdmin(), fn ($query) => $query->where('user_id', $user->id))
            ->orderBy('name')
            ->get(['id', 'name', 'time', 'user_id']);

        $date = $request->string('date')->toString();
        if ($date === '' || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            $date = now()->toDateString();
        }

        $attendanceDate = Carbon::createFromFormat('Y-m-d', $date)->toDateString();

        $selectedTeamId = $request->filled('team_id') ? $request->integer('team_id') : null;
        $team = null;
        $students = collect();
        $attendances = collect();

        if ($selectedTeamId) {
            $team = $teams->firstWhere('id', $selectedTeamId);
            if (! $team) {
                $this->authorize('view', $team);
            }

            $students = Team::findOrFail($selectedTeamId)
                ->students()
                ->where('active', true)
                ->orderBy('name')
                ->get();

            $attendances = Attendance::query()
                ->whereIn('student_id', $students->pluck('id'))
                ->whereDate('date', $attendanceDate)
                ->get()
                ->keyBy('student_id');
        }

        return view('presence.index', [
            'teams' => $teams,
            'team' => $team,
            'selectedTeamId' => $selectedTeamId,
            'date' => $date,
            'students' => $students,
            'attendances' => $attendances,
        ]);
    }

    public function save(Request $request)
    {
        $user = $request->user();
        $this->authorize('attendance-view');

        $validated = $request->validate([
            'team_id' => ['required', 'integer', 'exists:teams,id'],
            'date' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'present' => ['array'],
            'present.*' => ['boolean'],
            'obs' => ['array'],
            'obs.*' => ['nullable', 'string', 'max:500'],
        ]);

        $team = Team::findOrFail($validated['team_id']);
        $this->authorize('save', $team);

        $date = $validated['date'];
        $attendanceDate = Carbon::createFromFormat('Y-m-d', $date)->toDateString();

        $students = $team->students()
            ->where('active', true)
            ->get(['id']);

        $present = $validated['present'] ?? [];
        $obs = $validated['obs'] ?? [];

        DB::transaction(function () use ($students, $attendanceDate, $present, $obs) {
            foreach ($students as $student) {
                $studentId = (string) $student->id;

                $isPresent = (bool) (($present[$studentId] ?? false) ? true : false);
                $note = trim((string) ($obs[$studentId] ?? ''));
                $note = $note === '' ? null : $note;

                $matches = Attendance::query()
                    ->where('student_id', $student->id)
                    ->whereDate('date', $attendanceDate)
                    ->orderBy('id')
                    ->get();

                $attendance = $matches->first();

                if ($attendance) {
                    $matches->skip(1)->each->delete();

                    $attendance->update([
                        'date' => $attendanceDate,
                        'present' => $isPresent,
                        'obs' => $note,
                    ]);
                } else {
                    Attendance::create([
                        'student_id' => $student->id,
                        'date' => $attendanceDate,
                        'present' => $isPresent,
                        'obs' => $note,
                    ]);
                }
            }
        });

        return redirect()
            ->route('presenca.index', ['team_id' => $team->id, 'date' => $date])
            ->with('success', 'Chamada salva.');
    }

    public function create(Request $request, Team $team)
    {
        $this->authorize('save', $team);

        $date = $request->string('date')->toString();
        if ($date === '') {
            $date = now()->toDateString();
        }

        return redirect()->route('presenca.index', ['team_id' => $team->id, 'date' => $date]);
    }

    public function store(Request $request, Team $team)
    {
        $this->authorize('save', $team);

        $request->merge(['team_id' => $team->id]);

        return $this->save($request);
    }
}
