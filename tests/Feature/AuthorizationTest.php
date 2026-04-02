<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that admin can view students
     */
    public function test_admin_can_view_students(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $student = Student::factory()->create();

        $response = $this->actingAs($admin)->get(route('alunos.show', $student));

        $response->assertStatus(200);
    }

    /**
     * Test that professor can view their own students
     */
    public function test_professor_can_view_own_students(): void
    {
        $professor = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        $team = Team::factory()->create(['user_id' => $professor->id]);
        $student = Student::factory()->create(['team_id' => $team->id]);

        $response = $this->actingAs($professor)->get(route('alunos.show', $student));

        $response->assertStatus(200);
    }

    /**
     * Test that professor cannot view other professor's students
     */
    public function test_professor_cannot_view_other_professors_students(): void
    {
        $professor1 = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        $professor2 = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        
        $team = Team::factory()->create(['user_id' => $professor2->id]);
        $student = Student::factory()->create(['team_id' => $team->id]);

        $response = $this->actingAs($professor1)->get(route('alunos.show', $student));

        $response->assertStatus(403);
    }

    /**
     * Test that responsavel cannot view students
     */
    public function test_responsavel_cannot_view_students(): void
    {
        $responsavel = User::factory()->create(['role' => User::ROLE_RESPONSAVEL]);
        $student = Student::factory()->create();

        $response = $this->actingAs($responsavel)->get(route('alunos.show', $student));

        $response->assertStatus(403);
    }

    /**
     * Test that only admin can export students
     */
    public function test_only_admin_and_professor_can_export_students(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $professor = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        $responsavel = User::factory()->create(['role' => User::ROLE_RESPONSAVEL]);
        
        $team = Team::factory()->create(['user_id' => $professor->id]);
        $response = $this->actingAs($responsavel)->get(route('alunos.export'));
        $response->assertStatus(403);
    }

    /**
     * Test team authorization
     */
    public function test_professor_can_view_own_teams(): void
    {
        $professor = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        $team = Team::factory()->create(['user_id' => $professor->id]);

        $response = $this->actingAs($professor)->get(route('turmas.show', $team));

        $response->assertStatus(200);
    }

    /**
     * Test that professor cannot edit other professor's teams
     */
    public function test_professor_cannot_edit_other_professors_teams(): void
    {
        $professor1 = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        $professor2 = User::factory()->create(['role' => User::ROLE_PROFESSOR]);
        
        $team = Team::factory()->create(['user_id' => $professor2->id]);

        $response = $this->actingAs($professor1)
            ->patch(route('turmas.update', $team), [
                'name' => 'Updated Team',
                'time' => '14:00',
            ]);

        $response->assertStatus(403);
    }
}
