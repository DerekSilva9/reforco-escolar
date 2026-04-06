<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Services\FinanceReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class FinanceController extends Controller
{
    protected FinanceReportService $reportService;

    public function __construct(FinanceReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function executive(Request $request)
    {
        $user = $request->user();
        $this->authorize('finance-view');

        $now = now();
        $year = (int) $request->input('year', $now->year);
        $month = (int) $request->input('month', $now->month);

        // Validate year and month
        if ($month < 1 || $month > 12 || $year < 2020 || $year > 2050) {
            $year = $now->year;
            $month = $now->month;
        }

        $cacheKey = "finance_kpis_{$year}_{$month}";

        // KPIs cached for 1 hour
        $kpis = Cache::remember($cacheKey, 3600, fn () => $this->reportService->getKPIs($year, $month));

        // Delinquents NOT cached (always fresh)
        $delinquents = $this->reportService->getDelinquents();

        // Occupancy by team cached for 1 hour
        $occupancyByTeam = Cache::remember("finance_occupancy_{$year}_{$month}", 3600, 
            fn () => $this->reportService->getOccupancyByTeam()
        );

        // Revenue trend cached for 1 hour
        $revenueTrend = Cache::remember('finance_trend', 3600, 
            fn () => $this->reportService->getRevenueTrend()
        );

        // Available months for dropdown (last 12 months)
        $availableMonths = collect(range(11, 0))->map(fn ($i) => $now->clone()->subMonths($i))->toArray();

        return view('finance.executive', [
            'kpis' => $kpis,
            'delinquents' => $delinquents,
            'occupancy_by_team' => $occupancyByTeam,
            'revenue_trend' => $revenueTrend,
            'year' => $year,
            'month' => $month,
            'available_months' => $availableMonths,
            'month_name' => $this->getMonthName($month),
        ]);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $this->authorize('finance-view');

        $status = $request->string('status')->toString();
        if (! in_array($status, ['pendentes', 'pagos', 'atrasados'], true)) {
            $status = 'pendentes';
        }

        $now = now();
        $year = $now->year;
        $month = $now->month;
        $today = $now->toDateString();

        $students = Student::query()
            ->where('active', true)
            ->with('team')
            ->with(['payments' => fn ($q) => $q->where('year', $year)->where('month', $month)->whereNotNull('paid_at')])
            ->orderBy('name')
            ->get();

        $rows = $students->map(function (Student $student) use ($year, $month, $today) {
            $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
            $dueDay = min((int) $student->due_day, $daysInMonth);
            $dueDate = Carbon::create($year, $month, $dueDay)->toDateString();

            $payment = $student->payments->first();
            $isPaid = (bool) $payment;

            $computedStatus = 'pendente';
            if ($isPaid) {
                $computedStatus = 'pago';
            } elseif ($dueDate < $today) {
                $computedStatus = 'atrasado';
            }

            return [
                'student' => $student,
                'due_date' => $dueDate,
                'payment' => $payment,
                'status' => $computedStatus,
                'amount' => $student->fee,
            ];
        });

        $counts = [
            'pagos' => $rows->where('status', 'pago')->count(),
            'pendentes' => $rows->where('status', 'pendente')->count(),
            'atrasados' => $rows->where('status', 'atrasado')->count(),
        ];

        $rows = $rows->when($status === 'pagos', fn ($c) => $c->where('status', 'pago'))
            ->when($status === 'pendentes', fn ($c) => $c->where('status', 'pendente'))
            ->when($status === 'atrasados', fn ($c) => $c->where('status', 'atrasado'))
            ->values();

        return view('finance.index', [
            'status' => $status,
            'year' => $year,
            'month' => $month,
            'counts' => $counts,
            'rows' => $rows,
        ]);
    }

    public function pay(Request $request, Student $student)
    {
        $this->authorize('finance-create');

        $validated = $request->validate([
            'method' => ['nullable', 'string', 'in:dinheiro,cartao,pix,boleto,transferencia'],
            'obs' => ['nullable', 'string', 'max:500'],
        ]);

        $now = now();
        $year = $now->year;
        $month = $now->month;

        Payment::updateOrCreate(
            ['student_id' => $student->id, 'year' => $year, 'month' => $month],
            [
                'amount' => $student->fee,
                'paid_at' => $now,
                'method' => $validated['method'] ?? null,
                'obs' => $validated['obs'] ?? null,
            ],
        );

        return redirect()
            ->route('financeiro.index')
            ->with('success', 'Mensalidade marcada como paga.');
    }

    /**
     * Helper: get month name in Portuguese
     */
    private function getMonthName(int $month): string
    {
        $months = [
            'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
            'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro',
        ];

        return $months[$month - 1] ?? 'Mês';
    }
}

