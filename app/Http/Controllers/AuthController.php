<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
        ]);


        $user  = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        if($user->save()) {
            return response()->json([
                'message' => 'User created successfully',
                'status_code' => 201
            ], 201);
        }

        return response()->json([
            'message' => 'Some error occured, Please Try Again',
            'status_code' => 500
        ], 500);

    }

    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        if(!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return response()->json([
                'message' => 'Invalid username/password',
                'status_code' => 401
            ], 401);
        }

        $user = $request->user();

        if($user->role == 'administrator') {
            $tokenData = $user->createToken('Personal Access Tokens', ['administrator']);
        }
        else {
            $tokenData = $user->createToken('Personal Access Tokens', ['user']);
        }

        $token = $tokenData->token;

        if($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }

        if($token->save()) {
            return response()->json([
                'user' => $user,
                'access_token' => $tokenData->accessToken,
                'token_type' => 'Bearer',
                'token_scope' => $tokenData->token->scopes[0],
                'expires_at' => Carbon::parse($tokenData->token->expires_at)->toDateTimeString(),
                'status_code' => 200
            ], 200);
        }
        else {
            return response()->json([
                'message' => 'Some error occured, Please try again',
                'status_code' => 500
            ], 500);
        }
    }

    public function logout(Request $request)
    {

        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Logout Successfully',
            'status_code' => 200
        ], 200);

    }

    public function profile(Request $request)
    {

        if($request->user()) {

            return response()->json($request->user(), 200);

        }

        return response()->json([
            'message' => 'Not LoggedIn',
            'status_code' => 599
        ], 500);

    }
}
