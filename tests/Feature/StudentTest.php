<?php

namespace Tests\Feature;

use App\Models\Student;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = User::factory()->create(['role' => 'admin']);
        
        // Create professor and turmas
        $this->professor = User::factory()->create(['role' => 'professor']);
        $this->team = Team::factory()->create(['user_id' => $this->professor->id]);
        
        // Create students
        Student::factory(20)->create(['team_id' => $this->team->id]);
    }

    public function test_student_index_returns_paginated_results()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('alunos.index'));

        $response->assertStatus(200);
        $response->assertViewHas('students');
        
        // Check that students are paginated
        $this->assertEquals(15, $response['students']->count());
    }

    public function test_student_index_has_pagination_links()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('alunos.index'));

        $response->assertViewHas('students');
        $students = $response['students'];
        
        $this->assertTrue($students->hasPages());
    }

    public function test_student_index_loads_common_relations()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('alunos.index'));

        $students = $response['students'];
        
        // Check that team relation is loaded (no N+1)
        $this->assertNotNull($students[0]->team);
    }

    public function test_student_show_returns_student()
    {
        $student = Student::first();

        $response = $this->actingAs($this->admin)
            ->get(route('alunos.show', $student));

        $response->assertStatus(200);
        $response->assertViewHas('student', $student);
    }

    public function test_student_can_be_exported()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('alunos.export'));

        $response->assertStatus(200);
        $response->assertDownload();
    }

    public function test_student_pagination_second_page()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('alunos.index', ['page' => 2]));

        $response->assertStatus(200);
        
        // Second page should have 5 students (20 total - 15 on first page)
        $this->assertEquals(5, $response['students']->count());
    }

    public function test_student_total_count_is_accurate()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('alunos.index'));

        $students = $response['students'];
        
        // Check that total reflects all students
        $this->assertEquals(20, $students->total());
    }
}
