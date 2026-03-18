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
        $teamsWithAttendanceToday = $teamIds->isEmpty()
            ? collect()
            : Attendance::query()
                ->join('students', 'attendances.student_id', '=', 'students.id')
                ->whereDate('attendances.date', $today)
                ->whereIn('students.team_id', $teamIds)
                ->distinct()
                ->pluck('students.team_id');

        return view('dashboard', [
            'mode' => 'professor',
            'teams' => $teams,
            'teamsWithAttendanceToday' => $teamsWithAttendanceToday,
        ]);
    }
}
