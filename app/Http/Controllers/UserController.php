<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function AddUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {    
            return response()->json(['description' => 'Can not create user with this info'], 400);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['description' => 'Successful operation', 'token' => $user->createToken("API TOKEN")->plainTextToken], 201);
    }

    public function EditUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2',
            'email' => 'required|email|unique:users,email,'. Auth::id(),
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {    
            return response()->json(['description' => 'Can not edit user with this info'], 400);
        }

        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['description' => 'Successful operation'], 200);
    }

    public function DeleteUser()
    {
        $user = Auth::user();
        Auth::logout();
        $user->delete();
        return response()->json(['description' => 'Successful operation'], 200);
    }
}