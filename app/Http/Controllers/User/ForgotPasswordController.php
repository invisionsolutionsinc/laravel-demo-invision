<?php

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordUser;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
   |--------------------------------------------------------------------------
   | Password Reset Controller
   |--------------------------------------------------------------------------
   |
   | This controller is responsible for handling password reset emails and
   | includes a trait which assists in sending these notifications from
   | your application to your users. Feel free to explore this trait.
   |
   */

    use SendsPasswordResetEmails;


    public function __invoke(ForgotPasswordUser $request)
    {
        try{
            $data = $request->validated();
            //$this->validateEmail($request);
            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            $response = $this->broker()->sendResetLink(
                $request->only('email')
            );
            // return ['received' => true];
            return $response == Password::RESET_LINK_SENT
                ? $this->successResponse(true,'Reset link sent to your email')
                : $this->errorResponse( 'Unable to send reset link',[],401);

            return $response == Password::RESET_LINK_SENT
                ? response()->json(['message' => 'Reset link sent to your email.', 'success' => true], 200)
                : response()->json(['message' => 'Unable to send reset link', 'success' => false], 401);
        }catch (\Exception $e){
            return $this->errorResponse(commonMessages()->unknown, $e->getMessage());
        }
    }



    public function broker()
    {
        return Password::broker('users');
    }
}
