<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (! $user->isAdmin()) {
            abort(403);
        }

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
        $user = $request->user();
        if (! $user->isAdmin()) {
            abort(403);
        }

        $now = now();
        $year = $now->year;
        $month = $now->month;

        Payment::updateOrCreate(
            ['student_id' => $student->id, 'year' => $year, 'month' => $month],
            [
                'amount' => $student->fee,
                'paid_at' => $now,
                'method' => $request->string('method')->toString() ?: null,
                'obs' => $request->string('obs')->toString() ?: null,
            ],
        );

        return redirect()
            ->route('financeiro.index')
            ->with('success', 'Mensalidade marcada como paga.');
    }
}

