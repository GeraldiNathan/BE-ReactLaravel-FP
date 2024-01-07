<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

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

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:191',
            'description' => 'required|max:191',
            'file_path' => 'sometimes|file|mimes:jpeg,png,jpg,gif,svg', // Use 'sometimes' to allow update without file
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => $validator->messages()
            ], 400);
        }

        $data = Post::find($id);

        if (!$data) {
            return response()->json([
                'status' => 404,
                'message' => "Data not found"
            ], 404);
        }

   
        $data->title = $request->input("title");
        $data->description = $request->input("description");
        

        // Check if file_path is present in the request
        if ($request->hasFile('file_path')) {
            // Old image delete
            // Assuming you are storing the file path in the 'file_path' column
            if (Storage::disk('public')->exists($data->file_path)) {
                Storage::disk('public')->delete($data->file_path);
            }

            // Save the new file
            $file_path = $request->file('file_path')->store('public');

            // Update the file path in the database
            $data->update([
                'file_path' => $file_path,
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => "Data successfully updated"
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
}
