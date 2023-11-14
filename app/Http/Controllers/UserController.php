<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function userLogin(Request $request)
    {
        $input = $request->all();
        $vallidation = Validator::make($input, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($vallidation->fails()) {
            return response()->json(['error' => $vallidation->errors()], 422);
        }


        if (Auth::attempt(['email' => $input['email'], 'password' => $input['password']])) {
            $user = Auth::user();
            // dd($user);

            $token = $user->createToken('MyApp')->accessToken;

            return response()->json(['token' => $token]);
        }

    }

    public function userRegister(Request $request)
    {
        $input = $request->all();

        $validation = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json(['error' => $validation->errors()], 422);
        }

        $input['password'] = bcrypt($input['password']);
        User::create($input);

        return response()->json(['message' => 'User created successfully']);
    }


    public function userDetails()
    {
        $user = Auth::guard('api')->user();

        return response()->json(['data' => $user]);
    }
}