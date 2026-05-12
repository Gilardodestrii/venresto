<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class TenantPathLoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_tenant_login_page_via_path_based_url()
    {
        Tenant::factory()->create(['slug' => 'warung-aji']);

        $resp = $this->get('/warung-aji/login');
        $resp->assertStatus(200)->assertSee('Masuk');
    }

    /** @test */
    public function it_can_login_tenant_user_via_path_based_url()
    {
        $tenant = Tenant::factory()->create(['slug' => 'warung-aji']);
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'email'     => 'owner@aji.test',
            'password'  => Hash::make('rahasia123'),
        ]);

        $resp = $this->post('/warung-aji/login', [
            'email' => 'owner@aji.test',
            'password' => 'rahasia123',
        ]);

        $resp->assertRedirect(); // intended redirect
        $this->assertAuthenticatedAs($user);
    }
}
