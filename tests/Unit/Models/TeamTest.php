<?php

namespace Tests\Unit\Models;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamTest extends TestCase
{
    use RefreshDatabase;

    public function test_with_teacher_loads_user()
    {
        $professor = User::factory()->create(['role' => 'professor']);
        $team = Team::factory()->create(['user_id' => $professor->id]);

        $teamWithTeacher = Team::query()
            ->withTeacher()
            ->first();

        // Check that teacher is loaded
        $this->assertNotNull($teamWithTeacher->teacher);
        $this->assertEquals($professor->id, $teamWithTeacher->teacher->id);
    }

    public function test_with_teacher_selects_specific_columns()
    {
        $professor = User::factory()->create(['role' => 'professor']);
        Team::factory()->create(['user_id' => $professor->id]);

        $teamWithTeacher = Team::query()
            ->withTeacher()
            ->first();

        // Check that teacher loaded with correct columns
        $teacherColumns = array_keys($teamWithTeacher->teacher->getAttributes());
        $this->assertContains('id', $teacherColumns);
        $this->assertContains('name', $teacherColumns);
        $this->assertContains('email', $teacherColumns);
    }

    public function test_team_belongs_to_user()
    {
        $professor = User::factory()->create(['role' => 'professor']);
        $team = Team::factory()->create(['user_id' => $professor->id]);

        $this->assertEquals($professor->id, $team->teacher->id);
    }

    public function test_team_has_many_students()
    {
        $team = Team::factory()->create();
        $studentsCount = 5;
        
        for ($i = 0; $i < $studentsCount; $i++) {
            \App\Models\Student::factory()->create(['team_id' => $team->id]);
        }

        $this->assertEquals($studentsCount, $team->students()->count());
    }
}
