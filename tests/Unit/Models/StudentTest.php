<?php

namespace Tests\Unit\Models;

use App\Models\Student;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    public function test_with_common_relations_loads_team()
    {
        $professor = User::factory()->create(['role' => 'professor']);
        $team = Team::factory()->create(['user_id' => $professor->id]);
        $student = Student::factory()->create(['team_id' => $team->id]);

        $studentWithRelations = Student::query()
            ->withCommonRelations()
            ->first();

        // Check that team is loaded
        $this->assertNotNull($studentWithRelations->team);
        $this->assertEquals($team->id, $studentWithRelations->team->id);
    }

    public function test_with_common_relations_loads_responsavel()
    {
        $professor = User::factory()->create(['role' => 'professor']);
        $team = Team::factory()->create(['user_id' => $professor->id]);
        $responsavel = User::factory()->create(['role' => 'responsavel']);
        $student = Student::factory()->create([
            'team_id' => $team->id,
            'responsavel_id' => $responsavel->id,
        ]);

        $studentWithRelations = Student::query()
            ->withCommonRelations()
            ->first();

        // Check that responsavel is loaded
        $this->assertNotNull($studentWithRelations->responsavel);
        $this->assertEquals($responsavel->id, $studentWithRelations->responsavel->id);
    }

    public function test_with_common_relations_selects_specific_columns()
    {
        $professor = User::factory()->create(['role' => 'professor']);
        $team = Team::factory()->create(['user_id' => $professor->id]);
        $student = Student::factory()->create(['team_id' => $team->id]);

        $studentWithRelations = Student::query()
            ->withCommonRelations()
            ->first();

        // Check that team loaded with correct columns
        $teamColumns = array_keys($studentWithRelations->team->getAttributes());
        $this->assertContains('id', $teamColumns);
        $this->assertContains('name', $teamColumns);
    }

    public function test_student_belongs_to_team()
    {
        $team = Team::factory()->create();
        $student = Student::factory()->create(['team_id' => $team->id]);

        $this->assertEquals($team->id, $student->team->id);
    }

    public function test_student_belongs_to_responsavel()
    {
        $responsavel = User::factory()->create(['role' => 'responsavel']);
        $student = Student::factory()->create(['responsavel_id' => $responsavel->id]);

        $this->assertEquals($responsavel->id, $student->responsavel->id);
    }
}
