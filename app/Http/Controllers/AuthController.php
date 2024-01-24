<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected UserRepository $repository;
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'string|required',
                'email' => 'string|email|required',
                'password' => 'string|required',
            ]);
            if ($validated) {
                $this->repository->store($request);
                return response()->json(
                    ['status' => 201, 'message' => 'register successful!'],
                    201
                );
            }
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 400,
                    'message' => $th->getMessage(),
                ],
                400
            );
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'string|email|required',
                'password' => 'string|required',
            ]);
            if ($validated) {
                $credentials = request(['email', 'password']);
                // if ($request->validated()) {
                if (!($token = auth()->attempt($credentials))) {
                    return response()->json(
                        ['status' => 401, 'message' => 'Unauthorized'],
                        401
                    );
                }
                return $this->respondWithToken($token);
            }

            // }
        } catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => 400,
                    'message' => $th->getMessage(),
                ],
                400
            );
        }
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'status' => 200,
            'access_token' => $token,
        ]);
    }
}
