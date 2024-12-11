<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CreateRecipeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_with_valid_data()
    {
        $response = $this->postJson('api/recipe', [
            'title' => 'New Recipe',
            'description' => 'A new recipe description.',
            'file_path' => UploadedFile::fake()->image('recipe.jpg')
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'New recipe has been created!'
            ]);

        $this->assertDatabaseHas('posts', [
            'title' => 'New Recipe',
            'description' => 'A new recipe description.'
        ]);
    }

    /** @test */
    public function create_with_invalid_data()
    {
        $response = $this->postJson('api/recipe', [
            'title' => 'New Recipe',
            'description' => 'A new recipe description.',
            'file_path' => null
        ]);

        $response->assertStatus(400)
            ->assertJsonValidationErrors('file_path');

        // memastikan untuk data tidak disimpan kedalam database
        $this->assertDatabaseMissing('posts', [
            'title' => 'New Recipe',
            'description' => 'A new recipe description.'
        ]);
    }
}
