<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLogin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function login(UserLogin $request)
    {
        // credentials
        $credentials = $request->only(['email', 'password']);

        try {
            if ($token = $this->guard()->attempt($credentials)) {
                $success['token'] = $token;
                $user = $this->guard()->user();
                $success['user'] = $user;
                return $this->successResponse($success, 'logged in');
            }else{
                return $this->errorResponse('Wrong credentials - Email or Password is incorrect');
            }
        }
        catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function logout(Request $request)
   {
       $user = $this->guard()->user();
       auth()->logout();
       return $this->successResponse(true, 'Successfully logged out');
   }

    /**
     * Get the guard to be used during authentication.
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard('users');
    }
}
