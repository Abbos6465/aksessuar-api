<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function register(RegisterRequest $request){

        $user = User::create([
            'role_id'=>2,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $token = Auth::login($user);
        $user = new UserResource(auth()->user());
        return $this->respondWithToken($token ,$user);
    }

    public function login(LoginRequest $request)
    {
        

        $credentials = request(['username', 'password']);
        $token = auth()->attempt($credentials);
        if (! $token = auth()->attempt($credentials)) {
            return response()->errorJson('Username or password not match!', 401);
        }
        $user = new UserResource(auth()->user());
        return $this->respondWithToken($token ,$user);
    }

    public function me()
    {
        $user = new UserResource(auth()->user());
        if($user) {
            return response()->successJson($user);
        }
        return response()->errorJson('Unauthorized', 401);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {

        Auth::logout();

        return response()->successJson(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh(), \auth()->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $user)
    {
        return response()->successJson([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 36000,
            'user' => $user,
        ]);
    }
}
