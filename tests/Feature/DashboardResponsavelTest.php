<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DashboardResponsavelTest extends TestCase
{
    use RefreshDatabase;

    public function test_responsavel_dashboard_renders_with_billing_and_attendance_summary(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 3, 24, 12, 0, 0));

        $teacher = User::factory()->create([
            'role' => User::ROLE_PROFESSOR,
            'phone' => '(11) 99999-9999',
        ]);

        $responsavel = User::factory()->create([
            'role' => User::ROLE_RESPONSAVEL,
        ]);

        $team = Team::query()->create([
            'name' => '5º Ano B',
            'time' => 'Manhã',
            'user_id' => $teacher->id,
        ]);

        $student = Student::factory()->create([
            'team_id' => $team->id,
            'responsavel_id' => $responsavel->id,
            'fee' => 200.00,
            'due_day' => 1,
        ]);

        Attendance::query()->create([
            'student_id' => $student->id,
            'date' => Carbon::now()->toDateString(),
            'present' => true,
            'obs' => 'Boa participação.',
        ]);

        Attendance::query()->create([
            'student_id' => $student->id,
            'date' => Carbon::now()->copy()->subDay()->toDateString(),
            'present' => false,
            'obs' => null,
        ]);

        Payment::query()->create([
            'student_id' => $student->id,
            'year' => 2026,
            'month' => 3,
            'amount' => 200.00,
            'paid_at' => null,
            'method' => null,
            'obs' => null,
        ]);

        $response = $this->actingAs($responsavel)->get(route('dashboard', absolute: false));

        $response->assertStatus(200);
        $response->assertSee($student->name);
        $response->assertSee('Mensalidade');
        $response->assertSee('Desempenho');
        $response->assertSee('Observações');
        $response->assertSee('Presença no mês');
        $response->assertSee($teacher->name);
    }
}
