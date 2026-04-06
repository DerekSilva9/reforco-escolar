<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\Student;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = User::factory()->create(['role' => 'admin']);
        
        // Create professor and turma
        $this->professor = User::factory()->create(['role' => 'professor']);
        $this->team = Team::factory()->create(['user_id' => $this->professor->id]);
        
        // Create students
        $this->students = Student::factory(3)->create(['team_id' => $this->team->id, 'fee' => 100.00]);
    }

    public function test_admin_can_view_finance_page()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('financeiro.index'));

        $response->assertStatus(200);
        $response->assertViewHas('rows');
    }

    public function test_non_admin_cannot_view_finance_page()
    {
        $responsavel = User::factory()->create(['role' => 'responsavel']);
        
        $response = $this->actingAs($responsavel)
            ->get(route('financeiro.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_mark_payment_as_paid()
    {
        $student = $this->students->first();
        $now = now();

        $response = $this->actingAs($this->admin)
            ->post(route('financeiro.pay', $student), [
                'method' => 'pix',
                'obs' => 'Pagamento via PIX',
            ]);

        $this->assertDatabaseHas('payments', [
            'student_id' => $student->id,
            'year' => $now->year,
            'month' => $now->month,
            'method' => 'pix',
        ]);

        $response->assertRedirect(route('financeiro.index'));
    }

    public function test_payment_method_validation()
    {
        $student = $this->students->first();

        $response = $this->actingAs($this->admin)
            ->post(route('financeiro.pay', $student), [
                'method' => 'invalid_method',
                'obs' => 'Test',
            ]);

        $response->assertSessionHasErrors('method');
    }

    public function test_payment_obs_max_length()
    {
        $student = $this->students->first();
        $longObs = str_repeat('a', 600);

        $response = $this->actingAs($this->admin)
            ->post(route('financeiro.pay', $student), [
                'method' => 'dinheiro',
                'obs' => $longObs,
            ]);

        $response->assertSessionHasErrors('obs');
    }
}
