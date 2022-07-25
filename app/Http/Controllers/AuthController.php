<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function Login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) 
        {    
            return response()->json(['description' => 'Can not log in with this info'], 401);
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        {
            $user = User::where('email', $request->email)->first();
            return response()->json(['description' => 'Successful operation', 'token' => $user->createToken("API TOKEN")->plainTextToken], 200);
        }

        return response()->json(['description' => 'Can not log in with this info'], 401);
    }

    public function logout() 
    {
        Auth::user()->tokens()->delete();
        return response()->json(['description' => 'Successful operation'], 200);
    }
}