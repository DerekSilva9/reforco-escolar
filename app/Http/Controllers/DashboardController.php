<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Team;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $now = now();
        $today = $now->toDateString();
        $year = $now->year;
        $month = $now->month;

        if ($user->isAdmin()) {
            $totalStudentsCount = Student::query()->count();

            $pendingFeesCount = Student::query()
                ->where('active', true)
                ->whereDoesntHave('payments', fn ($q) => $q->where('year', $year)->where('month', $month)->whereNotNull('paid_at'))
                ->count();

            $classesTodayCount = Attendance::query()
                ->join('students', 'attendances.student_id', '=', 'students.id')
                ->whereDate('attendances.date', $today)
                ->distinct()
                ->pluck('students.team_id')
                ->count();

            $latestPayments = Payment::query()
                ->whereNotNull('paid_at')
                ->with(['student.team'])
                ->orderByDesc('paid_at')
                ->limit(8)
                ->get();

            return view('dashboard', [
                'mode' => 'admin',
                'totalStudentsCount' => $totalStudentsCount,
                'pendingFeesCount' => $pendingFeesCount,
                'classesTodayCount' => $classesTodayCount,
                'latestPayments' => $latestPayments,
            ]);
        }

        if ($user->isResponsavel()) {
            return view('dashboard', [
                'mode' => 'responsavel',
            ]);
        }

        $teams = Team::query()
            ->where('user_id', $user->id)
            ->withCount(['students' => fn ($q) => $q->where('active', true)])
            ->orderBy('name')
            ->get();

        $teamIds = $teams->pluck('id');
        
        // Total de alunos ativos
        $totalStudentsCount = Student::query()
            ->where('active', true)
            ->whereIn('team_id', $teamIds)
            ->count();

        // Turmas com aula hoje
        $teamsWithAttendanceToday = $teamIds->isEmpty()
            ? collect()
            : Attendance::query()
                ->join('students', 'attendances.student_id', '=', 'students.id')
                ->whereDate('attendances.date', $today)
                ->whereIn('students.team_id', $teamIds)
                ->distinct()
                ->pluck('students.team_id');

        // Presença recente (últimas 10 aulas)
        $recentAttendances = $teamIds->isEmpty()
            ? collect()
            : Attendance::query()
                ->join('students', 'attendances.student_id', '=', 'students.id')
                ->whereIn('students.team_id', $teamIds)
                ->with(['student.team'])
                ->orderByDesc('attendances.date')
                ->limit(10)
                ->get(['attendances.*']);

        // Taxa geral de presença deste mês
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();
        $attendanceStats = $teamIds->isEmpty()
            ? ['total' => 0, 'present' => 0]
            : Attendance::query()
                ->join('students', 'attendances.student_id', '=', 'students.id')
                ->whereIn('students.team_id', $teamIds)
                ->whereBetween('attendances.date', [$monthStart, $monthEnd])
                ->selectRaw('COUNT(*) as total, SUM(CASE WHEN present = true THEN 1 ELSE 0 END) as present')
                ->first();

        $attendanceRate = $attendanceStats->total > 0 
            ? round(($attendanceStats->present / $attendanceStats->total) * 100, 1)
            : 0;

        // Alunos com muitas faltas (mais de 20% de faltas no mês)
        $studentsWithHighAbsence = $teamIds->isEmpty()
            ? collect()
            : Student::query()
                ->whereIn('team_id', $teamIds)
                ->where('active', true)
                ->get()
                ->filter(function (Student $student) use ($monthStart, $monthEnd) {
                    $total = $student->attendances()
                        ->whereBetween('date', [$monthStart, $monthEnd])
                        ->count();
                    
                    if ($total === 0) return false;
                    
                    $absences = $student->attendances()
                        ->whereBetween('date', [$monthStart, $monthEnd])
                        ->where('present', false)
                        ->count();
                    
                    return ($absences / $total) > 0.2;
                })
                ->values();

        // Turmas sem aula registrada hoje
        $teamsWithoutAttendanceToday = $teams->filter(fn ($team) => !$teamsWithAttendanceToday->contains($team->id));

        return view('dashboard', [
            'mode' => 'professor',
            'teams' => $teams,
            'totalStudentsCount' => $totalStudentsCount,
            'teamsWithAttendanceToday' => $teamsWithAttendanceToday,
            'teamsWithoutAttendanceToday' => $teamsWithoutAttendanceToday,
            'recentAttendances' => $recentAttendances,
            'attendanceRate' => $attendanceRate,
            'studentsWithHighAbsence' => $studentsWithHighAbsence,
        ]);
    }
}
