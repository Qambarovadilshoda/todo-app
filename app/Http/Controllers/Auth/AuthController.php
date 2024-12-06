<?php

namespace App\Http\Controllers\Auth;

use App\Customs\Services\EmailVerificationService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ResendVerificationLinkRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function __construct(private EmailVerificationService $service) {

    }
    public function register(Request $request){
         $request->validate([
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed|min:6',
         ]);

         $user = User::create([
            'email' => $request->email,
            'password' => bcrypt($request->password),
         ]);

         if($user) {
            $this->service->sendVerificationLink(($user));
            $token = $user->createToken('token-name')->plainTextToken;
            return $this->responseWithToken($token, $user);
         } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'An error occure while trying to create user'
             ], 500);
         }
    }

    public function responseWithToken($token, $user) {
        return response()->json([
            'status'=> 'succes',
            'user' => $user,
            'access_token' => $token,
            'type' => 'bearer'
        ]);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logget out successfully',

        ]);
    }

    public function login(Request $request) {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if(!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid login details'
            ]);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('token-name')->plainTextToken;

        if($user) {
            return response()->json([
                'success' => true,
                'user' => Auth::user(),
                'token' => $token
            ]);
        } else {
            return response()->json([
                'failed' => false,
                'message' => 'User Not Found'
            ], 404);
        }

    }

    public function verifyUserEmail(VerifyEmailRequest $request){
        return $this->service->verifyEmail($request->email, $request->token);
    }

    public function resendVerificationLink(ResendVerificationLinkRequest $request) {
        return $this->service->resendLink($request->email);
    }
}
