<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Convert an authentication exception into an unauthenticated response.
     *

     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
    */

    protected function unauthenticated($request, AuthenticationException $exception)

    {
        if ($request->expectsJson()) {
            $response = ['success' => 'false','message' => 'You pass invalid token'];
            return $this->errorResponse($exception->getMessage(), $exception->getCode());
        }
        return $this->errorResponse("You are not authroized. Please Login", $exception->getCode(),$exception->getMessage());

    }

    

    public function errorResponse($error, $code = 401,$errorMessages = []){

        $statusCode = $code == 0 ? 401 : $code;
        $response = [
            'success' => false,
            'status_code' =>$statusCode,
            'message' => is_array($error) == TRUE ? $error : [$error],
            'data'    => []
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            return $this->errorResponse('You do not have the required authorization.' , 403);
            // return response()->json([
            //     'responseMessage' => 'You do not have the required authorization.',
            //     'responseStatus'  => 403,
            // ]);
        });
    }
}
