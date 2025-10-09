<?php

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Master;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


class UserController extends Controller
{
    //
    public function index()
    {
        return response()->json(["okey"]);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $role = Role::where('slug', $data['role'])->firstOrFail();
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'role_id' => $role->id,
            'status' => 'active',
        ]);
        if ($data['role'] === 'master') {
            Master::create([
                'user_id' => $user->id,
                'bio' => $data['bio'] ?? '',
                'location' => $data['location'] ?? '',
                'rating' => null,
            ]);
        }
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => new UserResource($user),
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The      provided credentials are incorrect.'],
            ]);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'token' => $token,
            'user' => new UserResource($user) ,
        ]);
    }

    public function logout(Request $request)
    {
        Log::info('User', ['user' => $request->user()]);
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out.']);
    }

}
