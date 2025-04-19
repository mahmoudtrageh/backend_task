<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();           

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->success([
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 'User registered successfully');

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, 401);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            $data = $request->validated();

            $credentials = [
                'email' => $data['email'],
                'password' => $data['password'],
            ];

            if (!Auth::attempt($credentials)) {
                return $this->error('Invalid credentials', null, 401);
            }
            
            $user = User::where('email', $data['email'])->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->success([
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 'Login successful');

        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success(null, 'Successfully logged out');
    }

    public function user(Request $request)
    {
        $user = $request->user()->load([
            'departments', 
            'positions', 
            'userDepartmentPositions.department', 
            'userDepartmentPositions.position',
            'managedDepartments'
        ]);

        return $this->success(new UserResource($user));
    }
}