<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(AuthRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken('token')->plainTextToken;

        $authResource = new AuthResource(true, 'user register success', [
            'user' => $user,
            'token' => $token
        ]);
        return $authResource->response()->setStatusCode(200);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            $authResource = new AuthResource(false, 'Invalid Credential', []);
            return $authResource->response()->setStatusCode(401);
        }

        $token = $user->createToken('token')->plainTextToken;

        $authResource = new AuthResource(true, 'user Login success', [
            'user' => $user,
            'token' => $token
        ]);
        return $authResource->response()->setStatusCode(200);
    }
}
