<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Student;
use Carbon\Carbon;

class FinanceReportService
{
    /**
     * Get KPI metrics for the executive dashboard
     */
    public function getKPIs(int $year, int $month): array
    {
        $monthRevenue = $this->getMonthRevenue($year, $month);
        $monthTarget = $this->getMonthTarget($year, $month);
        $delinquency = $this->getDelinquencyAmount();
        $occupancyRate = $this->getOccupancyRate();
        $newStudents = $this->getNewStudentsCount($year, $month);

        return [
            'revenue' => $monthRevenue,
            'revenue_percentage' => $monthTarget > 0 ? round(($monthRevenue / $monthTarget) * 100, 1) : 0,
            'target' => $monthTarget,
            'delinquency_amount' => $delinquency['amount'],
            'delinquency_count' => $delinquency['count'],
            'occupancy_rate' => $occupancyRate,
            'new_students' => $newStudents,
        ];
    }

    /**
     * Get revenue received in a specific month
     */
    private function getMonthRevenue(int $year, int $month): float
    {
        return Payment::query()
            ->where('year', $year)
            ->where('month', $month)
            ->whereNotNull('paid_at')
            ->sum('amount');
    }

    /**
     * Calculate expected revenue based on active students × their fee
     */
    private function getMonthTarget(int $year, int $month): float
    {
        return Student::query()
            ->where('active', true)
            ->get()
            ->sum('fee');
    }

    /**
     * Get total amount overdue and count of students with arrears
     */
    private function getDelinquencyAmount(): array
    {
        $now = Carbon::now();
        
        $delinquent = Payment::query()
            ->whereNull('paid_at')
            ->where(function ($query) use ($now) {
                $query->where('year', '<', $now->year)
                    ->orWhere(function ($q) use ($now) {
                        $q->where('year', $now->year)
                          ->where('month', '<', $now->month);
                    });
            })
            ->with('student')
            ->get();

        return [
            'amount' => $delinquent->sum('amount'),
            'count' => $delinquent->groupBy('student_id')->count(),
        ];
    }

    /**
     * Calculate occupancy (just active student count, no capacity limit)
     */
    private function getOccupancyRate(): array
    {
        $activeCount = Student::where('active', true)->count();

        return [
            'active' => $activeCount,
        ];
    }

    /**
     * Count new students added in current month
     */
    private function getNewStudentsCount(int $year, int $month): int
    {
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->clone()->endOfMonth();

        return Student::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    /**
     * Get list of delinquent students with payment details
     */
    public function getDelinquents(): array
    {
        $now = Carbon::now();
        
        $delinquents = Payment::query()
            ->whereNull('paid_at')
            ->where(function ($query) use ($now) {
                $query->where('year', '<', $now->year)
                    ->orWhere(function ($q) use ($now) {
                        $q->where('year', $now->year)
                          ->where('month', '<', $now->month);
                    });
            })
            ->with(['student.responsavel'])
            ->get()
            ->map(function ($payment) use ($now) {
                $paymentDate = Carbon::create($payment->year, $payment->month, 1);
                $daysOverdue = $now->diffInDays($paymentDate, absolute: true);

                return [
                    'student_name' => $payment->student->name,
                    'responsavel_name' => $payment->student->responsavel?->name ?? $payment->student->parent_name ?? 'N/A',
                    'responsavel_phone' => $payment->student->responsavel?->phone ?? $payment->student->phone ?? null,
                    'month' => $payment->month,
                    'year' => $payment->year,
                    'amount' => $payment->amount,
                    'days_overdue' => $daysOverdue,
                    'month_display' => $this->getMonthName($payment->month),
                    'period' => $this->getMonthName($payment->month) . ' de ' . $payment->year,
                ];
            })
            // Sort by days overdue (descending) in PHP to work with all databases
            ->sortByDesc('days_overdue')
            ->values()
            ->toArray();

        return $delinquents;
    }

    /**
     * Get occupancy breakdown by team
     */
    public function getOccupancyByTeam(): array
    {
        return Student::query()
            ->where('active', true)
            ->with('team')
            ->get()
            ->groupBy('team_id')
            ->map(function ($students, $teamId) {
                $team = $students->first()->team;
                $totalRevenue = $students->sum('fee');

                return [
                    'team_name' => $team?->name ?? 'Sem Turma',
                    'team_time' => $team?->time ?? '-',
                    'occupied' => $students->count(),
                    'revenue' => $totalRevenue,
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Get month revenue trend (last 6 months)
     */
    public function getRevenueTrend(): array
    {
        $months = [];
        $today = Carbon::now();

        for ($i = 5; $i >= 0; $i--) {
            $date = $today->clone()->subMonths($i);
            $revenue = $this->getMonthRevenue($date->year, $date->month);
            $target = $this->getMonthTarget($date->year, $date->month);

            $months[] = [
                'month' => $date->format('M'),
                'full_month' => $this->getMonthName($date->month),
                'year' => $date->year,
                'revenue' => $revenue,
                'target' => $target,
            ];
        }

        return $months;
    }

    /**
     * Helper: get month name in Portuguese
     */
    private function getMonthName(int $month): string
    {
        $months = [
            'Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun',
            'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez',
        ];

        return $months[$month - 1] ?? 'Mês';
    }
}
