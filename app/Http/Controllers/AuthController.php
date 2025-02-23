<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Register a new user
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Generate and return a token
            $token = $user->createToken('API Token')->accessToken;

            $data = [
                'user' => $user,
                'token' => $token,
            ];

            return response()->success($data, __('User created successfully'), 201);
        }
        catch (ValidationException $e) {
            return response()->error($e->getMessage(), 400, $e->errors());
        } catch (\Exception $e) {
            return response()->error(__('Something went wrong'), 500);
        }
    }

    // Login an existing user
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->error(__('Invalid login credentials'), 401);
        }

        $user = Auth::user();
        $token = $user->createToken('Personal Access Token')->accessToken;

        $data = [
            'user' => $user,
            'token' => $token,
        ];

        return response()->success($data, __('Successfully logged in'));
    }

    // Logout the user (revoke token)
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->success(null, __('Successfully logged out'));
    }

    // Unauthorized route
    public function unauthenticated(Request $request)
    {
        return response()->unauthenticated();
    }
}
