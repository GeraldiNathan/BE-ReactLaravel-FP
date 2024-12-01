<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserContoller extends Controller
{

    function register(Request $req)
    {
        $user = new User;
        $user->name = $req->input('name');
        $user->email = $req->input('email');
        $user->password = Hash::make($req->input('password'));
        $user->save();

        return $user;
    }

    function login(Request $req)
    {
        $user = User::where('email', $req->email)->first();

        if (!$user) {
            return response()->json([
                "Error" => "Email doesnt match with password"
            ], 401);
        } else {
            return response([
                "Success" => "Login Successfull"
            ], 202);
        }

        if (!Hash::check($req->password, $user->password)) {
            return response()->json([
                "Error" => "Password doesnt match with email!"
            ], 404);
        } else {
            return response([
                "Success" => "Password match with email!"
            ], 202);
        }

        return $user;
    }
}
