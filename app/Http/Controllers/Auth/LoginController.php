<?php

namespace App\Http\Controllers\Auth;
//
use App\Http\Controllers\Controller;
use App\Http\Requests\UserLogin;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
//    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function login(Request $request)
    {


        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($token = $this->attemptLogin($request)) {
            return $this->sendLoginResponse($request,$token);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
//        dd($this->guard());
        return $this->guard()->attempt(
            $this->credentials($request)
        );
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @param null $token
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request,$token = null)
    {
//        dd($token, $this->guard()->user());
        //$request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($token, $this->guard()->user())) {
            return $response;
        }

//        return $request->wantsJson()
//            ? new JsonResponse([], 204)
//            : redirect()->intended($this->redirectPath());
    }

    public function authenticated($token, $user){

        $success['token'] = $token;
        $success['user'] = $user;

//        dd(auth());

        return $this->successResponse($success, 'logged in');

    }


    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {

        return $this->errorResponse([
            $this->username() => [trans('auth.failed')],
        ],'402');
//        throw ValidationException::withMessages([
//            $this->username() => [trans('auth.failed')],
//        ]);
    }

    /**
     * Get the token array structure.
     * @param string $token
     * @return \Illuminate\Http\Response
     */
    protected function respondWithToken($token)
    {
        $a = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ];
        return $this->successResponse($a, 'user data');
    }
}