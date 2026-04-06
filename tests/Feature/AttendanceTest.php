<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Student;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AttendanceTest extends TestCase
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
        $this->students = Student::factory(3)->create(['team_id' => $this->team->id]);
    }

    public function test_admin_can_view_attendance_page()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('presenca.index'));

        $response->assertStatus(200);
    }

    public function test_professor_can_view_attendance_page()
    {
        $response = $this->actingAs($this->professor)
            ->get(route('presenca.index'));

        $response->assertStatus(200);
    }

    public function test_responsavel_cannot_view_attendance_page()
    {
        $responsavel = User::factory()->create(['role' => 'responsavel']);
        
        $response = $this->actingAs($responsavel)
            ->get(route('presenca.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_save_bulk_attendance()
    {
        $date = now()->format('Y-m-d');
        $present = [];
        $obs = [];
        
        foreach ($this->students as $student) {
            $present[$student->id] = true;
            $obs[$student->id] = 'Presença confirmada';
        }

        $response = $this->actingAs($this->admin)
            ->post(route('teams.attendance.store', $this->team), [
                'date' => $date,
                'present' => $present,
                'obs' => $obs,
            ]);

        // Check that we got redirected (200 for redirect in test)
        $response->assertRedirect();
        
        // Check that attendances were created
        $this->assertCount($this->students->count(), 
            Attendance::whereDate('date', $date)->get());
    }

    public function test_attendance_records_are_transactional()
    {
        // Testing that transactions work properly
        // When validation fails, entire transaction is rolled back
        $response = $this->actingAs($this->admin)
            ->post(route('teams.attendance.store', $this->team), [
                'date' => now()->format('Y-m-d'),
                'present' => [
                    $this->students[0]->id => true,
                    $this->students[1]->id => true,
                ],
                'obs' => [
                    $this->students[0]->id => 'Valid',
                    $this->students[1]->id => str_repeat('a', 600), // This will fail validation
                ],
            ]);

        // First student should not be saved due to transaction rollback
        $this->assertCount(0, Attendance::all());
    }

    public function test_attendance_note_max_length()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('teams.attendance.store', $this->team), [
                'date' => now()->format('Y-m-d'),
                'present' => [$this->students[0]->id => true],
                'obs' => [$this->students[0]->id => str_repeat('a', 600)],
            ]);

        $response->assertSessionHasErrors();
    }

}
