<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class UpdateRecipeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function update_with_valid_data()
    {
        $post = Post::create([
            'title' => 'Old Recipe',
            'description' => 'Old recipe description.',
            'file_path' => 'old_recipe.jpg'
        ]);

        $response = $this->putJson('api/recipe/' . $post->id, [
            'title' => 'Updated Recipe',
            'description' => 'Updated recipe description.',
            'file_path' => UploadedFile::fake()->image('updated_recipe.jpg')
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 200,
            'message' => 'Recipe has been updated',
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated Recipe',
            'description' => 'Updated recipe description.'
        ]);
    }

    /** @test */
    public function update_with_invalid_data()
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
            'description' => 'update recipe description.'
        ]);
    }
}
