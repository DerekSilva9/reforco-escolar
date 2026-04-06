<?php

namespace Tests\Feature\Authorization;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GatesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_finance_gate()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->assertTrue($admin->can('finance-view'));
    }

    public function test_admin_can_create_finance_gate()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->assertTrue($admin->can('finance-create'));
    }

    public function test_professor_cannot_access_finance_gate()
    {
        $professor = User::factory()->create(['role' => 'professor']);

        $this->assertFalse($professor->can('finance-view'));
    }

    public function test_responsavel_cannot_access_finance_gate()
    {
        $responsavel = User::factory()->create(['role' => 'responsavel']);

        $this->assertFalse($responsavel->can('finance-view'));
    }

    public function test_admin_can_access_attendance_view_gate()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->assertTrue($admin->can('attendance-view'));
    }

    public function test_professor_can_access_attendance_view_gate()
    {
        $professor = User::factory()->create(['role' => 'professor']);

        $this->assertTrue($professor->can('attendance-view'));
    }

    public function test_responsavel_cannot_access_attendance_view_gate()
    {
        $responsavel = User::factory()->create(['role' => 'responsavel']);

        $this->assertFalse($responsavel->can('attendance-view'));
    }
}
