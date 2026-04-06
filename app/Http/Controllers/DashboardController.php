<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Notice;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $now = now();
        $today = $now->toDateString();
        $year = $now->year;
        $month = $now->month;

        $notices = collect();
        if (Schema::hasTable('notices')) {
            $notices = Notice::query()
                ->visibleOnDashboard()
                ->limit(5)
                ->get();
        }

        if ($user->isAdmin()) {
            // Cache keys with date to ensure daily refresh
            $cacheKey = 'dashboard.admin.' . now()->toDateString();
            $cacheTtl = now()->addHours(1);

            $totalStudentsCount = Cache::remember(
                "$cacheKey.total_students",
                $cacheTtl,
                fn () => Student::query()->count()
            );

            $pendingFeesCount = Cache::remember(
                "$cacheKey.pending_fees",
                $cacheTtl,
                fn () => Student::query()
                    ->where('active', true)
                    ->whereDoesntHave('payments', fn ($q) => $q->where('year', $year)->where('month', $month)->whereNotNull('paid_at'))
                    ->count()
            );

            $classesTodayCount = Cache::remember(
                "$cacheKey.classes_today",
                $cacheTtl,
                fn () => Attendance::query()
                    ->join('students', 'attendances.student_id', '=', 'students.id')
                    ->whereDate('attendances.date', $today)
                    ->distinct()
                    ->pluck('students.team_id')
                    ->count()
            );

            $latestPaymentIds = Cache::remember(
                "$cacheKey.latest_payments",
                $cacheTtl,
                fn () => Payment::query()
                    ->whereNotNull('paid_at')
                    ->orderByDesc('paid_at')
                    ->limit(8)
                    ->pluck('id')
                    ->toArray()
            );
            
            // Fetch fresh with relations
            $latestPayments = Payment::query()
                ->whereIn('id', $latestPaymentIds)
                ->with(['student.team'])
                ->orderByDesc('paid_at')
                ->get();

            return view('dashboard', [
                'mode' => 'admin',
                'totalStudentsCount' => $totalStudentsCount,
                'pendingFeesCount' => $pendingFeesCount,
                'classesTodayCount' => $classesTodayCount,
                'latestPayments' => $latestPayments,
                'notices' => $notices,
            ]);
        }

        if ($user->isResponsavel()) {
            $now = now();
            $month = $now->month;
            $year = $now->year;

            // Cache per user for 2 hours
            $responsavelCacheKey = "dashboard.responsavel.{$user->id}." . now()->toDateString();
            $responsavelCacheTtl = now()->addHours(2);

            // Cache only the student IDs, not the full models
            $childIds = Cache::remember(
                "$responsavelCacheKey.child_ids",
                $responsavelCacheTtl,
                fn () => $user->studentsAsResponsavel()
                    ->orderBy('name')
                    ->pluck('id')
                    ->toArray()
            );

            // Fetch children fresh with relations
            $children = $user->studentsAsResponsavel()
                ->with(['team.teacher', 'attendances' => function ($q) {
                    $q->orderBy('date', 'desc')->limit(30);
                }])
                ->with(['payments' => function ($q) use ($year, $month) {
                    $q->where('year', $year)->where('month', $month);
                }])
                ->whereIn('id', $childIds)
                ->orderBy('name')
                ->get();

            $monthStart = $now->copy()->startOfMonth();
            $monthEnd = $now->copy()->endOfMonth();

            $childIds = $children->pluck('id');
            $attendanceStatsByStudent = $childIds->isEmpty()
                ? collect()
                : Cache::remember(
                    "$responsavelCacheKey.attendance_stats",
                    $responsavelCacheTtl,
                    fn () => Attendance::query()
                        ->whereIn('student_id', $childIds)
                        ->whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])
                        ->selectRaw('student_id, COUNT(*) as total, SUM(CASE WHEN present = 1 THEN 1 ELSE 0 END) as present, SUM(CASE WHEN present = 0 THEN 1 ELSE 0 END) as absent, MAX(date) as last_date')
                        ->groupBy('student_id')
                        ->get()
                        ->mapWithKeys(function ($row) {
                            return [
                                (int) $row->student_id => [
                                    'total' => (int) ($row->total ?? 0),
                                    'present' => (int) ($row->present ?? 0),
                                    'absent' => (int) ($row->absent ?? 0),
                                    'last_date' => $row->last_date ? Carbon::parse($row->last_date) : null,
                                ],
                            ];
                        })
                );

            $billingByStudent = $children->mapWithKeys(function (Student $student) use ($year, $month, $today) {
                $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
                $dueDay = min((int) $student->due_day, $daysInMonth);
                $dueDate = Carbon::create($year, $month, $dueDay)->toDateString();

                $payment = $student->payments->first();
                $isPaid = (bool) ($payment && $payment->paid_at);

                $status = 'pendente';
                if ($isPaid) {
                    $status = 'pago';
                } elseif ($dueDate < $today) {
                    $status = 'atrasado';
                }

                return [
                    $student->id => [
                        'status' => $status,
                        'due_date' => Carbon::parse($dueDate),
                        'amount' => $student->fee,
                        'paid_at' => $isPaid ? $payment->paid_at : null,
                        'method' => $payment?->method,
                    ],
                ];
            });

            $paymentsCounts = [
                'pagos' => $billingByStudent->where('status', 'pago')->count(),
                'pendentes' => $billingByStudent->where('status', 'pendente')->count(),
                'atrasados' => $billingByStudent->where('status', 'atrasado')->count(),
            ];

            $attendanceTotals = $attendanceStatsByStudent->reduce(function (array $carry, array $row) {
                $carry['total'] += $row['total'] ?? 0;
                $carry['present'] += $row['present'] ?? 0;

                return $carry;
            }, ['total' => 0, 'present' => 0]);

            $overallAttendanceRate = $attendanceTotals['total'] > 0
                ? round(($attendanceTotals['present'] / $attendanceTotals['total']) * 100, 1)
                : 0.0;

            return view('dashboard', [
                'mode' => 'responsavel',
                'children' => $children,
                'attendanceStatsByStudent' => $attendanceStatsByStudent,
                'billingByStudent' => $billingByStudent,
                'paymentsCounts' => $paymentsCounts,
                'overallAttendanceRate' => $overallAttendanceRate,
                'notices' => $notices,
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
            'notices' => $notices,
        ]);
    }
}
