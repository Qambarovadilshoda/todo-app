<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Jobs\SendEmail;
use App\Mail\EmailVerify;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(AuthRegisterRequest $request)
    {
        $requestData = $request->validated();
        $user = new User();
        $user->name = $requestData['name'];
        $user->email = $requestData['email'];
        $user->verification_token = uniqid();
        $user->password = bcrypt($requestData['password']);
        $user->save();
        SendEmail::dispatch($user);
        return $this->success(new UserResource($user));
    }
    public function emailVerify(Request $request)
    {
        $user = User::where('verification_token', $request->token)->first();
        if (!$user || $user->verification_token !== $request->token) {
            return $this->error('You should verify', 404);
        }
        $user->email_verified_at = now();
        $user->save();
        return $this->success('Successfully verified');
    }
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user->email_verified_at === null) {
            return $this->error('You must verify your email', 403);
        }
        if (Hash::check($request->password, $user->password)) {
            $user->tokens()->delete();
            $token = $user->createToken('auth_login')->plainTextToken;
            return $this->success([new UserResource($user), $token]);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success('Successfully logouted');
    }
         public function findUser(Request $request){
            return $this->success( $request->user());
         }
}



