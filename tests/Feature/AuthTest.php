<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_protected_route_requires_token(): void
    {
        $this->getJson('/api/v1/appointments')->assertStatus(401);
    }

    public function test_login_returns_token_and_user(): void
    {
        User::factory()->asClient()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('secret123'),
        ]);

        $this->postJson('/api/v1/login', [
            'email' => 'user@example.com',
            'password' => 'secret123',
        ])->assertOk()->assertJsonStructure(['token', 'user']);
    }
}
