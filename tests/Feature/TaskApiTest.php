<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure Spatie roles exist (guard web)
        $this->artisan('db:seed', ['--class' => \Database\Seeders\SpatieRolesSeeder::class]);


        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'testapi@example.com',
            'password' => Hash::make('password123'),
            'role' => 'Tenaga Teknik'
        ]);

        $this->user->assignRole('Tenaga Teknik');
    }

    /**
     * Test successful login and dual-token generation.
     */
    public function test_user_can_login_and_receive_tokens(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'testapi@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'access_token',
                    'refresh_token',
                    'token_type',
                    'user'
                ]
            ]);
    }

    /**
     * Test access is allowed using a valid access token.
     */
    public function test_access_is_allowed_with_valid_access_token(): void
    {
        $accessToken = $this->user->createToken('access_token', ['access-api'])->plainTextToken;

        $response = $this->getJson('/api/tasks', [
            'Authorization' => 'Bearer ' . $accessToken
        ]);

        $response->assertStatus(200);
    }

    /**
     * Test access is blocked when using a refresh token on core resources.
     */
    public function test_access_is_blocked_with_refresh_token_on_resources(): void
    {
        $refreshToken = $this->user->createToken('refresh_token', ['issue-access-token'])->plainTextToken;

        $response = $this->getJson('/api/tasks', [
            'Authorization' => 'Bearer ' . $refreshToken
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test refreshing access token using refresh token.
     */
    public function test_user_can_refresh_access_token(): void
    {
        $refreshToken = $this->user->createToken('refresh_token', ['issue-access-token'])->plainTextToken;

        $response = $this->postJson('/api/auth/refresh', [], [
            'Authorization' => 'Bearer ' . $refreshToken
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'access_token',
                    'token_type',
                    'expires_in'
                ]
            ]);
    }
}
