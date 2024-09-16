<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function test_user_can_register()
    {

        $response = $this->post('/api/register', [
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'john@doe.com',
        ]);

        $response->assertStatus(201);
    }

    /**
     * @test
     * @dataProvider providesInvalidRegistrationData
     */
    public function test_user_cannot_register_with_invalid_data(string $name, string $email, string $password, string $password_confirmation)
    {

        $response = $this->post('/api/register', [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $password_confirmation
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => 'john@doe.com',
        ]);

    }

    public function providesInvalidRegistrationData()
    {
        return [

          'no name' => [
              'name' => '',
              'email' => 'john@doe.com',
              'password' => 'password',
              'password_confirmation' => 'password'
          ],
          'invalid email format' => [
              'name' => 'John Doe',
              'email' => 'tacopizza',
              'password' => 'password',
              'password_confirmation' => 'password'
          ],
            'short password' => [
                'name' => 'John Doe',
                'email' => 'john@doe.com',
                'password' => 'pa',
                'password_confirmation' => 'pa'
            ],
            'passwords do not match' => [
                'name' => 'John Doe',
                'email' => 'john@doe.com',
                'password' => 'password123',
                'password_confirmation' => 'tacopizza321'
            ]
        ];
    }

    /**
     * @test
     */
    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'password' => Hash::make($password = 'password123'),
        ]);

        Hash::check($password, $user->password);

        $this->post('/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test
     */
    public function test_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'password' => Hash::make($password = 'password123'),
        ]);

        $incorrectPassword = Hash::make('tacopizza');

        $response = $this->json('POST', '/api/login', [
            'email' => $user->email,
            'password' => $incorrectPassword
        ]);

        $response->assertStatus(422);
        $this->assertGuest();
    }

    /**
     * @test
     */
    public function test_user_can_logout()
    {
        $user = User::factory()->create([
            'password' => Hash::make($password = 'password123'),
        ]);

        $response = $this->json('POST', '/api/login', [
            'email' => $user->email,
            'password' => $password
        ]);

        $this->assertAuthenticatedAs($user);

        $response = $this->json('POST', '/api/logout');
        $this->assertGuest();
    }
}
