<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class PostController extends Controller
{
    public function index()
    {
        $data = Post::all();
        if ($data->count() > 0) {
            return response()->json([
                'status' => 200,
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "Data empty"
            ], 404);
        }
    }


    public function show($id)
    {
    }

    public function addPost(Request $request)
    {

        $post = new Post();
        $post->title = $request->input('title');
        $post->description = $request->input('description');
        $post->file_path = $request->file('file_path')->store('public');
        $post->save();

        if ($post) {
            return response()->json([
                'status' => 200,
                'message' => "add data successfully done",
                'data' => $post
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "something went wrong"
            ], 404);
        };
    }

    public function edit()
    {
    }

    public function destroy()
    {
    }
}
