<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

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

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:191',
            'description' => 'required|max:191',
            'file_path' => 'required|file|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 404,
                'message' => $validator->messages()
            ], 404);
        } else {
            $data = Post::find($id);
            if ($data) {
                $data->update([
                    'title' => $request->name,
                    'description' => $request->description,
                    'file_path' => $request->file_path,
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => "Data successfully updated"
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "Data not found"
                ], 404);
            }
        }
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
}
