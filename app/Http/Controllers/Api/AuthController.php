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
            return response()->errorJson('Foydalanuvchi nomi yoki parol mos emas!', 404);
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
        return response()->errorJson("Tizimga kirishga ruxsat yo'q", 401);
    }

    public function logout()
    {

        Auth::logout();

        return response()->successJson(['message' => 'Tizimdan muvaffaqqiyatli chiqildi']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh(), \auth()->user());
    }


    protected function respondWithToken($token, $user)
    {
        return response()->successJson([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60000,
            'user' => $user,
        ]);
    }
}
