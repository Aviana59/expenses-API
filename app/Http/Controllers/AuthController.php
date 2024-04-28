<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register()
    {
        // validate request data
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'password_confirmation' => 'required|min:8'

        ]);

        // if data is invalid
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = new User(); // initialize new data

        // set the value
        $user->name = request()->name;
        $user->email = request()->email;
        $user->password = bcrypt(request()->password); // encrypt password

        $user->save(); //save

        return response()->json($user, 201);
    }

    public function login()
    {
        $credential = request(['email', 'password']); // get request data email and password

        // if cant login
        if (!$token = auth('api')->attempt($credential)) return response()->json(['error' => 'Unauthorized', 'message' => 'username or password is wrong'], 401);

        // return token and info
        return response()->json([
            'message' => 'success',
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]
        ]);;
    }

    // get detail user by login
    public function me()
    {

        return response()->json([
            'message' => 'success',
            'data' => auth()->user()
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();


        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return response()->json([
            'message' => 'success',
            'data' => [
                'access_token' => auth()->refresh(),
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60 //token expired
            ]
        ]);
    }
}
