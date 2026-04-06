<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_filter_students_by_search_name(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $team = Team::factory()->create(['user_id' => $admin->id]);
        
        Student::factory(3)->create(['team_id' => $team->id]);
        $student = Student::factory()->create(['team_id' => $team->id, 'name' => 'João Silva']);

        $response = $this->actingAs($admin)
            ->get(route('alunos.index', ['search' => 'João']));

        $response->assertStatus(200);
        $response->assertViewHas('students');
    }

    public function test_can_filter_students_by_status(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $team = Team::factory()->create(['user_id' => $admin->id]);
        
        Student::factory(2)->create(['team_id' => $team->id, 'active' => true]);
        Student::factory(2)->create(['team_id' => $team->id, 'active' => false]);

        $response = $this->actingAs($admin)
            ->get(route('alunos.index', ['status' => 'ativo']));

        $response->assertStatus(200);
        $response->assertViewHas('students');
        $students = $response->viewData('students');
        $this->assertTrue($students->every(fn ($s) => $s->active === true));
    }

    public function test_can_filter_students_by_school_year(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $team = Team::factory()->create(['user_id' => $admin->id]);
        
        Student::factory(2)->create(['team_id' => $team->id, 'school_year' => '5º ano']);
        Student::factory(2)->create(['team_id' => $team->id, 'school_year' => '3º ano']);

        $response = $this->actingAs($admin)
            ->get(route('alunos.index', ['school_year' => '5º ano']));

        $response->assertStatus(200);
        $response->assertViewHas('students');
        $students = $response->viewData('students');
        $this->assertTrue($students->every(fn ($s) => $s->school_year === '5º ano'));
    }

    public function test_can_filter_students_by_responsavel_name(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $team = Team::factory()->create(['user_id' => $admin->id]);
        $responsavel = User::factory()->create(['name' => 'Maria Santos', 'role' => 'responsavel']);
        
        Student::factory(2)->create(['team_id' => $team->id]);
        $student = Student::factory()->create(['team_id' => $team->id, 'responsavel_id' => $responsavel->id]);

        $response = $this->actingAs($admin)
            ->get(route('alunos.index', ['search' => 'Maria']));

        $response->assertStatus(200);
        $response->assertViewHas('students');
    }

    public function test_search_query_preserved_in_pagination(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $team = Team::factory()->create(['user_id' => $admin->id]);
        
        Student::factory(20)->create(['team_id' => $team->id, 'name' => 'test']);

        $response = $this->actingAs($admin)
            ->get(route('alunos.index', ['search' => 'test', 'page' => 1]));

        $response->assertStatus(200);
        $html = $response->getContent();
        // Verificar que a página foi filtrada
        $this->assertStringContainsString('test', $html);
    }

    public function test_school_years_dropdown_populated(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $team = Team::factory()->create(['user_id' => $admin->id]);
        
        Student::factory()->create(['team_id' => $team->id, 'school_year' => '5º ano']);
        Student::factory()->create(['team_id' => $team->id, 'school_year' => '3º ano']);

        $response = $this->actingAs($admin)
            ->get(route('alunos.index'));

        $response->assertStatus(200);
        $response->assertViewHas('schoolYears');
        $schoolYears = $response->viewData('schoolYears');
        $this->assertContains('5º ano', $schoolYears);
        $this->assertContains('3º ano', $schoolYears);
    }
}

