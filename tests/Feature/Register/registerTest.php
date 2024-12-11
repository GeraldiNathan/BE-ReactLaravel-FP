<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function register_valid()
    {
        $user = User::factory()->create([
            'name' => 'geraldi nathan',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('correct_password'),
        ]);

        $response = $this->postJson('/api/register', [
            'name' => "nathan",
            'email' => 'notregistered@gmail.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'User registered successfully'
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'notregistered@gmail.com',
        ]);
    }

    /** @test */
    public function invalid_Register()
    {
        $user = User::factory()->create([
            'name' => 'geraldi nathan',
            'email' => 'test@.com',
            'password' => Hash::make('correct_password'),
        ]);

        $response = $this->postJson('/api/register', [
            'name' => "nathan",
            'email' => 'notregistered@gmail.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'User registered successfully'
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'notregistered@gmail.com',
        ]);
    }
}
