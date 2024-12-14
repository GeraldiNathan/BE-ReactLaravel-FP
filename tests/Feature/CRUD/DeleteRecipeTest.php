<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DeleteRecipeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_delete_post()
    {
        $post = Post::create([
            'title' => 'Recipe to Delete',
            'description' => 'This recipe will be deleted.',
            'file_path' => 'recipe_to_delete.jpg'
        ]);

        // Delete the recipe
        $response = $this->deleteJson('api/recipe/' . $post->id);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Data has been deleted'
            ]);

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id
        ]);
    }

    /** @test */
    public function delete_invalid_data()
    {
        $invalidDataID = 99999;

        // Delete the recipe
        $response = $this->deleteJson('api/recipe/' . $invalidDataID);

        $response->assertStatus(404)
            ->assertJson([
                'status' => 404,
                'message' => 'Data failed to delete'
            ]);
    }
}
