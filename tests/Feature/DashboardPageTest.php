<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DashboardPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function test_authenticated_user_can_see_dashboard()
    {
        $user = User::factory()->create([
            'password' => Hash::make($password = 'password123'),
        ]);

        Hash::check($password, $user->password);

        $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function test_guest_cannot_see_dashboard()
    {

        $response = $this->get('/dashboard');

        $response->assertRedirect('/');
    }
}
