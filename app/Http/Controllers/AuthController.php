<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        try{
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required'
            ]);

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'user'    => $user,
                'token'    => $user->createToken('Personal Access Token')->accessToken,
                'message' => 'User created successfully'
            ], 201);
        } catch (Exception $exception){
            return response()->json([
                'data'    => [],
                'message' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try{
            $request->validate(['email' => 'required|email', 'password' => 'required']);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)){
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $token = $user->createToken('Personal Access Token')->accessToken;

            return response()->json([
                'token' => $token,
                'user' => $user
            ]);
        } catch (Exception $exception){
            return response()->json([
                'token'   => null,
                'user'   => null,
                'message' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try{
            $request->user()->token()->revoke();
            return response()->json(['message' => 'Successfully logged out']);
        } catch (Exception $exception){
            return response()->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

    }
}
