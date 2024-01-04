<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {
        return Post::all();

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
        $data = Post::where('id', $id)->first();
        if (!$data) {
            return response()->json([
                'status' => 404,
                'message' => "Data not found"
            ], 404);
        } else {
            return response()->json([
                'status' => 200,
                'data' => $data
            ], 200);
        }
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

    // public function update(Request $request, int $id)
    // {
    //     // Validate the incoming request
    //     // $validator = Validator::make($request->all(), [
    //     //     'title' => 'required|max:1000',
    //     //     'description' => 'required|max:1000',
    //     //     'file_path' => 'file|mimes:jpeg,png,jpg,gif,svg',
    //     // ]);

    //     // if ($validator->fails()) {
    //     //     return response()->json([
    //     //         'status' => 400,
    //     //         'message' => $validator->messages()
    //     //     ], 400);
    //     // }

    //     // Find the post by ID
    //     $post = Post::find($id);

    //     if (!$post) {
    //         return response()->json([
    //             'status' => 404,
    //             'message' => 'Post not found'
    //         ], 404);
    //     }

    //     // Update post data
    //     $post->title = $request->input('title');
    //     $post->description = $request->input('description');

    //     // Check if a new file is uploaded
    //     if ($request->hasFile('file_path')) {
    //         // Store the new file
    //         $post->file_path = $request->file('file_path')->store('public');
    //     }

    //     // Save the updated post
    //     $post->save();

    //     return response()->json([
    //         'status' => 200,
    //         'message' => 'Post successfully updated',
    //         'data' => $post
    //     ], 200);
    // }

    public function update(Request $request, int $id)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:1000',
            'description' => 'max:1000',
            'file_path' => 'file|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->messages()->first()
            ], 400);
        }

        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'status' => 404,
                'message' => 'Post not found'
            ], 404);
        }

        $post->title = $request->input('title');
        $post->description = $request->input('description');

        if ($request->hasFile('file_path')) {
            $post->file_path = $request->file('file_path')->store('public');
        }

        $post->save();

        return response()->json([
            'status' => 200,
            'message' => 'Post successfully updated',
            'data' => $post
        ], 200);
    }

    public function destroy($id)
    {
        $data = Post::find($id);

        if ($data) {
            $data->delete();

            return  response()->json([
                'status' => 200,
                'message' => 'Data has been deleted'
            ], 200);
        } else {
            return  response()->json([
                'status' => 404,
                'message' => 'Data not found'
            ], 404);
        }
    }

    public function search($key)
    {
        return Post::where('title', 'LIKE', "%$key%")->get();
    }
}
