<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinanceExecutiveTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_executive_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $team = Team::factory()->create(['user_id' => $admin->id]);
        Student::factory(5)->create(['team_id' => $team->id]);

        $response = $this->actingAs($admin)
            ->get(route('financeiro.executive'));

        $response->assertStatus(200);
        $response->assertViewHas(['kpis', 'delinquents', 'occupancy_by_team', 'revenue_trend']);
    }

    public function test_professor_cannot_access_executive_dashboard(): void
    {
        $professor = User::factory()->create(['role' => 'professor']);

        $response = $this->actingAs($professor)
            ->get(route('financeiro.executive'));

        $response->assertStatus(403);
    }

    public function test_kpis_calculated_correctly(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $team = Team::factory()->create(['user_id' => $admin->id]);
        $student = Student::factory()->create(['team_id' => $team->id, 'fee' => 500.00]);

        // Create a paid payment
        Payment::create([
            'student_id' => $student->id,
            'year' => now()->year,
            'month' => now()->month,
            'amount' => 500.00,
            'paid_at' => now(),
        ]);

        $response = $this->actingAs($admin)
            ->get(route('financeiro.executive'));

        $response->assertStatus(200);
        $kpis = $response->viewData('kpis');
        
        $this->assertEquals(500.00, $kpis['revenue']);
        $this->assertEquals(500.00, $kpis['target']);
        $this->assertEquals(100, $kpis['revenue_percentage']);
    }

    public function test_delinquents_are_listed(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $team = Team::factory()->create(['user_id' => $admin->id]);
        $responsavel = User::factory()->create(['role' => 'responsavel', 'phone' => '84999999999']);
        $student = Student::factory()->create([
            'team_id' => $team->id,
            'responsavel_id' => $responsavel->id,
            'fee' => 300.00,
        ]);

        // Create unpaid payment (last month)
        $lastMonth = now()->subMonth();
        Payment::create([
            'student_id' => $student->id,
            'year' => $lastMonth->year,
            'month' => $lastMonth->month,
            'amount' => 300.00,
            'paid_at' => null,
        ]);

        $response = $this->actingAs($admin)
            ->get(route('financeiro.executive'));

        $response->assertStatus(200);
        $delinquents = $response->viewData('delinquents');
        
        $this->assertCount(1, $delinquents);
        $this->assertEquals($student->name, $delinquents[0]['student_name']);
        $this->assertEquals(300.00, $delinquents[0]['amount']);
    }

    public function test_occupancy_rate_calculated(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $team = Team::factory()->create(['user_id' => $admin->id]);
        
        // Create 8 active students
        Student::factory(8)->create(['team_id' => $team->id, 'active' => true]);

        $response = $this->actingAs($admin)
            ->get(route('financeiro.executive'));

        $response->assertStatus(200);
        $kpis = $response->viewData('kpis');
        
        $this->assertEquals(8, $kpis['occupancy_rate']['active']);
    }

    public function test_filter_by_month(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $team = Team::factory()->create(['user_id' => $admin->id]);
        Student::factory(3)->create(['team_id' => $team->id]);

        $response = $this->actingAs($admin)
            ->get(route('financeiro.executive', ['month' => 6, 'year' => 2026]));

        $response->assertStatus(200);
        $this->assertEquals(6, $response->viewData('month'));
    }
}
