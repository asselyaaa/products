<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserLoginForm;
use App\Http\Requests\UserStoreForm;
use App\User;
use JWTAuth;

class UserController extends Controller
{
    public function register(UserStoreForm $request)
    {
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'role' => User::ROLE_USER
        ]);

        auth()->login($user);
        $token = JWTAuth::fromUser($user);

        return response()->json(compact('token', 'user'));
    }

    public function login(UserLoginForm $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json(compact('token'));
    }

}
