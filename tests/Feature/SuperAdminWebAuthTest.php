<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\SuperAdmin;
use Illuminate\Support\Facades\Hash;

class SuperAdminWebAuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function superadmin_can_view_login_page()
    {
        $response = $this->get(route('superadmin.login'));
        $response->assertStatus(200);
        $response->assertSee('Login to');
    }

    /** @test */
    public function superadmin_can_login_with_correct_credentials()
    {
        $superadmin = SuperAdmin::create([
            'username' => 'adminweb',
            'email' => 'admin@tuno.test',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post(route('superadmin.login.post'), [
            'login' => 'adminweb',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('superadmin.dashboard'));
        $this->assertAuthenticatedAs($superadmin, 'internal_web');
    }

    /** @test */
    public function superadmin_cannot_login_with_wrong_password()
    {
        SuperAdmin::create([
            'username' => 'adminweb',
            'email' => 'admin@tuno.test',
            'password' => Hash::make('correct-password'),
        ]);

        $response = $this->from(route('superadmin.login'))->post(route('superadmin.login.post'), [
            'login' => 'adminweb',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('superadmin.login'));
        $response->assertSessionHasErrors('login');
        $this->assertGuest('internal_web');
    }
}
