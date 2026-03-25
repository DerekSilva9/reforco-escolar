<?php

namespace Tests\Feature;

use App\Models\Notice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class NoticesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_publish_notice_and_it_appears_on_dashboard(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 3, 24, 12, 0, 0));

        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $responsavel = User::factory()->create([
            'role' => User::ROLE_RESPONSAVEL,
        ]);

        $this->actingAs($admin)->post(route('admin.notices.store', absolute: false), [
            'title' => 'Sem aula amanhã',
            'body' => 'A escola estará fechada para manutenção.',
            'pinned' => '1',
            'publish_now' => '1',
        ])->assertRedirect(route('admin.notices.index', absolute: false));

        $this->assertDatabaseHas('notices', [
            'title' => 'Sem aula amanhã',
            'pinned' => 1,
            'created_by' => $admin->id,
        ]);

        $response = $this->actingAs($responsavel)->get(route('dashboard', absolute: false));
        $response->assertStatus(200);
        $response->assertSee('Mural de avisos');
        $response->assertSee('Sem aula amanhã');
    }

    public function test_non_admin_cannot_access_notices_admin_page(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_PROFESSOR,
        ]);

        $this->actingAs($user)->get(route('admin.notices.index', absolute: false))->assertStatus(403);
    }

    public function test_only_visible_notices_show_on_dashboard(): void
    {
        Carbon::setTestNow(Carbon::create(2026, 3, 24, 12, 0, 0));

        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $responsavel = User::factory()->create([
            'role' => User::ROLE_RESPONSAVEL,
        ]);

        Notice::create([
            'created_by' => $admin->id,
            'title' => 'Visível',
            'body' => 'Este recado deve aparecer.',
            'pinned' => false,
            'published_at' => now(),
        ]);

        Notice::create([
            'created_by' => $admin->id,
            'title' => 'Expirado',
            'body' => 'Este recado não deve aparecer.',
            'pinned' => false,
            'published_at' => now(),
            'ends_at' => now()->copy()->subMinute(),
        ]);

        $response = $this->actingAs($responsavel)->get(route('dashboard', absolute: false));
        $response->assertStatus(200);
        $response->assertSee('Visível');
        $response->assertDontSee('Expirado');
    }
}

