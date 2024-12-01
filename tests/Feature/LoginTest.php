<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_return_error_if_email_is_not_registered()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'notregistered@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(401)    
            ->assertJson([
                'Error' => 'Email doesnt match with password'
            ]);
    }

    /** @test */
    public function it_should_login_successfully()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('correct_password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'correct_password',
        ]);

        $response->assertStatus(202)->assertJson([
            'Success' => 'Login Success'
        ]);
    }
}
