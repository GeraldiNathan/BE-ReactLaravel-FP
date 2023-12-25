<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index(){
        return Post::all();
    }

    public function addPost(Request $request){
        
        $post = new Post();
        $post->title = $request->input('title');
        $post->description = $request->input('description');
        $post->file_path = $request->file('file_path')->store('public');
        $post->save();

        return $post;

    }
}
