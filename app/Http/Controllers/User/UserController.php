<?php

namespace App\Http\Controllers\User;

use App\Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegister;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {

    }

    /**
     * Register API
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(UserRegister $request)
    {
        $validatedData = $request->validated();
        $validatedData =  $request->except('confirm_password');


        $data = array();
        try {
            $input 				= $validatedData;
            // $input['talent']    = json_encode($input['talent']);
            // $input['additional_talent'] = json_encode($input['additional_talent']);
            // $input['interests'] = json_encode($input['interests']);
            $input['password'] 	= bcrypt($validatedData['password']);
            $user 				= User::create($input);
            $data['user'] 		=  $user;
            $response[] = $data;

            if (true) {
                return $this->successResponse($response, 'User Registered Successfully');
            } else {
                return $this->errorResponse('Oops ! Something went wrong , Please try again', 422, $e->getMessage());

            }

        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }


    public function resend_verification_code(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required'
        ]);

        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return errorResponse($validator->errors()->first(), $errors, 422);
        } else {
            $user = User::where(array('email' => $request['email']))->first();
            if (!isset($user->id)) {
                return errorResponse("Email Address doesn't exists in our record.", []);
            } else {
                if($user->is_verified == 1){
                    return errorResponse('Already verified', []);
                }
                $user->getVerificationCode();
                if (true) {
                    return  successResponse([], 'Verification Code has been sent!');
                } else {
                    return errorResponse('Verification Code not sent, Please try again later', []);
                }
            }
        }
    }

    /**
     * Verifying the code
     * @param Request $request
     * @return
     */
    public function verify_account(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'email' => 'required'
        ]);

        if ($validator->fails()) {
            $errors[] = $validator->errors();
            return errorResponse($validator->errors()->first(), $errors, 422);
        } else {
            $user = User::where(array('email' => $request['email']))->first();
            if (!isset($user->id)) {
                return errorResponse('Email Not found', []);
            } else {
                if($user->verifyOtp($request->code) == true){
                    $userData = User::find($user->id);
                    return  successResponse($userData, 'User has been verified, Login to your account!');
                }else{
                    return errorResponse('Invalid code', []);
                }

            }
        }
    }

    /**
     * User Detail API
     * @param Request $request
     * @return
     */
    public function user_details(Request $request)
    {
        try {
            $user = $request->user();
            $array[] = json_decode(json_encode($user), true);
            $user_data = array_map('arrayMap', $array);
            return successResponse($user_data, 'User Data Found.');
        } catch (\Exception $e) {
            $errorMessages = $e->getMessage();
            logError($e, 'user_detail', 'User/UserController');
            return errorResponse(commonMessages()->unknown, $errorMessages);
        }
    }

    /**
     * User Update API
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function user_update(Request $request)
    {
        try {
            $user = $request->user();
            $validator = Validator::make($request->all(), [
                'phone_number' => 'sometimes|required',
                'email' => 'sometimes|required',
                'first_name' => 'sometimes|required',
                'last_name' => 'sometimes|required',
                'organization'=>'sometimes|required'
            ]);

            if ($validator->fails()) {
                $errors[] = $validator->errors();
                return errorResponse($validator->errors()->first(), $errors, 422);
            } else {
                $data = $request->all();
                if (array_key_exists('password', $data)) {
                    $data['password'] = bcrypt($data['password']);
                }

                $user->update($data);
                $user = User::find($user->id);
                $array[] = json_decode(json_encode($user), true);
                $user_data = array_map('arrayMap', $array);
                return $this->successResponse($user_data, 'User has been updated successfully!');
            }
        } catch (\Exception $e) {
            $errorMessages = $e->getMessage();
            logError($e, 'user_update', 'User/UserController');
            return errorResponse(commonMessages()->unknown, $errorMessages);
        }
    }


}
