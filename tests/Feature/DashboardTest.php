<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_renders_without_cache_errors()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // First call - populates cache (even with no data)
        $response = $this->actingAs($admin)
            ->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewHas('totalStudentsCount');
        $response->assertViewHas('latestPayments');

        // Second call - retrieves from cache (this would fail with serialization error before fix)
        $response2 = $this->actingAs($admin)
            ->get(route('dashboard'));

        $response2->assertStatus(200);
        $response2->assertViewHas('latestPayments');
        
        // Verify cache results are accessible without serialization errors
        $payments = $response2['latestPayments'];
        $this->assertIsIterable($payments);
    }

    public function test_responsavel_dashboard_renders_without_cache_errors()
    {
        $responsavel = User::factory()->create(['role' => 'responsavel']);

        // First call - populates cache (even when user has no children)
        $response = $this->actingAs($responsavel)
            ->get(route('dashboard'));

        $response->assertStatus(200);

        // Second call - retrieves from cache (this would fail with serialization error before fix)
        $response2 = $this->actingAs($responsavel)
            ->get(route('dashboard'));

        $response2->assertStatus(200);
    }
}
