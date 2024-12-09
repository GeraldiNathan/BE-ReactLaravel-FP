<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class LoginUnitTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_error_when_password_is_incorrect()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('correct_password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong_password'
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'Error' => "Password doesn't match with email!"
            ]);
    }

    /** @test */
    public function it_returns_success_when_email_and_password_are_correct()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('correct_password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'correct_password'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'Success' => "Login Successful"
            ]);
    }
}
